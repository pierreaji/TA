<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $week;

    public function __construct()
    {
        $this->week = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
    }

    public function success($message)
    {
        Session::flash('alert', 'success');
        Session::flash('title', 'Success!');
        Session::flash('message', $message);
    }

    public function failed($message)
    {
        Session::flash('alert', 'error');
        Session::flash('title', 'Failed!');
        Session::flash('message', $message);
    }
}
