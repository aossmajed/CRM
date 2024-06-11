<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class xx_nfh_yakeen_data extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $table = 'xx_nfh_yakeen_data';
    public $timestamps = false;
    protected $fillable = [
        'rec_id',
        'yakeen_id',
        'yakeen_dob',
        'Title',
        'Address1',
        'Address2',
        'ObjLatLng',
        'BuildingNumber',
        'Street',
        'District',
        'City',
        'PostCode',
        'AdditionalNumber',
        'RegionName',
        'PolygonString',
        'IsPrimaryAddress',
        'UnitNumber',
        'Latitude',
        'Longitude',
        'CityID',
        'RegionID',
        'Restriction',
        'PKAddressID',
        'DistrictID',
        'Title_L2',
        'RegionName_L2',
        'City_L2',
        'Street_L2',
        'District_L2',
        'GovernorateID',
        'Governorate',
        'Governorate_L2',
        'PostCode2',
        'idVersion',
        'idExpirationDate',
        'idIssueDate',
        'idIssuePlaceCode',
        'idIssuePlaceDescAR',
        'idIssuePlaceDescEN',
        'birthCity',
        'birthCountryCode',
        'birthCountryDescAr',
        'birthCountryDescEn',
        'birthDateG',
        'maritalStatusCode',
        'maritalStatusDescAr',
        'maritalStatusDescEn',
        'nationalityCode',
        'nationalityDescAr',
        'nationalityDescEn',
        'nationalityMOFACode',
        'sexCode',
        'sexDescAr',
        'sexDescEn',
        'fullName',
        'fullNameT',
        'personAlienSponsorInfo',
        'service_data',
        'created_by',
        'creation_date'
    ];
}