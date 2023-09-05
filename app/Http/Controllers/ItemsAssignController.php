<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Distributor;
use App\Models\Item;
use App\Models\ItemAssign;
use App\Models\ItemAssignTemp;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserSales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemsAssignController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->notif) && $request->notif != null) {
            Notification::where('id', $request->notif)->update([
                'is_read' => true
            ]);
        }
        $type = $request->type ?? 'Car';
        $sales = User::where('role', 'Sales')->get();
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        if (isset($request->sales) && $request->sales != null) {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            $firstSales = $sales->first();
        }
        $items = Item::withCount([
            'In as in_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                // $query->where('id_user', $firstSales->id);
            },
            'ItemAssign as assign_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                    // ->whereNotNull('approved_at')->whereIn('status', [0,1]);
                // $query->where('id_user', $firstSales->id);
            },
            'ItemRequest as store_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                    // ->whereNotNull('approved_at')->whereIn('status', [0,1]);
                // $query->where('id_user', $firstSales->id);
            },
        ]);

        // dd(ItemAssign::all()->toArray());

        $assignIdentity = ItemAssign::whereNull('approved_at')
            ->select('identity', 'stock', DB::raw('COUNT(id_user) user_total'), 'is_temp', 'id_item')
            ->with('Item')
            ->groupBy('identity', 'is_temp', 'id_item', 'stock')
            ->whereBetween('created_at', $this->week)
            ->get();


        $data = [
            'sales' => User::where('role', 'Sales')->get(),
            'sales1' => User::where('id', $request->sales)->get(),
            'items' => $items->get(),
            'firstSales' => $firstSales,
            'itemAssign' => $assignIdentity,
            'type' => $type
        ];
        return view('items.assign.index', $data);
    }

    public function store(Request $request)
    {
        $text = ($request->id == null) ? 'create' : 'update';
        DB::beginTransaction();
        try {
            $sales = UserSales::all();
            $stocks = count($sales) * $request->stock;
            $item = ItemAssign::find($request->id);
            if ($stocks > $request->stock_left) {

                self::failed("Failed to $text Item Assign! not enough stocks ");
                return redirect()->back();
            }
            start:
            $identity = rand(0, 99999999);
            $check = ItemAssign::where('identity', $identity)->first();
            if($check != null) goto start;
            if (!$item) {
                foreach ($sales as $sale) {
                    $item = ItemAssign::where([
                        'id_item' => $request->item,
                        'id_user' => $sale->id_user,
                        'status' => 0,
                        'is_temp' => true
                    ])->first();
                    $stock = $request->stock;
                    if($item == null) {
                        $item = new ItemAssign;
                    } else {
                        $stock += $item->stock;
                    }
                    $item->id_item = $request->item;
                    $item->id_user = $sale->id_user;
                    $item->type = 'assign';
                    $item->stock = $stock;
                    $item->identity = $identity;
                    $item->save();
                    Notification::create([
                        'title' => 'Assign Item from Admin',
                        'text' => "You have {$request->stock} items stock to assign",
                        'target' => $sale->id_user
                    ]);
                }
            } else {
                if ($request->stock > $request->stock_left) {

                    self::failed("Failed to $text Item Assign! not enough stocks ");
                    return redirect()->back();
                }
                $item->id_item = $request->item;
                $item->type = 'assign';
                $item->stock = $request->stock;
                $item->save();
                
            }

            Notification::create([
                'title' => 'Assign Item from Admin',
                'text' => "You have {$request->stock} items to review"
            ]);

            DB::commit();

            self::success("Item Assign {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item Assign! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function confirm()
    {

        $text = 'confirm';
        DB::beginTransaction();
        try {
            $sales = UserSales::all();

            $assigns = ItemAssign::where('is_temp', true)->get();
            // dd($assigns);
            foreach($assigns as $as) {
                $item = ItemAssign::find($as->id);
                $item->id_item = $as->id_item;
                $item->id_user = $as->id_user;
                $item->type = 'assign';
                $item->stock = $as->stock;
                $item->is_temp = false;
                $item->identity = $as->identity;
                $item->created_at = Carbon::now();
                $item->updated_at = Carbon::now();
                $item->save();
                // ItemAssign::find($as->id)->forceDelete();
            }

            DB::commit();

            self::success("Item Assign {$text}ed successfully!");

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
            $item = ItemAssign::where('identity', $id);
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
