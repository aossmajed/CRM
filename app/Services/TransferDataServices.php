<?php
namespace App\Services;
use App\Jobs\TransferDataFromMYSQLtoORACEL;
use DateTime;
use Carbon\Carbon;
use App\Models\{leads_vu_kastle,leads_vu_kastle_oracel,SIR_LEADS2_VU_KASTLE};
use Illuminate\Support\Facades\DB;
use Exception;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use Throwable;
class TransferDataServices{

    public static function TransferDataFromMySqlToOracel (){
        // self::handle_data_and_update_to_oracle_2();
        TransferDataFromMYSQLtoORACEL::dispatch()->delay(now()->addMinutes(1))->onConnection('database');
    }
    public static function TransferDataFromMySqlToOracel2 (){
        TransferDataFromMYSQLtoORACEL::dispatch()->delay(now()->addMinutes(1))->onConnection('database');
        self:: handle_data_and_insert_to_oracle_2();
        // self:: handle_data_and_insert_to_oracle();
    }

    public static function handle_data_and_insert_to_oracle_2(){
        $i =1 ;
        $count=SIR_LEADS2_VU_KASTLE::count();
        if ($count ==0){
            $max=0;
        }else{
            $max=SIR_LEADS2_VU_KASTLE::max('LEAD_ID');
        }
        leads_vu_kastle::whereNotNull('lead_id')
            ->whereNotNull('acc_id')
            ->where('lead_id', '>',$max)
            ->orderBy('lead_id','asc')
            ->chunk(3000, function ($leads_vu_kastle_data)  use (&$i){
                foreach($leads_vu_kastle_data as $data){
                    $second=SIR_LEADS2_VU_KASTLE::where('lead_id',$data->lead_id)->first();
                    if($second==null){
                        $input=self::handle_input_before_insert_to_oracle2($data);
                        self::insert_to_oracle_and_run_proceduer2($input);
                        printf("DATA INSERTED ,WHERE lead_id = ".$data->lead_id."\n");
                    }else{
                        printf("DATA IS FOUND ,WHERE lead_id = ".$data->lead_id."\n");
                    }
                }
            });
        leads_vu_kastle::whereNotNull('lead_id')
            ->whereNotNull('acc_id')
            ->whereDate('assignment_status_date', date("Y-m-d"))
            ->orderBy('modified_date','desc')
            ->chunk(100, function ($leads_vu_kastle_data)  use (&$i){
                $ids = $leads_vu_kastle_data->pluck('lead_id')->toArray();
                $second2=SIR_LEADS2_VU_KASTLE::wherein('lead_id',$ids)->count();
                if(count($ids)!=$second2){
                    foreach($leads_vu_kastle_data as $data){
                        $second=SIR_LEADS2_VU_KASTLE::where('lead_id',$data->lead_id)->first();
                        if($second==null){
                            $input=self::handle_input_before_insert_to_oracle2($data);
                            self::insert_to_oracle_and_run_proceduer2($input);
                            printf("DATA INSERTED 2 ,WHERE lead_id = ".$data->lead_id."\n");
                        }else{
                            printf("DATA IS FOUND 2 ,WHERE lead_id = ".$data->lead_id."\n");
                        }
                    }
                }else{
                    printf("DATA IS FOUND 3\n");
                }
            });

    }
    public static function handle_data_and_insert_to_oracle(){
        $i =1 ;
        $count=leads_vu_kastle_oracel::count();
        if ($count ==0){
            $max=0;
        }else{
            $max=leads_vu_kastle_oracel::max('LEAD_ID');
        }
        leads_vu_kastle::whereNotNull('lead_id')
            ->whereNotNull('acc_id')
            ->where('lead_id', '>',$max)
            ->orderBy('lead_id','asc')
            ->chunk(2000, function ($leads_vu_kastle_data)  use (&$i){
                foreach($leads_vu_kastle_data as $data){
                    $second=leads_vu_kastle_oracel::where('lead_id',$data->lead_id)->where('acc_id',$data->acc_id)->first();
                    if($second==null){
                        $input=self::handle_input_before_insert_to_oracle($data);
                        self::insert_to_oracle_and_run_proceduer($input);
                        printf("DATA INSERTED ,WHERE lead_id = ".$data->lead_id."\n");
                    }else{
                        printf("DATA IS FOUND ,WHERE lead_id = ".$data->lead_id."\n");
                    }
                }
            });
        leads_vu_kastle::whereNotNull('lead_id')
            ->whereNotNull('acc_id')
            ->whereDate('assignment_status_date', date("Y-m-d"))
            ->orderBy('modified_date','desc')
            ->chunk(100, function ($leads_vu_kastle_data)  use (&$i){
                $ids = $leads_vu_kastle_data->pluck('lead_id')->toArray();
                $second2=leads_vu_kastle_oracel::wherein('lead_id',$ids)->count();
                if(count($ids)!=$second2){
                    foreach($leads_vu_kastle_data as $data){
                        $second=leads_vu_kastle_oracel::where('lead_id',$data->lead_id)->where('acc_id',$data->acc_id)->first();
                        if($second==null){
                            $input=self::handle_input_before_insert_to_oracle($data);
                            self::insert_to_oracle_and_run_proceduer($input);
                            printf("DATA INSERTED 2 ,WHERE lead_id = ".$data->lead_id."\n");
                        }else{
                            printf("DATA IS FOUND 2 ,WHERE lead_id = ".$data->lead_id."\n");
                        }
                    }
                }else{
                    printf("DATA IS FOUND 3\n");
                }
            });
    }
    public static function insert_to_oracle_and_run_proceduer($input){
        try{
            leads_vu_kastle_oracel::insert($input);
            // DB::connection('oracle')->statement("BEGIN proceduer_name(:proceduer_value); END;", ['proceduer_value' =>  $input['lead_id']]);
        }catch (Throwable $e) {
            printf($e->getMessage()."\n");
            printf("ERROR WHEN INSERT lead_id= ".$input['LEAD_ID']."\n");
        }
    }
    public static function insert_to_oracle_and_run_proceduer2($input){
        try{
            SIR_LEADS2_VU_KASTLE::insert($input);
            DB::connection('oracle')->statement("BEGIN SIR_PKG_WEB_APPLICATION.SIR_POPULATE_DATA(:LEAD_ID); END;", ['LEAD_ID' =>  $input['LEAD_ID']]);
        }catch (Throwable $e) {
            printf($e->getMessage()."\n");
            printf("ERROR WHEN INSERT lead_id= ".$input['LEAD_ID']."\n");
        }
    }
    public static function handle_input_before_insert_to_oracle($data){
        try{
            $creation_date = Carbon::parse($data->creation_date);
            $assign_date=$data->assign_date;
            if ($assign_date !=null){
                $assign_date=Carbon::parse($assign_date);
            }
            $assignment_status_date=$data->assignment_status_date;
            if ($assignment_status_date !=null){
                $assignment_status_date=Carbon::parse($assignment_status_date);
            }
            $convert_br_date=$data->convert_br_date;
            if ($convert_br_date !=null){
                $convert_br_date=Carbon::parse($convert_br_date);
            }
            $convert_salesman_date=$data->convert_salesman_date;
            if ($convert_salesman_date !=null){
                $convert_salesman_date=Carbon::parse($convert_salesman_date);
            }
            $convert_status_date=$data->convert_status_date;
            if ($convert_status_date !=null){
                $convert_status_date=Carbon::parse($convert_status_date);
            }
            $application_creation_date=$data->application_creation_date;
            if ($application_creation_date !=null){
                $application_creation_date=Carbon::parse($application_creation_date);
            }
            $assignment_callback_date=$data->assignment_callback_date;
            if ($assignment_callback_date !=null and strval( $assignment_callback_date)!='0000-00-00'){
                $assignment_callback_date=Carbon::parse($assignment_callback_date);
            }else{
                $assignment_callback_date=null;
            }
            $rdob=$data->rdob;
            if ($rdob !=null){
                $rdob=Carbon::parse($rdob);
            }
            $modified_date=$data->modified_date;
            if ($modified_date !=null){
                $modified_date=Carbon::parse($modified_date);
            }
            $created_by=$data->created_by;
            if ($created_by ==null){
                $created_by=71;
            }
            $input=[
                'LEAD_ID'=>$data->lead_id,
                'ACC_ID'=>$data->acc_id,
                'KYC_ID'=>$data->kyc_id,
                'KASTLE_ID'=>$data->kastle_id,
                'FNAME'=>$data->fname,
                'PHONE1'=>$data->phone1,
                'PHONE2'=>$data->phone2,
                'PHONE3'=>$data->phone3,
                'CRMNATID'=>$data->crmnatid,
                'PREF_LANG_ID'=>$data->pref_lang_id,
                'ACC_LANG'=>$data->acc_lang,
                'EMAIL'=>$data->email,
                'GENDER_ID'=>$data->gender_id,
                'ACC_GENDER'=>$data->acc_gender,
                'CRMDOB'=>$data->crmdob,
                'CITY_ID'=>$data->city_id,
                'ACC_CITY'=>$data->acc_city,
                'PROFESSION_ID'=>$data->profession_id,
                'ACC_PROFESSION'=>$data->acc_profession,
                'SALARYCAT_ID'=>$data->salaryCat_id,
                'ACC_SALARY_CAT'=>$data->acc_salary_cat,
                'SALARYTYPE_ID'=>$data->salaryType_id,
                'ACC_SALARY_TYPE'=>$data->acc_salary_type,
                'SALARY'=>$data->salary,
                'SALARYDEDUCTION'=>$data->salaryDeduction,
                'NATIONALITY'=>$data->nationality,
                'ACC_NATIONALITY'=>$data->acc_nationality,
                'SALARY_BANK_ID'=>$data->salary_bank_id,
                'ACC_BANK'=>$data->acc_bank,
                'ACC_SOURCE'=>$data->acc_source,
                'ACC_CHANNEL'=>$data->acc_channel,
                'ACC_CREATED_BY'=>$data->acc_created_by,
                'CAR_BRAND_ID'=>$data->car_brand_id,
                'CAR_BRAND'=>$data->car_brand,
                'CAR_MODEL_YEAR_ID'=>$data->car_model_year_id,
                'CAR_MODEL_YEARS'=>$data->car_model_years,
                'PRODUCT_TYPE_ID'=>$data->product_type_id,
                'PRODUCT_TYPE'=>$data->product_type,
                'PROSPECT_STATUS_ID'=>$data->prospect_status_id,
                'PROSPECT_STATUS'=>$data->prospect_status,
                'CRMFINANCE_AMOUNT'=>$data->crmfinance_amount,
                'SOURCE_ID'=>$data->source_id,
                'PROSPECT_SOURCE'=>$data->prospect_source,
                'CHANNEL_ID'=>$data->channel_id,
                'PROSPECT_CHANNEL'=>$data->prospect_channel,
                'PREFERRED_CALL_TIME_ID'=>$data->preferred_call_time_id,
                'PREF_CALL_TIME'=>$data->pref_call_time,
                'CREATED_BY'=>$created_by,
                'PROSPECT_CREATED_BY'=>$data->prospect_created_by,
                'CREATION_DATE'=>$creation_date,
                'ASSIGN_BY'=>$data->assign_by,
                'PROSPECT_ASSIGN_BY'=>$data->prospect_assign_by,
                'ASSIGNMENT_TO_ID'=>$data->assignment_to_id,
                'PROSPECT_ASSIGN_TO'=>$data->prospect_assign_to,
                'PROSPECT_ASSIGN_TO_EMAIL'=>$data->prospect_assign_to_email,
                'ASSIGN_DATE'=>$assign_date,
                'ASSIGNMENT_CALLBACK_DATE'=>$assignment_callback_date,
                'ASSIGNMENT_CALLBACK_TIME_ID'=>$data->assignment_callback_time_id,
                'PROSPECT_CALL_BACK_TIME'=>$data->prospect_call_back_time,
                'ASSIGNMENT_NOTE'=>$data->assignment_note,
                'ASSIGNMENT_STATUS_ID'=>$data->assignment_status_id,
                'ASSIGNMENT_STATUS'=>$data->assignment_status,
                'ASSIGNMENT_STATUS_DATE'=>$assignment_status_date,
                'CONVERT_BR'=>$data->convert_br,
                'CONVERT_BRANCH'=>$data->convert_branch,
                'CONVERT_BRANCH_AR'=>$data->convert_branch_ar,
                'BRANCH_URL'=>$data->branch_url,
                'CONVERT_BR_DATE'=>$convert_br_date,
                'CONVERT_SALESMAN_ID'=>$data->convert_salesman_id,
                'CONVERT_SALESMAN_NAME'=>$data->convert_salesman_name,
                'CONVERT_SALESMAN_NAME_AR'=>$data->convert_salesman_name_ar,
                'CONVERT_SALESMAN_EMAIL'=>$data->convert_salesman_email,
                'CONVERT_SALESMAN_MOBILE'=>$data->convert_salesman_mobile,
                'CONVERT_SALESMAN_BY_ID'=>$data->convert_salesman_by_id,
                'CONVERT_BY'=>$data->convert_by,
                'CONVERT_SALESMAN_DATE'=>$convert_salesman_date,
                'CONVERT_STAGE_ID'=>$data->convert_stage_id,
                'CONVERT_STATUS_ID'=>$data->convert_status_id,
                'CONVERT_STATUS'=>$data->convert_status,
                'CONVERT_STATUS_AR'=>$data->convert_status_ar,
                'CONVERT_NOTE'=>$data->convert_note,
                'CONVERT_STATUS_DATE'=>$data->convert_status_date,
                'MODIFIED_DATE'=>$modified_date,
                'LEAD_SCORE'=>$data->lead_score,
                'SERVICE_CODE'=>$data->service_code,
                'CRMUTM_SOURCE'=>$data->crmutm_source,
                'CRMUTM_CAMPAIGN'=>$data->crmutm_campaign,
                'APP_ID'=>$data->app_id,
                'FULL_NAME'=>$data->full_name,
                'MOBILE'=>$data->Mobile,
                'NATID'=>$data->natid,
                'NATID_EXPIRY_DATE'=>$data->natid_expiry_date,
                'EMAIL_ADDRESS'=>$data->email_address,
                'LEADCF69'=>$data->LEADCF69,
                'HOUSE_ALLOWANCE'=>$data->house_allowance,
                'OTHER_ALLOWANCE'=>$data->other_allowance,
                'SOCIAL_STATUS'=>$data->social_status,
                'SOCIAL_STATUS_V'=>$data->social_status_v,
                'SOCIAL_STATUS_KASTLE'=>$data->social_status_kastle,
                'DATE1'=>$data->date,
                'MONTH1'=>$data->month,
                'DOB'=>$data->DOB,
                'RDOB'=>$rdob,
                'EMPLOYER_NAME'=>$data->employer_name,
                'EMPLOYER_CITY'=>$data->employer_city,
                'EMPLOYER_CITY_V'=>$data->employer_city_v,
                'EMPLOYER_CITY_KASTLE'=>$data->employer_city_kastle,
                'EMPLOYER_ADDRESS'=>$data->employer_address,
                'OFFICE_PHONE'=>$data->office_phone,
                'EMPLOYER_TYPE'=>$data->employer_type,
                'EMPLOYER_TYPE_V'=>$data->employer_type_v,
                'EMPLOYER_TYPE_KASTLE'=>$data->employer_type_kastle,
                'EMPLOYMENT_TENNURE'=>$data->employment_tennure,
                'EMPLOYMENT_TENNURE_Y'=>$data->employment_tennure_y,
                'GROSS_SALARY'=>$data->gross_salary,
                'BANK_TYPE'=>$data->bank_type,
                'BANK_TYPE_V'=>$data->bank_type_v,
                'IBAN_NUMBER'=>$data->iban_number,
                'ISHAVEADDITIONALINCOME'=>$data->ishaveadditionalincome,
                'ISHAVEADDITIONALINCOME_V'=>$data->ishaveadditionalincome_v,
                'ADDITIONALINCOMESOURCE'=>$data->additionalincomesource,
                'ADDITIONALINCOMESOURCE_V'=>$data->additionalincomesource_v,
                'ADDITIONALINCOMEVALUE'=>$data->additionalincomevalue,
                'ISHAVECREDITCARD'=>$data->ishavecreditcard,
                'ISHAVECREDITCARD_V'=>$data->ishavecreditcard_v,
                'CREDITCARDVALUE'=>$data->creditcardvalue,
                'ISHAVEDEDUCTION'=>$data->ishavededuction,
                'ISHAVEDEDUCTION_V'=>$data->ishavededuction_v,
                'DEDUCTION_VALUE'=>$data->deduction_value,
                'ISHAVEMORTAGE'=>$data->ishavemortage,
                'ISHAVEMORTAGE_V'=>$data->ishavemortage_v,
                'MORTAGESUPPORTAMOUNT'=>$data->mortagesupportamount,
                'MONTHLYMORTAGEINSTALLMENT'=>$data->monthlymortageinstallment,
                'NOOFINSTALLMENT'=>$data->noofinstallment,
                'NOOFINSTALLMENT_V'=>$data->noofinstallment_v,
                'FINANCE_AMOUNT'=>$data->finance_amount,
                'DOCUMENT_TYPE'=>$data->document_type,
                'DOCUMENT_TYPE_V'=>$data->document_type_v,
                'JOB_TITLE'=>$data->job_title,
                'JOB_TITLE_V'=>$data->job_title_v,
                'PURPOSE_OF_LOAN_V'=>$data->purpose_of_loan_v,
                'TC'=>$data->tc,
                'SAMAH'=>$data->samah,
                'DOCBANKSTATEMENTUPLOAD_FILE'=>$data->docbankstatementupload_file,
                'DOCSALARYLETTERUPLOAD_FILE'=>$data->docsalaryletterupload_file,
                'DOCGOSIUPLOAD_FILE'=>$data->docgosiupload_file,
                'DOCNATIDUPLOAD_FILE'=>$data->docnatidupload_file,
                'VERIFICATION_CODE'=>$data->verification_code,
                'APPLICATION_CREATION_DATE'=>$application_creation_date,
                'HOUSE_EXPENSE'=>$data->house_expense,
                'HOUSING_TYPE'=>$data->housing_type,
                'HOUSING_TYPE_V'=>$data->housing_type_v,
                'HOUSING_OWNERSHIP'=>$data->housing_ownership,
                'HOUSING_OWNERSHIP_V'=>$data->housing_ownership_v,
                'HOUSING_OWNERSHIP_KASTLE'=>$data->housing_ownership_kastle,
                'MONTHLY_RENT'=>$data->monthly_rent,
                'NO_OF_DEPENDENT'=>$data->no_of_dependent,
                'NO_OF_DOMESTIC_WORKER'=>$data->no_of_domestic_worker,
                'DOMESTIC_WORKER_SALARY'=>$data->domestic_worker_salary,
                'INVOICES'=>$data->invoices,
                'INSURANCE_VALUE'=>$data->insurance_value,
                'NUMBER_OF_DEPENDENT_IN_PRIVATE_SCHOOL'=>$data->number_of_dependent_in_private_school,
                'NUMBER_OF_DEPENDENT_IN_GOV_SCHOOL'=>$data->number_of_dependent_in_gov_school,
                'MONTHLY_EDUCATION_EXPENSE'=>$data->monthly_education_expense,
                'TRANSPORT_EXPENSES'=>$data->transport_expenses,
                'HEALTH_SERVICE_EXPENSE'=>$data->health_service_expense,
                'CALLING_EXPENSE'=>$data->calling_expense,
                'FOOD_BEVERAGE_EXPENSE'=>$data->food_beverage_expense,
                'DEPENDENT_EXPENSE'=>$data->dependent_expense,
                'MONTHLY_AID_PARENTS'=>$data->monthly_aid_parents,
                'OTHER_MONTHLY_EXPENSE'=>$data->other_monthly_expense,
                'REFER_MOBILE'=>$data->refer_mobile,
                'REFER_NAME'=>$data->refer_name,
                'IS_RELATIVE_HP'=>$data->is_relative_hp,
                'IS_PREVIOUS_ACCOUNT'=>$data->is_previous_account,
                'IS_REAL_BENEFICIARY'=>$data->is_real_beneficiary,
                'EDUCATION_LEVEL'=>$data->education_level,
                'EDUCATION_LEVEL_V'=>$data->education_level_v,
                'EDUCATION_LEVEL_KASTLE'=>$data->education_level_kastle,
                'UTM_SOURCE'=>$data->utm_source,
                'UTM_CAMPAIGN'=>$data->utm_campaign,
                'KASTLE_USERID'=>$data->kastle_userid,
    ];
        return $input;
    }catch (Throwable $e) {
        printf($e->getMessage()."\n");
        printf("ERROR WHEN HANDLED INPUTS lead_id= ".$data->lead_id."\n");
        return array();
    }
    }

