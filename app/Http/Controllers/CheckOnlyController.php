<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{leads_vu_kastle,leads_vu_kastle_oracel};
class CheckOnlyController extends Controller
{
    public function check_conn_data(){

        $DATA= leads_vu_kastle::first();
        $DATA2 =leads_vu_kastle_oracel::first();
        $all =[
            'sql'=>$DATA,
            'oracel'=>$DATA2
        ];
        return $all;


    }
}
