<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class xx_nfh_yakeen_data_oracel extends Model
{
    use HasFactory;
    protected $connection = 'oracle';
    protected $table = 'jobs_yakeen_queue';
    public $timestamps = false;
}
