<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (date('l') == 'Saturday') {
            $notif = Notification::where([
                'title' => 'Please assign a item',
                'text' => "You need assign a new item",
                'id_user' => 0
            ])->whereDate('created_at', date('Y-m-d'))->first();
            if (!$notif) {

                Notification::create([
                    'title' => 'Please assign a item',
                    'text' => "You need assign a new item",
                    'id_user' => 0,
                ]);
            }
        }
        if (Auth::user()?->Sales?->must_change_password) {

            $request->session()->flash('alert', 'warning');
            $request->session()->flash('title', 'Warning!');
            $request->session()->flash('message', 'Please change password please!');
            $request->session()->put('sales', true);
            return redirect()->to(url('profile'));
        } else {
            $request->session()->remove('sales');
        }
        $date = $request->date ?? date('Y-m');
        $this->week = explode('-', $date);

        $items = Item::withCount([
            'ItemAssign as assign_stocks' => function ($query) use ($date) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role == 'Sales') {
                    $query->where('id_user', Auth::user()->id);
                }
                $query->whereNotNull('approved_at');
                $query->whereMonth('approved_at', $this->week[1]);
                $query->whereYear('approved_at', $this->week[0]);
            },
            'ItemRequest as store_stocks' => function ($query) use ($date) {
                $query->select(DB::raw('SUM(stock)'));
                if (Auth::user()->role == 'Sales') {
                    $query->where('id_user', Auth::user()->id);
                }
                $query->whereNotNull('approved_at');
                $query->whereMonth('approved_at', $this->week[1]);
                $query->whereYear('approved_at', $this->week[0]);
            },
            'Transactions as sold_stocks' => function ($query) use ($date) {
                $query->select(DB::raw('SUM(items_sold)'));
                if (Auth::user()->role == 'Sales') {
                    $query->where('id_user', Auth::user()->id);
                }
                $query->whereMonth('created_at', $this->week[1]);
                $query->whereYear('created_at', $this->week[0]);
            },
        ])->where(function ($query) use ($date) {
            $query->whereHas('ItemAssign', function ($query) use ($date) {
                if (Auth::user()->role == 'Sales') {
                    $query->where('id_user', Auth::user()->id);
                }
                $query->whereNotNull('approved_at');
                $query->whereMonth('approved_at', $this->week[1]);
                $query->whereYear('approved_at', $this->week[0]);
            });
            $query->orWhereHas('ItemRequest', function ($query) use ($date) {
                if (Auth::user()->role == 'Sales') {
                    $query->where('id_user', Auth::user()->id);
                }
                $query->whereNotNull('approved_at');
                $query->whereMonth('approved_at', $this->week[1]);
                $query->whereYear('approved_at', $this->week[0]);
            });
        });

        $transactions = Transaction::select(DB::raw('sum(items_sold) as items_sold'), DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day'))
            ->whereMonth('created_at', $this->week[1])
            ->whereYear('created_at', $this->week[0])
            ->groupBy('year', 'month', 'day');

        if (Auth::user()->role == 'Sales') {
            $transactions = $transactions->where('id_user', Auth::user()->id);
        }

        $data = [
            'items' => $items->get(),
            'date' => $date,
            'transactions' => $transactions->get(),
            'week' => $this->week
        ];

        if (Auth::user()->role == 'Admin') {

            return view('welcome_admin', $data);
        }
        return view('welcome', $data);
    }

    public function queue() {
        for ($i = 0; $i <= 10; $i++) {
            dispatch(new \App\Jobs\ExampleJob);
            // dispatch(new ProcessOnboardingPegawai([
            //     'queue' => $i
            // ]));
        }
    }
}
