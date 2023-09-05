<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SalesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = Auth::user();
        if ($auth->role == 'Sales') {
            if ($auth->Sales->must_change_password) {

                Session::flash('alert', 'warning');
                Session::flash('title', 'Warning!');
                Session::flash('message', 'Please change password please!');
                Session::put('sales', true);
                return redirect()->to(url('profile'));
            }
            $checkMandatoryFiles = $auth->Sales->toArray();
            $checkMandatoryFiles['reason'] = $checkMandatoryFiles['reason'] ?? 'test';
            $checkMandatoryFiles['is_renew'] = $checkMandatoryFiles['is_renew'] == 0 ? true : false;
            $checkMandatoryFiles['approved_status'] = $checkMandatoryFiles['approved_status'] == 0 ? 'menunggu' : $checkMandatoryFiles['approved_status'];

            $files = [
                'skck' => $checkMandatoryFiles['skck'],
                'ktp' => $checkMandatoryFiles['ktp'],
                'sim' => $checkMandatoryFiles['sim'],
                'stnk' => $checkMandatoryFiles['stnk'],
                'pas_foto' => $checkMandatoryFiles['pas_foto'],
                'sertifikat' => $checkMandatoryFiles['sertifikat'],
                'agreement' => $checkMandatoryFiles['agreement'],
            ];

            if (in_array(null, $files)) {
                Session::flash('alert', 'error');
                Session::flash('title', 'Failed!');
                Session::flash('message', 'You must fill in all the required fields below!');
                Session::put('sales', true);
                return redirect()->to(url(''));
                // return to_route('users.sales', ['id' => $auth->id]);
            }

            if ($checkMandatoryFiles['approved_status'] == 'menunggu') {
                Session::flash('alert', 'info');
                Session::flash('title', 'Document Saved!');
                Session::flash('message', 'Your document is under review!');
                Session::put('sales', true);
                return redirect()->to(url(''));
                // return to_route('users.sales', ['id' => $auth->id]);
            }
            if ($checkMandatoryFiles['approved_status'] == 2) {
                Session::flash('alert', 'info');
                Session::flash('title', 'Failed!');
                Session::flash('message', 'Your document is rejected!');
                Session::put('sales', true);
                return redirect()->to(url(''));
                // return to_route('users.sales', ['id' => $auth->id]);
            }
            if (!$checkMandatoryFiles['is_renew']) {
                Session::flash('alert', 'info');
                Session::flash('title', 'Failed!');
                Session::flash('message', 'Please Renew your Documents!');
                Session::put('sales', true);
                return redirect()->to(url(''));
                // return to_route('users.sales', ['id' => $auth->id]);
            }
            Session::remove('sales');
        }

        return $next($request);
    }
}
