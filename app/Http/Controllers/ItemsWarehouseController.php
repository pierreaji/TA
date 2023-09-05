<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemAssign;
use App\Models\ItemRequest;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemsWarehouseController extends Controller
{
    public function index(Request $request)
    {

        $isDetail = $request->detailDate != null;
        if (isset($request->notif) && $request->notif != null) {
            Notification::where('id', $request->notif)->update([
                'is_read' => true
            ]);
        }

        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $type = $request->warehouse ?? 'All';
        $items = Item::withCount([
            'In as in_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
            },
            'ItemAssign as assign_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
            },
            'ItemRequest as store_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
            },
        ]);


        $sales = User::where('role', 'Sales')->get();

        if (isset($request->sales) && $request->sales != null) {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            $firstSales = $sales->first();
        }

        if(Auth::user()->role == 'Sales') {
            $firstSales = Auth::user();
        }

        if (Auth::user()->role == 'Admin') {
            $itemRequest = ItemAssign::orderBy('id', 'desc')->where('id_user', $firstSales->id)->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'identity')->whereNull('approved_at')->whereBetween('created_at', $this->week)
            // ->where('is_temp', false)
            ->where('status', -1);
            $itemRequest = ItemRequest::orderBy('id', 'desc')->where('id_user', $firstSales->id)->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'id as identity')->whereNull('approved_at')->whereBetween('created_at', $this->week)->where('status', -1)->union($itemRequest);
        } else {
            $itemRequest = ItemAssign::orderBy('id', 'desc')->where('id_user', $firstSales->id)->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'identity')->whereBetween('created_at', $this->week)->where('is_temp', false)->whereNotIn('status', [2, -1]);
            $itemRequest = ItemRequest::orderBy('id', 'desc')->where('id_user', $firstSales->id)->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'id as identity')->whereBetween('created_at', $this->week)->whereNotIn('status', [2, -1])->union($itemRequest);
        }

        // if (Auth::user()->role != 'Sales') {
            if (isset($request->warehouse) && $request->warehouse == 'Confirmed') {
                $itemRequest = ItemAssign::orderBy('id', 'desc')->where('id_user', $firstSales->id)->whereNotNull('approved_at')->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'identity')->whereBetween('created_at', $this->week)->where('status', 1);
                $itemRequest = ItemRequest::orderBy('id', 'desc')->where('id_user', $firstSales->id)->whereNotNull('approved_at')->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'id as identity')->whereBetween('created_at', $this->week)
                    ->where('status', 1)->union($itemRequest);
            }
    
            if (isset($request->warehouse) && $request->warehouse == 'Rejected') {
                $itemRequest = ItemAssign::orderBy('id', 'desc')->where('id_user', $firstSales->id)->whereNotNull('approved_at')->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'identity')->whereBetween('created_at', $this->week)->where('status', 2);
                $itemRequest = ItemRequest::orderBy('id', 'desc')->where('id_user', $firstSales->id)->whereNotNull('approved_at')->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'id as identity')->whereBetween('created_at', $this->week)
                    ->where('status', 2)->union($itemRequest);
            }
    
            if (isset($request->warehouse) && $request->warehouse == 'All') {
                $itemRequest = ItemAssign::orderBy('id', 'desc')->where('id_user', $firstSales->id)->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'identity')->whereBetween('created_at', $this->week)->where('status', '!=', -1);
                $itemRequest = ItemRequest::orderBy('id', 'desc')->where('id_user', $firstSales->id)->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'id as identity')->whereBetween('created_at', $this->week)
                    ->where('status', '!=', -1)
                    ->union($itemRequest);
            }
    
            if (isset($request->warehouse) && $request->warehouse == 'Request') {
                $itemRequest = ItemAssign::orderBy('id', 'desc')->where('id_user', $firstSales->id)->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'identity')->whereBetween('created_at', $this->week)->where('status', '=', 0);
                $itemRequest = ItemRequest::orderBy('id', 'desc')->where('id_user', $firstSales->id)->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'id as identity')->whereBetween('created_at', $this->week)
                    ->where('status', '=', 0)
                    ->union($itemRequest);
            }
        // }

        if (Auth::user()->role == 'Admin') {
            $itemRequestHistory = ItemAssign::orderBy('id', 'desc')->with('Trx')->where('id_user', $firstSales->id)->whereNotNull('approved_at')->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'created_at')->whereBetween('created_at', $this->week)->where('status', 1);
            $itemRequestHistory = ItemRequest::orderBy('id', 'desc')->with('Trx')->where('id_user', $firstSales->id)->whereNotNull('approved_at')->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'created_at')->whereBetween('created_at', $this->week)
                ->where('status', 1)->union($itemRequestHistory);
        } else {
            $itemRequestHistory = ItemAssign::orderBy('id', 'desc')->with('Trx')->where('id_user', $firstSales->id)->whereNotNull('approved_at')->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'created_at')->whereBetween('created_at', $this->week)->where('status', 1);
            $itemRequestHistory = ItemRequest::orderBy('id', 'desc')->with('Trx')->where('id_user', $firstSales->id)->whereNotNull('approved_at')->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'created_at')->whereBetween('created_at', $this->week)
                ->where('status', 1)->union($itemRequestHistory);
        }

        $itemData = [];

        if (!$isDetail) {
            foreach ($itemRequestHistory->get() as $row) {
                $date = date('Y-m-d', strtotime($row->created_at));
                if (!isset($itemData[$date])) {
                    $itemData[$date] = [];
                }

                if (!isset($itemData[$date]['all_stock'])) {
                    $itemData[$date]['all_stock'] = 0;
                    $itemData[$date]['total'] = 0;
                    $itemData[$date]['id_item'] = [];
                }

                if (!in_array($row['id_item'], $itemData[$date]['id_item'])) {
                    $itemData[$date]['id_item'][] = $row['id_item'];
                }
                $itemData[$date]['all_item'] = count($itemData[$date]['id_item']);
                $itemData[$date]['all_stock'] += ($row?->stock ?? 0);
                $itemData[$date]['total'] += ($row?->stock ?? 0) * $row->Item->sale_price;
                $itemData[$date]['date'] = $row->created_at;
            }
        } else {
            $itemDataTemp = ItemAssign::orderBy('id', 'desc')
                ->where('id_user', $firstSales->id)
                ->whereNotNull('approved_at')
                ->with('Trx')
                ->whereDate('created_at', $request->detailDate)
                ->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'created_at')
                ->where('status', 1);
            $itemDataTemp = ItemRequest::orderBy('id', 'desc')
                ->where('id_user', $firstSales->id)
                ->whereNotNull('approved_at')
                ->with('Trx')
                ->whereDate('created_at', $request->detailDate)
                ->select('id', 'id_item', 'id_user', 'stock', 'type', 'status', 'created_at')
                ->where('status', 1)
                ->union($itemDataTemp)->get();

            foreach ($itemDataTemp as $row) {
                
                if (!isset($itemData[$row->id_item])) {
                    $itemData[$row->id_item] = [];
                }

                if (!isset($itemData[$row->id_item]['stock'])) {
                    $itemData[$row->id_item]['stock'] = 0;
                    $itemData[$row->id_item]['total'] = 0;
                }

                $itemData[$row->id_item]['item'] = $row->Item;
                $itemData[$row->id_item]['stock'] += $row->stock;
                $itemData[$row->id_item]['total'] += $row->stock * $row->Item->sale_price;
                // $itemData[$row->id_item] = [
                //     'item_name' => $row->Item->name,
                //     'item_category' => $row->Item->Category->name,
                //     'item_sold' => isset($itemData[$row->id_item]) ? $itemData[$row->id_item]['item_sold'] + ($row->stock ?? 0) : $row->stock ?? 0,
                //     'item_price' => $row->Item->sale_price
                // ];
            }
        }

        $data = [
            'sales' => $sales,
            'firstSales' => $firstSales,
            'items' => $items->get(),
            'ItemRequest' => $itemRequest->get(),
            'type' => $type,
            'warehouseHistory' => $itemData
        ];

        if ($isDetail) {

            return view('items.warehouse.index_detail', $data);
        }

        return view('items.warehouse.index', $data);
    }

    public function confirm(Request $request)
    {
        $text = ($request->id == null) ? 'create' : 'update';
        DB::beginTransaction();
        try {
            $selected = json_decode($request->selected_row, true);
            if (count($selected) < 1) {
                self::failed("Please select item first! ");

                return redirect()->back();
            }

            foreach ($selected as $row) {
                if ($row == 'all') continue;
                $val = explode('|', $row);
                if ($val[1] == 'request') {
                    $itemRequest = ItemRequest::where('id_user', $request->id_user)
                        ->where('id', $val[0])
                        ->where(function ($query) {
                            $query->where('status', 0)
                                ->orWhere('status', -1);
                        })
                        ->update([
                            'approved_at' => Carbon::now(),
                            'status' => $request->status_approve
                        ]);
                } else {
                    $itemRequest = ItemAssign::where('id_user', $request->id_user)->whereNull('approved_at')
                        ->where('identity', $val[0])
                        ->where(function ($query) {
                            $query->where('status', 0)
                                ->orWhere('status', -1);
                        })
                        ->update([
                            'approved_at' => Carbon::now(),
                            'status' => $request->status_approve
                        ]);
                }
            }

            DB::commit();

            self::success("Items Confirm {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Items Confirm! " . $e->getMessage());

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
            $item->delete();

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