    public static function handle_input_before_insert_to_oracle2($data){
        try{
            $creation_date = Carbon::parse($data->creation_date);
            $assignment_status_date=$data->assignment_status_date;
            if ($assignment_status_date !=null){
                $assignment_status_date=Carbon::parse($assignment_status_date);
            }
            $application_creation_date=$data->application_creation_date;
            if ($application_creation_date !=null){
                $application_creation_date=Carbon::parse($application_creation_date);
            }
            $dob=$data->DOB;
            $rdob=$data->rdob;
            $DOB_NEW=$rdob;
            if ($rdob !=null){
                $rdob=Carbon::parse($rdob);
            }
            $modified_date=$data->modified_date;
            if ($modified_date !=null){
                $modified_date=Carbon::parse($modified_date);
            }
            $created_by=$data->created_by;
            if ($created_by ==null){
                $created_by=71;
            }
            $NATIONALITY = ($data->nationality == '' || $data->nationality == null) ? null : $data->nationality;
            $ACC_NATIONALITY = ($data->acc_nationality == '' || $data->acc_nationality == null) ? null : $data->acc_nationality;
            $GENDER_ID = ($data->gender_id == '' || $data->gender_id == null) ? null : $data->gender_id;
            $ACC_GENDER = ($data->acc_gender == '' || $data->acc_gender == null) ? null : $data->acc_gender;
            $input=[
                'LEAD_ID'=>$data->lead_id,
                'FNAME'=>$data->fname,
                'PHONE1'=>$data->phone1,
                'CRMNATID'=>$data->crmnatid,
                'CRMDOB'=>$data->crmdob,
                'CITY_ID'=>$data->city_id,
                'PROFESSION_ID'=>$data->profession_id,
                'ACC_PROFESSION'=>$data->acc_profession,
                'SALARYCAT_ID'=>$data->salarycat_id,
                'ACC_SALARY_CAT'=>$data->acc_salary_cat,
                'SALARYTYPE_ID'=>$data->salarytype_id,
                'ACC_SALARY_TYPE'=>$data->acc_salary_type,
                'SALARY'=>$data->salary,
                'SALARYDEDUCTION'=>$data->salarydeduction,
                'SALARY_BANK_ID'=>$data->salary_bank_id,
                'ACC_BANK'=>$data->acc_bank,
                'ACC_SOURCE'=>$data->acc_source,
                'ACC_CHANNEL'=>$data->acc_channel,
                'ACC_CREATED_BY'=>$data->acc_created_by,
                'PRODUCT_TYPE_ID'=>$data->product_type_id,
                'PRODUCT_TYPE'=>$data->product_type,
                'PROSPECT_STATUS_ID'=>$data->prospect_status_id,
                'PROSPECT_STATUS'=>$data->prospect_status,
                'SOURCE_ID'=>$data->source_id,
                'CHANNEL_ID'=>$data->channel_id,
                'PREF_CALL_TIME'=>$data->pref_call_time,
                'CREATED_BY'=>$created_by,
                'CREATION_DATE'=>$creation_date,
                'ASSIGN_BY'=>$data->assign_by,
                'ASSIGNMENT_TO_ID'=>$data->assignment_to_id,
                'ASSIGNMENT_NOTE'=>$data->assignment_note,
                'ASSIGNMENT_STATUS_DATE'=>$assignment_status_date,
                'MODIFIED_DATE'=>$modified_date,
                'APP_ID'=>$data->app_id,
                'FULL_NAME'=>$data->full_name,
                'MOBILE'=>$data->mobile,
                'NATID'=>$data->natid,
                'NATID_EXPIRY_DATE'=>$data->natid_expiry_date,
                'EMAIL_ADDRESS'=>$data->email_address,
                'LEADCF69'=>$data->leadcf69,
                'HOUSE_ALLOWANCE'=>$data->house_allowance,
                'OTHER_ALLOWANCE'=>$data->other_allowance,
                'SOCIAL_STATUS'=>$data->social_status,
                'SOCIAL_STATUS_V'=>$data->social_status_v,
                'SOCIAL_STATUS_KASTLE'=>$data->social_status_kastle,
                'DOB'=>$dob,
                'RDOB'=>$rdob,
                'EMPLOYER_NAME'=>$data->employer_name,
                'EMPLOYER_CITY'=>$data->employer_city,
                'EMPLOYER_CITY_V'=>$data->employer_city_v,
                'EMPLOYER_CITY_KASTLE'=>$data->employer_city_kastle,
                'EMPLOYER_ADDRESS'=>$data->employer_address,
                'OFFICE_PHONE'=>$data->office_phone,
                'EMPLOYER_TYPE'=>$data->employer_type,
                'EMPLOYER_TYPE_V'=>$data->employer_type_v,
                'EMPLOYER_TYPE_KASTLE'=>$data->employer_type_kastle,
                'EMPLOYMENT_TENNURE'=>$data->employment_tennure,
                'EMPLOYMENT_TENNURE_Y'=>$data->employment_tennure_y,
                'GROSS_SALARY'=>$data->gross_salary,
                'DEDUCTION_VALUE'=>$data->deduction_value,
                'APPLICATION_CREATION_DATE'=>$application_creation_date,
                'HOUSE_EXPENSE'=>$data->house_expense,
                'HOUSING_TYPE'=>$data->housing_type,
                'HOUSING_TYPE_V'=>$data->housing_type_v,
                'HOUSING_OWNERSHIP'=>$data->housing_ownership,
                'HOUSING_OWNERSHIP_V'=>$data->housing_ownership_v,
                'HOUSING_OWNERSHIP_KASTLE'=>$data->housing_ownership_kastle,
                'MONTHLY_RENT'=>$data->monthly_rent,
                'NO_OF_DEPENDENT'=>$data->no_of_dependent,
                'NO_OF_DOMESTIC_WORKER'=>$data->no_of_domestic_worker,
                'DOMESTIC_WORKER_SALARY'=>$data->domestic_worker_salary,
                'INVOICES'=>$data->invoices,
                'INSURANCE_VALUE'=>$data->insurance_value,
                'NUMBER_OF_DEPENDENT_IN_PRIVATE_SCHOOL'=>$data->number_of_dependent_in_private_school,
                'NUMBER_OF_DEPENDENT_IN_GOV_SCHOOL'=>$data->number_of_dependent_in_gov_school,
                'MONTHLY_EDUCATION_EXPENSE'=>$data->monthly_education_expense,
                'TRANSPORT_EXPENSES'=>$data->transport_expenses,
                'HEALTH_SERVICE_EXPENSE'=>$data->health_service_expense,
                'CALLING_EXPENSE'=>$data->calling_expense,
                'FOOD_BEVERAGE_EXPENSE'=>$data->food_beverage_expense,
                'DEPENDENT_EXPENSE'=>$data->dependent_expense,
                'MONTHLY_AID_PARENTS'=>$data->monthly_aid_parents,
                'OTHER_MONTHLY_EXPENSE'=>$data->other_monthly_expense,
                'KASTLE_USERID'=>$data->kastle_userid,
                'DOB_NEW'=>$DOB_NEW,
                'CRE_DATE'=>DB::connection('oracle')->raw('SYSDATE'),
                'NATIONALITY'=>$NATIONALITY,
                'ACC_NATIONALITY'=>$ACC_NATIONALITY,
                'GENDER_ID'=>$GENDER_ID,
                'ACC_GENDER'=>$ACC_GENDER
                // 'CRE_DATE'=>now(),
    ];
        return $input;
    }catch (Throwable $e) {
        printf($e->getMessage()."\n");
        printf("ERROR WHEN HANDLED INPUTS lead_id= ".$data->lead_id."\n");
        return array();
    }
    }

