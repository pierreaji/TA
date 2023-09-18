<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Distributor;
use App\Models\Item;
use App\Models\ItemAssign;
use App\Models\ItemIn;
use App\Models\ItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemsInController extends Controller
{
    public function index(Request $request)
    {
        $distributor = Distributor::all();
        $category = Category::all();
        $firstDist = $distributor->first();
        $firstCat = null;

        if (isset($request->distributor) && $request->distributor != null) {
            $firstDist = $distributor->where('id', $request->distributor)->first();
            $firstCat = $category->where('id', $request->category)->first();
        }

        if ($firstCat == null) {
            $firstCat = new Category();
            $firstCat->id = '';
        }

        if ($firstDist == null || !isset($request->distributor) && $request->distributor == null) {
            $firstDist = new Distributor();
            $firstDist->id = '';
        }

        if ($firstDist->id != null) {
            $items = Item::where('id_distributor', $firstDist->id)->get();
        } else {

            $items = Item::all();
        }
        $itemsIn = ItemIn::whereHas('Item', function ($query) use ($firstDist, $firstCat) {
            if ($firstDist->id != null) {
                $query->where('id_distributor', $firstDist->id);
            }
            if ($firstCat->id != null) {
                $query->where('id_category', $firstCat->id);
            }
        })->get();

        $data = [
            'itemsIn' => $itemsIn,
            'distributor' => $distributor,
            'firstDist' => $firstDist,
            'category' => $category,
            'firstCat' => $firstCat,
            'items' => $items
        ];

        return view('items.in.index', $data);
    }
    public function stock(Request $request)
    {
        $distributor = Distributor::all();
        $category = Category::all();

        $items = Item::withCount([
            'In as stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
            },
            'ItemAssign as assign_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
                $query->where(function ($query) {
                    $query->whereNotNull('approved_at');
                    $query->where('status', '!=', 2);
                });
                $query->orWhere(function ($query) {
                    $query->whereNotNull('approved_at');
                    $query->whereNotNull('deleted_at');
                });
            },
            'ItemRequest as store_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
                $query->where(function ($query) {
                    $query->whereNotNull('approved_at');
                    $query->where('status', '!=', 2);
                });
                $query->orWhere(function ($query) {
                    $query->whereNotNull('approved_at');
                    $query->whereNotNull('deleted_at');
                });
            },
            'Transactions as items_sold' => function ($query) {
                $query->select(DB::raw('SUM(items_sold)'));
                $query->whereNotNull('is_return');
            },
        ]);

        $itemRequest = ItemRequest::withTrashed()
            ->select('stock', 'id_item', 'deleted_at', DB::raw('id as is_temp'))
            ->whereNotNull('approved_at')
            ->where('status', 1)
            ->whereNotNull('deleted_at');
        $itemAssign = ItemAssign::withTrashed()
            ->select('stock', 'id_item', 'deleted_at', DB::raw('id as is_temp'))
            ->whereNotNull('approved_at')
            ->where('status', 1)
            ->whereNotNull('deleted_at')->union($itemRequest);

        if (isset($request->distributor) && $request->distributor != null) {
            $firstDist = $distributor->where('id', $request->distributor)->first();
            $items = $items->where('id_distributor', $firstDist->id);
        } else {
            $firstDist = new Distributor();
            $firstDist->id = '';
        }

        if (isset($request->category) && $request->category != null) {
            $firstCat = $category->where('id', $request->category)->first();
            $items = $items->where('id_category', $firstCat->id);
        } else {
            $firstCat = new Category();
            $firstCat->id = '';
        }

        $data = [
            'distributor' => $distributor,
            'firstDist' => $firstDist,
            'category' => $category,
            'firstCat' => $firstCat,
            'items' => $items->get(),
            'itemsDelete' => $itemAssign->get(),
        ];

        return view('items.in.stock', $data);
    }

    public function store(Request $request)
    {
        $text = ($request->id == null) ? 'create' : 'update';
        DB::beginTransaction();
        try {
            foreach ($request->item as $i => $row) {
                $item = new ItemIn;
                $item->id_item = $row;
                $item->id_user = Auth::user()->id;
                $item->stock = $request->stock[$i];
                $item->incoming_item_date = $request->incoming_item_date[$i];
                $item->save();
            }

            DB::commit();

            self::success("Item In {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item In! " . $e->getMessage());

            return redirect()->back();
        }
    }
    public function update(Request $request)
    {
        $text = ($request->id == null) ? 'create' : 'update';
        DB::beginTransaction();
        try {
            $item = ItemIn::find($request->id);
            $item->id_item = $request->edit_item;
            $item->id_user = Auth::user()->id;
            $item->stock = $request->edit_stock;
            $item->incoming_item_date = $request->edit_incoming_item_date;
            $item->save();

            DB::commit();

            self::success("Item In {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item In! " . $e->getMessage());

            return redirect()->back();
        }
    }
    public function destroy(string $id)
    {

        $text = 'delete';
        DB::beginTransaction();
        try {
            $item = ItemIn::find($id);
            $item->delete();

            DB::commit();

            self::success("Item In {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Item In! " . $e->getMessage());

            return redirect()->back();
        }
    }
}
