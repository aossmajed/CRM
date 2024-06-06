<?php
namespace App\Services;
use App\Jobs\TransferDataFromMYSQLtoORACEL;
class TransferDataServices{

    public static function TransferDataFromMySqlToOracel (){


        printf("aoss\n");
        TransferDataFromMYSQLtoORACEL::dispatch()->delay(now()->addMinutes(1));
    }

    public static function TransferDataFromMySqlToOracel2 (){


        printf("zada\n");
        TransferDataFromMYSQLtoORACEL::dispatch()->delay(now()->addMinutes(1));
    }

}