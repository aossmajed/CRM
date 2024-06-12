<?php
namespace App\Services;
use App\Jobs\TransferYakeenDataFromMYSQLtoORACEL;
use DateTime;
use Carbon\Carbon;
use App\Models\{xx_nfh_yakeen_data,xx_nfh_yakeen_data_oracel};
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;
class TransferYakeenDataServices{

    public static function TransferYakeenDataFromMySqlToOracel_ (){
        TransferYakeenDataFromMYSQLtoORACEL::dispatch()->delay(now()->addMinutes(1))->onConnection('database2');
    }
    public static function TransferYakeenDataFromMySqlToOracel2 (){
        TransferYakeenDataFromMYSQLtoORACEL::dispatch()->delay(now()->addMinutes(1))->onConnection('database2');
        self::handle_data_and_insert_to_oracle_yakeen();
    }
    public static function handle_data_and_insert_to_oracle_yakeen(){
        $i =1 ;
        $count=xx_nfh_yakeen_data_oracel::count();
        if ($count ==0){
            $max=0;
        }else{
            $max=xx_nfh_yakeen_data_oracel::max('REC_ID');
        }
        xx_nfh_yakeen_data::whereNotNull('rec_id')
            ->where('rec_id', '>',$max)
            ->orderBy('rec_id','asc')
            ->chunk(100, function ($leads_vu_kastle_data)  use (&$i){
                foreach($leads_vu_kastle_data as $data){
                    $second=xx_nfh_yakeen_data_oracel::where('REC_ID',$data->lead_id)->first();
                    if($second==null){
                        $input=self::handle_input_before_insert_to_oracle($data);
                        self::insert_to_oracle($input);
                        printf("DATA INSERTED ,WHERE lead_id = ".$data->rec_id. ' index : '. $i."\n");
                    }else{
                        printf("DATA IS FOUND ,WHERE lead_id = ".$data->rec_id. ' index : '. $i."\n");
                    }
                }
        });
    }
    public static function insert_to_oracle($input){
        try{
            xx_nfh_yakeen_data_oracel::insert($input);
        }catch (Throwable $e) {
            printf($e->getMessage()."\n");
            printf("ERROR WHEN INSERT rec_id= ".$input['rec_id']."\n");
        }
    }
    public static function handle_input_before_insert_to_oracle($data){
        try{
            $creation_date = Carbon::parse($data->creation_date);
            $input=[
                'REC_ID'=> $data->rec_id,
                'YAKEEN_ID'=> $data->yakeen_id,
                'YAKEEN_DOB'=> $data->yakeen_dob,
                'TITLE'=> $data->Title,
                'ADDRESS1'=> $data->Address1,
                'ADDRESS2'=> $data->Address2,
                'OBJLATLNG'=> $data->ObjLatLng,
                'BUILDINGNUMBER'=> $data->BuildingNumber,
                'STREET'=> $data->Street,
                'DISTRICT'=> $data->District,
                'CITY'=> $data->City,
                'POSTCODE'=> $data->PostCode,
                'ADDITIONALNUMBER'=> $data->AdditionalNumber,
                'REGIONNAME'=> $data->RegionName,
                'POLYGONSTRING'=> $data->PolygonString,
                'ISPRIMARYADDRESS'=> $data->IsPrimaryAddress,
                'UNITNUMBER'=> $data->UnitNumber,
                'LATITUDE'=> $data->Latitude,
                'LONGITUDE'=> $data->Longitude,
                'CITYID'=> $data->CityID,
                'REGIONID'=> $data->RegionID,
                'RESTRICTION'=> $data->Restriction,
                'PKADDRESSID'=> $data->PKAddressID,
                'DISTRICTID'=> $data->DistrictID,
                'TITLE_L2'=> $data->Title_L2,
                'REGIONNAME_L2'=> $data->RegionName_L2,
                'CITY_L2'=> $data->City_L2,
                'STREET_L2'=> $data->Street_L2,
                'DISTRICT_L2'=> $data->District_L2,
                'GOVERNORATEID'=> $data->GovernorateID,
                'GOVERNORATE'=> $data->Governorate,
                'GOVERNORATE_L2'=> $data->Governorate_L2,
                'POSTCODE2'=> $data->PostCode2,
                'IDVERSION'=> $data->idVersion,
                'IDEXPIRATIONDATE'=> $data->idExpirationDate,
                'IDISSUEDATE'=> $data->idIssueDate,
                'IDISSUEPLACECODE'=> $data->idIssuePlaceCode,
                'IDISSUEPLACEDESCAR'=> $data->idIssuePlaceDescAR,
                'IDISSUEPLACEDESCEN'=> $data->idIssuePlaceDescEN,
                'BIRTHCITY'=> $data->birthCity,
                'BIRTHCOUNTRYCODE'=> $data->birthCountryCode,
                'BIRTHCOUNTRYDESCAR'=> $data->birthCountryDescAr,
                'BIRTHCOUNTRYDESCEN'=> $data->birthCountryDescEn,
                'BIRTHDATEG'=> $data->birthDateG,
                'MARITALSTATUSCODE'=> $data->maritalStatusCode,
                'MARITALSTATUSDESCAR'=> $data->maritalStatusDescAr,
                'MARITALSTATUSDESCEN'=> $data->maritalStatusDescEn,
                'NATIONALITYCODE'=> $data->nationalityCode,
                'NATIONALITYDESCAR'=> $data->nationalityDescAr,
                'NATIONALITYDESCEN'=> $data->nationalityDescEn,
                'NATIONALITYMOFACODE'=> $data->nationalityMOFACode,
                'SEXCODE'=> $data->sexCode,
                'SEXDESCAR'=> $data->sexDescAr,
                'SEXDESCEN'=> $data->sexDescEn,
                'FULLNAME'=> $data->fullName,
                'FULLNAMET'=> $data->fullNameT,
                'PERSONALIENSPONSORINFO'=> $data->personAlienSponsorInfo,
                'SERVICE_DATA'=> $data->service_data,
                'CREATED_BY'=> $data->created_by,
                'CREATION_DATE'=> $creation_date,
                'CRE_DATE'=>now()
            ];
        return $input;
    }catch (Throwable $e) {
        printf($e->getMessage()."\n");
        printf("ERROR WHEN HANDLED INPUTS rec_id= ".$data->rec_id."\n");
        return array();
    }
    }
}