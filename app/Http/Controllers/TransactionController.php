<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemAssign;
use App\Models\ItemRequest;
use App\Models\SalesTracking;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $items = Item::withCount([
            'ItemAssign as assign_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
                $query->where('id_user', Auth::user()->id);
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            },
            'ItemRequest as store_stocks' => function ($query) {
                $query->select(DB::raw('SUM(stock)'));
                $query->where('id_user', Auth::user()->id);
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            },
            'Transactions as sold_stocks' => function ($query) {
                $query->select(DB::raw('SUM(items_sold)'));
                $query->where('id_user', Auth::user()->id);
                $query->whereBetween('created_at', $this->week);
            },
            'Transactions as return_stocks' => function ($query) {
                $query->select(DB::raw('SUM(date_return)'));
                $query->where('id_user', Auth::user()->id);
                $query->whereNotNull('date_return');
                $query->whereBetween('date_return', $this->week);
            },
        ])->where(function ($query) {
            $query->whereHas('ItemAssign', function ($query) {
                $query->where('id_user', Auth::user()->id);
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            });
            $query->orWhereHas('ItemRequest', function ($query) {
                $query->where('id_user', Auth::user()->id);
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            });
        })->with([
            'ItemAssign', 'ItemRequest'
        ]);

        $transactionsHistory = Transaction::where('id_user', Auth::user()->id)
            ->whereBetween('created_at', $this->week);

        $itemData = [];

        foreach ($transactionsHistory->get() as $row) {
            $date = date('Y-m-d', strtotime($row->created_at)) . ' ' . $row->shop_name;
            if (!isset($itemData[$date])) {
                $itemData[$date] = [];
            }

            if (!isset($itemData[$date]['all_stock'])) {
                $itemData[$date]['all_stock'] = 0;
                $itemData[$date]['total'] = 0;
            }
            $itemData[$date]['shop'] = $row->shop_name;
            $itemData[$date]['all_stock'] += $row->items_sold;
            $itemData[$date]['total'] += $row->items_sold * $row->Item->sale_price;
            $itemData[$date]['date'] = $row->created_at;
        }



        $data = [
            'items' => $items->get(),
            'transactionsHistory' => $itemData
        ];

        return view('sales.transaction.index', $data);
    }

    public function history(Request $request)
    {
        $isDetail = $request->detailDate != null;

        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $sales = User::where('role', 'Sales')->get();
        if (isset($request->sales) && $request->sales != null) {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            $firstSales = $sales->first();
        }
        $type = $request->warehouse ?? 'Request';
        $items = Item::withCount([
            'ItemAssign as assign_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            },
            'ItemRequest as store_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            },
            'Transactions as sold_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(items_sold)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereBetween('created_at', $this->week);
            },
        ])->with([
            'Transactions' => function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereBetween('date_return', $this->week);
            }
        ])->where(function ($query) use ($firstSales) {
            $query->whereHas('ItemAssign', function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            });
            $query->orWhereHas('ItemRequest', function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            });
        });

        $transactions = Transaction::where('id_user', Auth::user()->role == 'Admin' ? $firstSales->id : Auth::user()->id)
            ->whereBetween('created_at', $this->week);

        $transactionsHistory = Transaction::where('id_user', Auth::user()->role == 'Admin' ? $firstSales->id : Auth::user()->id)
            ->whereBetween('created_at', $this->week);

        if ($isDetail) {
            $transactions = Transaction::where('id_user', Auth::user()->role == 'Admin' ? $firstSales->id : Auth::user()->id)
                ->select('id_item', 'shop_name', DB::raw('SUM(items_sold) as items_sold'))
                ->groupBy('id_item', 'shop_name')
                ->where('shop_name', $request->shop)
                ->whereDate('created_at', $request->detailDate);
        }

        $itemData = [];

        foreach ($transactionsHistory->get() as $row) {
            $date = date('Y-m-d', strtotime($row->created_at)) . ' ' . $row->shop_name;
            if (!isset($itemData[$date])) {
                $itemData[$date] = [];
            }

            if (!isset($itemData[$date]['all_stock'])) {
                $itemData[$date]['all_stock'] = 0;
                $itemData[$date]['total'] = 0;
            }
            $itemData[$date]['shop'] = $row->shop_name;
            $itemData[$date]['all_stock'] += $row->items_sold;
            $itemData[$date]['total'] += $row->items_sold * $row->Item->sale_price;
            $itemData[$date]['date'] = $row->created_at;
        }


        $data = [
            'items' => $items->get(),
            'type' => $type,
            'sales' => $sales,
            'firstSales' => $firstSales,
            'transactions' => $transactions->get(),
            'transactionsHistory' => $itemData
        ];

        if (Auth::user()->role == 'Admin') {
            return view('sales.transaction.history_admin', $data);
        }

        if ($isDetail) {
            return view('sales.transaction.history_detail', $data);
        }

        return view('sales.transaction.history', $data);
    }

    public function historyAdmin(Request $request)
    {

        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $sales = User::where('role', 'Sales')->get();
        if (isset($request->sales) && $request->sales != null) {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            $firstSales = $sales->first();
        }
        $type = $request->warehouse ?? 'Request';
        $items = Item::withCount([
            'ItemAssign as assign_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            },
            'ItemRequest as store_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            },
            'Transactions as sold_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(items_sold)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereBetween('created_at', $this->week);
            },
        ])->where(function ($query) use ($firstSales) {
            $query->whereHas('ItemAssign', function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            });
            $query->orWhereHas('ItemRequest', function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            });
        });

        $transactions = Transaction::where('id_user', Auth::user()->role == 'Admin' ? $firstSales->id : Auth::user()->id)
            ->whereBetween('created_at', $this->week);

        $data = [
            'items' => $items->get(),
            'type' => $type,
            'sales' => $sales,
            'firstSales' => $firstSales,
            'transactions' => $transactions->get()
        ];

        return view('sales.transaction.history_admin', $data);
    }

    public function historySale(Request $request)
    {

        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $sales = User::where('role', 'Sales')->get();
        if (isset($request->sales) && $request->sales != null) {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            $firstSales = $sales->first();
        }
        $type = $request->warehouse ?? 'Request';
        $items = Item::withCount([
            'ItemAssign as assign_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            },
            'ItemRequest as store_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            },
            'Transactions as sold_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(items_sold)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereBetween('created_at', $this->week);
            },
        ])->where(function ($query) use ($firstSales) {
            $query->whereHas('ItemAssign', function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            });
            $query->orWhereHas('ItemRequest', function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
                $query->whereBetween('approved_at', $this->week);
            });
        });



        $transactions = Transaction::where('id_user', Auth::user()->role == 'Admin' ? $firstSales->id : Auth::user()->id)
            ->whereBetween('created_at', $this->week);

        $data = [
            'items' => $items->get(),
            'type' => $type,
            'sales' => $sales,
            'firstSales' => $firstSales,
            'transactions' => $transactions->get()
        ];

        return view('sales.transaction.history_sale', $data);
    }

    public function return(Request $request)
    {
        if (isset($request->notif) && $request->notif != null) {
            Notification::where('id', $request->notif)->update([
                'is_read' => true
            ]);
        }
        $type = $request->warehouse ?? 'Request';
        $sales = User::where('role', 'Sales')->get();
        if (isset($request->sales) && $request->sales != null) {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            $firstSales = $sales->first();
        }
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $items = Item::withCount([
            'ItemAssign as assign_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
            },
            'ItemRequest as store_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->whereNotNull('approved_at');
                $query->where('status', 1);
            },
            'Transactions as sold_stocks' => function ($query) use ($firstSales) {
                $query->select(DB::raw('SUM(items_sold)'));
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
                $query->where('is_return', false);
            },
        ])->where(function ($query) use ($firstSales) {
            $query->whereHas('ItemAssign', function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
            });
            $query->orWhereHas('ItemRequest', function ($query) use ($firstSales) {
                if (Auth::user()->role != 'Admin') {
                    $query->where('id_user', Auth::user()->id);
                } else {
                    $query->where('id_user', $firstSales->id);
                }
            });
        })->whereHas('Transactions', function ($query) use ($firstSales) {
            if (Auth::user()->role != 'Admin') {
                $query->where('id_user', Auth::user()->id);
            } else {
                $query->where('id_user', $firstSales->id);
            }
            $query->where('is_return_user', true);
            $query->where('is_return', false);
        });


        $transactions = Transaction::where('id_user', Auth::user()->role == 'Admin' ? $firstSales->id : Auth::user()->id)
            ->orderBy('id', 'desc')
            ->select('id_item', 'shop_name', DB::raw('SUM(items_sold) as items_sold'))
            ->groupBy('id_item', 'shop_name')
            ->where('is_return_user', false);

        if (Auth::user()->role == 'Admin') {
            $transactionsHistory = Transaction::where('id_user', $firstSales->id)
                ->where('is_return_user', true)
                ->orderBy('id', 'desc')
                ->whereBetween('date_return', $this->week);
        } else {
            $transactionsHistory = Transaction::where('id_user', Auth::user()->id)
                ->where('is_return_user', true)
                ->orderBy('id', 'desc')
                ->whereBetween('date_return', $this->week);
        }
        $itemData = [];

        foreach ($transactionsHistory->get() as $row) {
            $date = date('Y-m-d', strtotime($row->date_return));
            if (!isset($itemData[$date])) {
                $itemData[$date] = [];
            }

            if (!isset($itemData[$date]['all_stock'])) {
                $itemData[$date]['all_stock'] = 0;
                $itemData[$date]['total'] = 0;
            }
            $itemData[$date]['all_stock'] += 1;
            $itemData[$date]['total'] += $row->items_sold * $row->Item->sale_price;
            $itemData[$date]['date'] = $row->date_return;
            $itemData[$date]['is_return_user'] = $row->is_return_user;
            $itemData[$date]['is_return'] = $row->is_return;
        }
        // dd($items->get());
        $data = [
            'items' => $items->get(),
            'transactions' => $transactions->get(),
            'type' => $type,
            'sales' => $sales,
            'firstSales' => $firstSales,
            'transactionsHistory' => $itemData,
        ];
        if (Auth::user()->role == 'Sales') {
            return view('sales.return.sales', $data);
        }
        return view('sales.return.index', $data);
    }

    public function returnHistory(Request $request)
    {
        $isDetail = $request->detailDate != null;
        if ($request->date != null) {
            $date = explode(' to ', $request->date);
            $this->week = $date;
        }
        $sales = User::where('role', 'Sales')->get();
        if (isset($request->sales) && $request->sales != null) {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            $firstSales = $sales->first();
        }

        if (Auth::user()->role == 'Admin') {
            $transactions = Transaction::where('id_user', $firstSales->id)
                ->where('is_return_user', true)
                ->orderBy('id', 'desc')
                ->whereBetween('date_return', $this->week);
        } else {
            $transactions = Transaction::where('id_user', Auth::user()->id)
                ->where('is_return_user', true)
                ->orderBy('id', 'desc')
                ->whereBetween('date_return', $this->week);
        }
        $itemData = [];

        if (!$isDetail) {
            foreach ($transactions->get() as $row) {
                $date = date('Y-m-d', strtotime($row->date_return));
                if (!isset($itemData[$date])) {
                    $itemData[$date] = [];
                }

                if (!isset($itemData[$date]['all_stock'])) {
                    $itemData[$date]['all_stock'] = 0;
                    $itemData[$date]['total'] = 0;
                }
                $itemData[$date]['all_stock'] += $row->items_sold;
                $itemData[$date]['total'] += $row->items_sold * $row->Item->sale_price;
                $itemData[$date]['date'] = $row->date_return;
            }
        } else {
            $itemData = $transactions
                ->orWhereDate('date_return', $request->detailDate)
                ->where('id_user', Auth::user()->id)
                ->where('is_return_user', true)
                ->orderBy('id', 'desc')
                ->select('id_item', DB::raw('SUM(items_sold) as items_sold'))
                ->groupBy('id_item')
                ->get();
        }

        $data = [
            'transactions' => $itemData,
            'sales' => $sales,
            'firstSales' => $firstSales,
        ];

        if ($isDetail) {
            return view('sales.return.history_detail', $data);
        }
        return view('sales.return.history', $data);
    }

    public function returnStore(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request->id == null) {
                self::failed("No transactions are returned");
                return redirect()->back();
            }
            $allId = explode(',', $request->id);
            if (Auth::user()->role == 'Sales') {

                $today = Transaction::whereDate('date_return', date('Y-m-d'))
                    ->where('id_user', Auth::user()->id)->first();

                if ($today) {
                    self::failed("Only 1 transaction can return today!");

                    return redirect()->back();
                }

                Notification::create([
                    'title' => 'Return Transaction',
                    'text' => "You have transaction return to review from sales - " . Auth::user()->name,
                    'id_user' => 0,
                ]);
            }
            foreach ($allId as $id) {
                if ($id == 'all') continue;
                if (Auth::user()->role == 'Sales') {

                    $trx = Transaction::where('id_item', $id)
                        ->where('id_user', Auth::user()->id)
                        ->where('is_return_user', false)->first();
                } else {
                    $trx = Transaction::find($id);
                }
                if ($trx) {
                    if (Auth::user()->role == 'Admin') {
                        $trx->is_return = true;
                        if ($request->all_return == "true") {
                            $trx->is_entrust_return = true;
                        }
                    } else {
                        $trx->is_return_user = true;
                    }
                    $trx->date_return = Carbon::now();
                    $trx->save();
                }
            }
            if ($request->all_return == "true" && Auth::user()->role == 'Admin') {
                for ($i = 0; $i < $request->stock_left; $i++) {
                    $itemRequest = ItemRequest::where('id_item', $trx->id_item)
                        ->whereNotNull('approved_at')
                        ->where('id_user', $trx->id_user)->first();
                    $itemAssign = ItemAssign::where('id_item', $trx->id_item)
                        ->whereNotNull('approved_at')
                        ->where('id_user', $trx->id_user)->first();

                    if ($itemRequest != null) {
                        $itemRequest->delete();
                    } else {
                        if ($itemAssign != null) {
                            $itemAssign->delete();
                        }
                    }
                }
            }

            DB::commit();

            self::success("Transaction Return successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to Transaction Return! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function returnStoreAll(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request->id == null) {
                self::failed("No transactions are returned");
                return redirect()->back();
            }
            foreach ($request->id ?? [] as $index => $row) {
                $allId = explode(',', $row);
                foreach ($allId ?? [] as $id) {
                    $id = str_replace('[', '', $id);
                    $id = str_replace(']', '', $id);
                    $trx = Transaction::find($id);
                    $trx->is_return = true;
                    if ($request->all_return == "true") {
                        $trx->is_entrust_return = true;
                    }
                    $trx->date_return = Carbon::now();
                    $trx->save();
                }
                if ($request->all_return == "true") {
                    for ($i = 0; $i < $request->stock_left[$index]; $i++) {
                        $itemRequest = ItemRequest::where('id_item', $trx->id_item)
                            ->whereNotNull('approved_at')
                            ->where('id_user', $trx->id_user)->first();
                        $itemAssign = ItemAssign::where('id_item', $trx->id_item)
                            ->whereNotNull('approved_at')
                            ->where('id_user', $trx->id_user)->first();

                        if ($itemRequest != null) {
                            $itemRequest->delete();
                        } else {
                            if ($itemAssign != null) {
                                $itemAssign->delete();
                            }
                        }
                    }
                }
            }

            DB::commit();

            self::success("Transaction Return successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to Transaction Return! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $text = ($request->id == null) ? 'create' : 'update';
        DB::beginTransaction();
        $stock = $request->stock;
        try {
            $stockLeft = [];
            foreach ($request->item as $index => $row) {
                $row = explode('|', $row);
                if (!isset($stockLeft[$row[0]])) {
                    $stockLeft[$row[0]] = $row[1];
                }

                $trx = Transaction::find($request->id);
                if (!$trx) {
                    $trx = new Transaction;
                }

                if ($stock[$index] > $stockLeft[$row[0]]) {

                    self::failed("Failed to $text Transaction! not enough stocks ");
                    return redirect()->back();
                }

                $stockLeft[$row[0]] -= $stock[$index];

                $trx->id_user = Auth::user()->id;
                $trx->id_item = $row[0];
                $trx->shop_name = $request->shop_name;
                $trx->items_sold = $stock[$index];
                $trx->save();
            }

            DB::commit();

            self::success("Transaction {$text}d successfully!");

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();

            self::failed("Failed to $text Transaction! " . $e->getMessage());

            return redirect()->back();
        }
    }

    public function salesTracking(Request $request)
    {
        $sales = User::where('role', 'Sales')->get();
        if (isset($request->sales) && $request->sales != null) {
            $firstSales = $sales->where('id', $request->sales)->first();
        } else {
            $firstSales = $sales->first();
        }
        $tracking = SalesTracking::where('id_user', $firstSales->id)->orderBy('id', 'desc');
        if (isset($request->track) && $request->track != null) {
            $firstTrack = $tracking->where('id', $request->track)->first();
        } else {
            $firstTrack = $tracking->first();
        }
        $tracks = SalesTracking::where('id_user', $firstSales->id)->orderBy('id', 'desc')->distinct()->get();
        $data = [
            'sales' => $sales,
            'tracking' => $tracks,
            'firstTrack' => $firstTrack,
            'firstSales' => $firstSales,
        ];
        return view('sales.tracking.index', $data);
    }

    public function salesTrackingStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $track = new SalesTracking();
            $check = SalesTracking::where('id_user', Auth::user()->id)->orderBy('id', 'desc')->first();
            if ($check?->latitude == $request->lat && $check?->longitude == $request->long) {
                return false;
            }

            if (Auth::user()->role != 'Sales') {
                return false;
            };

            $track->id_user = Auth::user()->id;
            $track->latitude = $request->lat;
            $track->longitude = $request->long;
            $track->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
