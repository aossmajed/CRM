<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{leads_vu_kastle,leads_vu_kastle_oracel,xx_nfh_yakeen_data,xx_nfh_yakeen_data_oracel};
class CheckOnlyController extends Controller
{
    public function check_conn_data(){

        $DATA= leads_vu_kastle::first();
        // $DATA2 =leads_vu_kastle_oracel::first();
        $DATA3 =xx_nfh_yakeen_data::first();
        $DATA4 =xx_nfh_yakeen_data_oracel::first();
        $all =[
            'sql_LEAD'=>$DATA,
            // 'oracel_LEAD'=>$DATA2,
            'sql_YAKEEN'=>$DATA3,
            'oracel_YAKEEN'=>$DATA4,
        ];
        return $all;


    }
}
