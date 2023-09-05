<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemAssign;
use App\Models\ItemRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemRequestController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? 'Car';
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $items = Item::withCount([
            'In as in_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
            },
            'ItemAssign as assign_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'))
                    ->whereNull('approved_at')->where('status', '!=', 1);
            },
            'ItemRequest as store_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'))
                    ->whereNull('approved_at')->where('status', '!=', 1);
            },
        ]);

        if (Auth::user()->role == 'Sales') {
            $itemRequest = ItemRequest::orderBy('id', 'desc')
                ->whereBetween('created_at', $this->week)
                ->where('id_user', Auth::user()->id);
        } else {
            $itemRequest = ItemRequest::orderBy('id', 'desc')->whereBetween('created_at', $this->week);
        }

        $data = [
            'sales' => User::where('role', 'Sales')->get(),
            'items' => $items->get(),
            'ItemRequest' => $itemRequest->get(),
            'type' => $type
        ];
        return view('items.request.index', $data);
    }

    public function store(Request $request)
    {
        $text = ($request->id == null) ? 'create' : 'update';
        DB::beginTransaction();
        try {
            $item = ItemRequest::find($request->id);
            if (!$item) {
                $item = new ItemRequest;

                $today = ItemRequest::whereDate('created_at', date('Y-m-d'))
                    ->where('id_user', Auth::user()->id)
                    ->where('id_item', $request->item)
                    ->first();
                
                if ($today != null) {

                    self::failed("Only 1 item can request today!");

                    return redirect()->back();
                }
            }

            if ($request->stock > $request->stock_left) {

                self::failed("Failed to $text Item Request! not enough stocks ");
                return redirect()->back();
            }
            $item->id_user = Auth::user()->id;
            $item->id_item = $request->item;
            $item->stock = $request->stock;
            $item->type = 'request';
            $item->status = -1;
            $item->save();

            Notification::create([
                'title' => 'Request Item from ' . Auth::user()->name,
                'text' => "You have {$request->stock} items stock to review",
                'id_user' => 0
            ]);

            DB::commit();

            self::success("Item Request {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item Request! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function approve(Request $request, string $id)
    {

        $text = 'confirm';
        DB::beginTransaction();
        try {
            $item = ItemRequest::find($id);
            $item->status = 1;
            $item->save();

            DB::commit();

            self::success("Item Assign {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item Assign! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {

        $text = 'delete';
        DB::beginTransaction();
        try {
            $item = ItemRequest::find($id);
            $item->forceDelete();

            DB::commit();

            self::success("Item Assign {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item Assign! " . $e->getMessage());

            return redirect()->back();
        }
    }
}
