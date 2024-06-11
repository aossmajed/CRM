<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class xx_nfh_yakeen_data_oracel extends Model
{
    use HasFactory;
    protected $connection = 'oracle';
    protected $table = 'SIR_XX_NFH_YAKEEN_DATA';
    public $timestamps = false;
    protected $primaryKey = 'REC_ID';
    protected $fillable = [
            'REC_ID',
            'YAKEEN_ID',
            'YAKEEN_DOB',
            'TITLE',
            'ADDRESS1',
            'ADDRESS2',
            'OBJLATLNG',
            'BUILDINGNUMBER',
            'STREET',
            'DISTRICT',
            'CITY',
            'POSTCODE',
            'ADDITIONALNUMBER',
            'REGIONNAME',
            'POLYGONSTRING',
            'ISPRIMARYADDRESS',
            'UNITNUMBER',
            'LATITUDE',
            'LONGITUDE',
            'CITYID',
            'REGIONID',
            'RESTRICTION',
            'PKADDRESSID',
            'DISTRICTID',
            'TITLE_L2',
            'REGIONNAME_L2',
            'CITY_L2',
            'STREET_L2',
            'DISTRICT_L2',
            'GOVERNORATEID',
            'GOVERNORATE',
            'GOVERNORATE_L2',
            'POSTCODE2',
            'IDVERSION',
            'IDEXPIRATIONDATE',
            'IDISSUEDATE',
            'IDISSUEPLACECODE',
            'IDISSUEPLACEDESCAR',
            'IDISSUEPLACEDESCEN',
            'BIRTHCITY',
            'BIRTHCOUNTRYCODE',
            'BIRTHCOUNTRYDESCAR',
            'BIRTHCOUNTRYDESCEN',
            'BIRTHDATEG',
            'MARITALSTATUSCODE',
            'MARITALSTATUSDESCAR',
            'MARITALSTATUSDESCEN',
            'NATIONALITYCODE',
            'NATIONALITYDESCAR',
            'NATIONALITYDESCEN',
            'NATIONALITYMOFACODE',
            'SEXCODE',
            'SEXDESCAR',
            'SEXDESCEN',
            'FULLNAME',
            'FULLNAMET',
            'PERSONALIENSPONSORINFO',
            'SERVICE_DATA',
            'CREATED_BY',
            'CREATION_DATE',
            'CRE_DATE'

    ];
}
