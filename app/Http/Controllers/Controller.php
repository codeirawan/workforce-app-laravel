<?php

namespace App\Http\Controllers;

use DB, Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validationError($message)
    {
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        return redirect()->back()->withInput(request()->except('_token'))->withErrors($message);
    }

    public function simpleEncrypt($string)
    {
        return substr_replace(Str::random(10), $string, 5, 0);
    }

    public function simpleDecrypt($string)
    {
        return substr($string, 5, -5);
    }
}
