<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Properti
{

    public static function prop($kolom)
    {
        $data = DB::table('login')->where('id', Auth::user()->id)->first();
        return $data->$kolom;
    }
}