    public static function handle_input_and_update_to_oracle2($data){
        try{
            // $dob=$data->DOB;
            // $rdob=$data->rdob;
            // // $data1=[
            // //     'LEAD_ID'=>$data->lead_id,
            // //     'DOB'=>$dob,
            // //     'DOB_NEW'=>$rdob,
            // // ];
            // // $query = "
            // // UPDATE SIR_LEADS2_VU_KASTLE
            // // SET DOB = :DOB,
            // //     DOB_NEW=:DOB_NEW
            // // WHERE LEAD_ID = :LEAD_ID";
            $NATIONALITY = ($data->nationality == '' || $data->nationality == null) ? null : $data->nationality;
            $ACC_NATIONALITY = ($data->acc_nationality == '' || $data->acc_nationality == null) ? null : $data->acc_nationality;
            $GENDER_ID = ($data->gender_id == '' || $data->gender_id == null) ? null : $data->gender_id;
            $ACC_GENDER = ($data->acc_gender == '' || $data->acc_gender == null) ? null : $data->acc_gender;            
            $data1=[
                'LEAD_ID'=>$data->lead_id,
                'NATIONALITY'=>$NATIONALITY,
                'ACC_NATIONALITY'=>$ACC_NATIONALITY,
                'GENDER_ID'=>$GENDER_ID,
                'ACC_GENDER'=>$ACC_GENDER
            ];
            $query = "
                    UPDATE SIR_LEADS2_VU_KASTLE
                    SET NATIONALITY = :NATIONALITY,
                        ACC_NATIONALITY=:ACC_NATIONALITY,
                        GENDER_ID=:GENDER_ID,
                        ACC_GENDER=:ACC_GENDER
                    WHERE LEAD_ID = :LEAD_ID";
            DB::connection('oracle')->statement($query, $data1);
            DB::connection('oracle')->commit();
            return $data;
    }catch (Throwable $e) {
        printf($e->getMessage()."\n");
        printf("ERROR WHEN HANDLED INPUTS lead_id= ".$data->lead_id."\n");
        return array();
    }
    }
    public static function handle_data_and_update_to_oracle_2(){
        $i=1;
        leads_vu_kastle::whereNotNull('lead_id')
            ->whereNotNull('acc_id')
            ->orderBy('lead_id','asc')
            ->chunk(3000, function ($leads_vu_kastle_data)  use (&$i){
                foreach($leads_vu_kastle_data as $data){
                    $second=SIR_LEADS2_VU_KASTLE::where('lead_id',$data->lead_id)->first();
                    if($second!=null){
                        self::handle_input_and_update_to_oracle2($data);
                        printf("DATA UPDATED ,WHERE lead_id = ".$data->lead_id."\n");
                    }
                }
            });
    }
    public static function runProcedureCrm($lead_id)
    {
        try {
            $posted = '';
            $postedDt = '';
            $eMsg = '';
            $COMP_APPL_ID = '';
            $pdo = DB::connection('oracle')->getPdo();
            $stmt = $pdo->prepare('BEGIN PROCEDUER_NAME(:lead_id, :posted, :posted_dt, :e_msg, :COMP_APPL_ID); END;');
            $stmt->bindParam(':lead_id', $lead_id, \PDO::PARAM_INT);
            $stmt->bindParam(':posted', $posted, \PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 1);
            $stmt->bindParam(':posted_dt', $postedDt, \PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 19);
            $stmt->bindParam(':e_msg', $eMsg, \PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 500);
            $stmt->bindParam(':COMP_APPL_ID', $COMP_APPL_ID, \PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 500);
            $stmt->execute();
            if ($postedDt) {
                $postedDt = Carbon::parse($postedDt);
            }
            if ($eMsg==''){
                $eMsg=null;
            }
            if ($COMP_APPL_ID==''){
                $COMP_APPL_ID=null;
            }
            $data2=leads_vu_kastle::where('lead_id',$lead_id)->first();
            $data=[
                'POSTED' => $posted,
                'POSTING_DATE' => $postedDt,
                'ERROR_MESSAGE' => $eMsg,
                'COMP_APPL_ID'=>$COMP_APPL_ID,
                'LEAD_ID'=>$lead_id
            ];
            $query = "
                UPDATE SIR_LEADS2_VU_KASTLE
                SET POSTED = :POSTED,
                    POSTING_DATE = TO_DATE(:POSTING_DATE, 'YYYY-MM-DD HH24:MI:SS'),
                    ERROR_MESSAGE = :ERROR_MESSAGE,
                    COMP_APPL_ID =:COMP_APPL_ID
                WHERE LEAD_ID = :LEAD_ID";
            DB::connection('oracle')->statement($query, $data);
            DB::connection('oracle')->commit();
            return true;
        } catch (Exception $e) {
            printf($e->getMessage());

        }
    }
}