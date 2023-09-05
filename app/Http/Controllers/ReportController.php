<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Distributor;
use App\Models\Item;
use App\Models\ItemAssign;
use App\Models\ItemIn;
use App\Models\ItemRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function profit(Request $request)
    {
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $type = $request->warehouse ?? 'Request';
        $items = Item::withCount([
            'Transactions as sold_stocks' => function ($query) {
                $query->select(DB::raw('SUM(items_sold)'));
                $query->whereBetween('created_at', $this->week);
            },
        ]);

        $data = [
            'items' => $items->get(),
            'type' => $type,
        ];
        return view('report.profit.index', $data);
    }

    public function sales(Request $request)
    {
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $type = $request->warehouse ?? 'Request';
        $transactions = Transaction::where('id_user', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->whereBetween('created_at', $this->week);

        $data = [
            'transactions' => $transactions->get(),
            'type' => $type,
        ];
        return view('report.sales.index', $data);
    }

    public function salesRequest(Request $request)
    {
        $isDetail = $request->detailDate != null;
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $sales = User::where('role', 'Sales')->get();
        if (isset($request->sales) && $request->sales != null && $request->sales != 'undefined') {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            if (Auth::user()->role == 'Sales') {
                $firstSales = $sales->where('id', Auth::user()->id)->first();
            } else {

                $firstSales = $sales->first();
            }
        }




        if ($isDetail) {
            $itemRequest = ItemAssign::where('id_user', $firstSales->id)
                ->selectRaw('id_item, stock, created_at')
                ->with('Item')
                // ->whereNotNull('approved_at')
                ->where('status', 1)
                ->whereDate('created_at', $request->detailDate);
        } else {
            $itemRequest = ItemAssign::where('id_user', $firstSales->id)
                ->selectRaw('id_item, stock, created_at')
                ->whereBetween('approved_at', $this->week)
                ->where('status', 1)
                // ->whereNotNull('approved_at')
                ->with('Item');
        }

        if ($isDetail) {
            $itemRequest = ItemRequest::where('id_user', $firstSales->id)
                ->selectRaw('id_item, stock, created_at')
                ->whereDate('created_at', $request->detailDate)
                ->with('Item')
                ->where('status', 1)
                // ->whereNotNull('approved_at')
                ->union($itemRequest);
        } else {
            $itemRequest = ItemRequest::where('id_user', $firstSales->id)
                ->selectRaw('id_item, stock, created_at')
                ->whereBetween('created_at', $this->week)
                ->with('Item')
                ->where('status', 1)
                // ->whereNotNull('approved_at')
                ->union($itemRequest);
        }

        $itemRequest = $itemRequest->get();

        $itemData = [];

        if (!$isDetail) {
            foreach ($itemRequest as $row) {
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
                $itemData[$date]['all_stock'] += $row->stock;
                $itemData[$date]['total'] += $row->stock * $row->Item->sale_price;
                $itemData[$date]['date'] = $row->created_at;
            }
        } else {
            // $itemData = $itemRequest;
            
            foreach($itemRequest as $row) {
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

            }
        }

        // dd($itemData);


        $data = [
            'ItemRequest' => $itemData,
            'sales' => $sales,
            'firstSales' => $firstSales,
        ];

        if ($isDetail) {
            return view('report.sales.received_detail', $data);
        }

        return view('report.sales.received', $data);
    }

    public function inOut()
    {
        return view('report.in-out.index');
    }

    public function in(Request $request)
    {
        $distributor = Distributor::all();
        $firstDist = $distributor->first();

        if (isset($request->distributor) && $request->distributor != null) {
            $firstDist = $distributor->where('id', $request->distributor)->first();
        }

        $isDetail = $request->detailDate != null;
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        if ($firstDist->id != null) {
            $itemsIn = ItemIn::whereBetween('incoming_item_date', $this->week)
                ->whereHas('Item', function ($query) use ($firstDist) {
                    $query->where('id_distributor', $firstDist->id);
                });
        } else {
            $itemsIn = ItemIn::whereBetween('incoming_item_date', $this->week);
        }

        $itemData = [];

        if (!$isDetail) {
            foreach ($itemsIn->get() as $row) {
                $date = date('Y-m-d', strtotime($row->incoming_item_date));
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
                $itemData[$date]['all_stock'] += $row->stock;
                $itemData[$date]['total'] += $row->stock * $row->Item->sale_price;
                $itemData[$date]['date'] = $row->incoming_item_date;
            }
        } else {
            $itemData = $itemsIn->get();
        }

        $data = [
            'itemsIn' => $itemsIn->orWhereDate('incoming_item_date', $request->detailDate)->whereHas('Item', function ($query) use ($firstDist) {
                $query->where('id_distributor', $firstDist->id);
            })->get(),
            'itemData' => $itemData,
            'distributor' => $distributor,
            'firstDist' => $firstDist,
        ];

        if ($isDetail) {

            return view('report.in.detail', $data);
        }

        return view('report.in.index', $data);
    }
}
