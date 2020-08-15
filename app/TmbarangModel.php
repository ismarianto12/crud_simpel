<?php

namespace App;

use App\Login;

use Illuminate\Database\Eloquent\Model;

class TmbarangModel extends Model
{
    //
    protected $table   = 'tmbarang';
    protected $guarded = [];

    function Login()
    {
        $this->belongsTo(Login::class);
    }
}
