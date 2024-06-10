<?php
namespace App\Services;
use App\Jobs\TransferDataFromMYSQLtoORACEL;
use DateTime;
use Carbon\Carbon;
use App\Models\{leads_vu_kastle,leads_vu_kastle_oracel};
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;
class TransferDataServices{

    public static function TransferDataFromMySqlToOracel (){


        printf("aoss\n");
        TransferDataFromMYSQLtoORACEL::dispatch()->delay(now()->addMinutes(1));
    }

    public static function TransferDataFromMySqlToOracel2 (){


        printf("zada\n");
        TransferDataFromMYSQLtoORACEL::dispatch()->delay(now()->addMinutes(1));
    }
    public static function handle_data_and_insert_to_oracle(){
        $i =1 ;
        leads_vu_kastle::whereNotNull('lead_id')
            ->whereNotNull('acc_id')
            // ->whereBetween('creation_date', [now()->subWeek(), now()])
            ->orderBy('lead_id','asc')
            ->chunk(100, function ($leads_vu_kastle_data)  use (&$i){
                foreach($leads_vu_kastle_data as $data){
                    $second=leads_vu_kastle::whereIn('lead_id',$data->lead_id)->where('acc_id',$$data->acc_id)->first();
                    if($second==null){
                        $input=self::handle_input_before_insert_to_oracle($data);
                        self::insert_to_oracle_and_run_proceduer($input);
                        printf("DATA INSERTED ,WHERE lead_id = ".$data->lead_id."\n");
                    }else{
                        printf("DATA IS FOUND ,WHERE lead_id = ".$data->lead_id."\n");
                    }
                }
            });
    }
    public static function insert_to_oracle_and_run_proceduer($input){
        try{
            leads_vu_kastle_oracel::insert($input);
            DB::connection('oracle')->statement("BEGIN proceduer_name(:proceduer_value); END;", ['proceduer_value' =>  $input['lead_id']]);
        }catch (Throwable $e) {
            printf($e->getMessage()."\n");
            printf("ERROR WHEN INSERT lead_id= ".$input['lead_id']."\n");
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
            if ($assignment_callback_date !=null){
                $assignment_callback_date=Carbon::parse($assignment_callback_date);
            }
            $rdob=$data->rdob;
            if ($rdob !=null){
                $rdob=Carbon::parse($rdob);
            }
            $modified_date=$data->modified_date;
            if ($modified_date !=null){
                $modified_date=Carbon::parse($modified_date);
            }
            $input=[
                'lead_id' =>$data->lead_id,
                'acc_id' =>$data->acc_id,
                'kyc_id' =>$data->kyc_id,
                'kastle_id' =>$data->kastle_id,
                'fname' =>$data->fname,
                'phone1' =>$data->phone1,
                'phone2' =>$data->phone2,
                'phone3' =>$data->phone3,
                'crmnatid' =>$data->crmnatid,
                'pref_lang_id' =>$data->pref_lang_id,
                'acc_lang' =>$data->acc_lang,
                'email' =>$data->email,
                'gender_id' =>$data->gender_id,
                'acc_gender' =>$data->acc_gender,
                'crmdob' =>$data->crmdob,
                'city_id' =>$data->city_id,
                'acc_city' =>$data->acc_city,
                'profession_id' =>$data->profession_id,
                'acc_profession' =>$data->acc_profession,
                'salaryCat_id' =>$data->salaryCat_id,
                'acc_salary_cat' =>$data->acc_salary_cat,
                'salaryType_id' =>$data->salaryType_id,
                'acc_salary_type' =>$data->acc_salary_type,
                'salary' =>$data->salary,
                'salaryDeduction' =>$data->salaryDeduction,
                'nationality' =>$data->nationality,
                'acc_nationality' =>$data->acc_nationality,
                'salary_bank_id' =>$data->salary_bank_id,
                'acc_bank' =>$data->acc_bank,
                'acc_source' =>$data->acc_source,
                'acc_channel' =>$data->acc_channel,
                'acc_created_by' =>$data->acc_created_by,
                'car_brand_id' =>$data->car_brand_id,
                'car_brand' =>$data->car_brand,
                'car_model_year_id' =>$data->car_model_year_id,
                'car_model_years' =>$data->car_model_years,
                'product_type_id' =>$data->product_type_id,
                'product_type' =>$data->product_type,
                'prospect_status_id' =>$data->prospect_status_id,
                'prospect_status' =>$data->prospect_status,
                'crmfinance_amount' =>$data->crmfinance_amount,
                'source_id' =>$data->source_id,
                'prospect_source' =>$data->prospect_source,
                'channel_id' =>$data->channel_id,
                'prospect_channel' =>$data->prospect_channel,
                'preferred_call_time_id' =>$data->preferred_call_time_id,
                'pref_call_time' =>$data->pref_call_time,
                'created_by' =>$data->created_by,
                'prospect_created_by' =>$data->prospect_created_by,
                'creation_date' =>$creation_date,
                'assign_by' =>$data->assign_by,
                'prospect_assign_by' =>$data->prospect_assign_by,
                'assignment_to_id' =>$data->assignment_to_id,
                'prospect_assign_to' =>$data->prospect_assign_to,
                'prospect_assign_to_email' =>$data->prospect_assign_to_email,
                'assign_date' =>$assign_date,
                'assignment_callback_date' =>$assignment_callback_date,
                'assignment_callback_time_id' =>$data->assignment_callback_time_id,
                'prospect_call_back_time' =>$data->prospect_call_back_time,
                'assignment_note' =>$data->assignment_note,
                'assignment_status_id' =>$data->assignment_status_id,
                'assignment_status' =>$data->assignment_status,
                'assignment_status_date' =>$assignment_status_date,
                'convert_br' =>$data->convert_br,
                'convert_branch' =>$data->convert_branch,
                'convert_branch_ar' =>$data->convert_branch_ar,
                'branch_url' =>$data->branch_url,
                'convert_br_date' =>$convert_br_date,
                'convert_salesman_id' =>$data->convert_salesman_id,
                'convert_salesman_name' =>$data->convert_salesman_name,
                'convert_salesman_name_ar' =>$data->convert_salesman_name_ar,
                'convert_salesman_email' =>$data->convert_salesman_email,
                'convert_salesman_mobile' =>$data->convert_salesman_mobile,
                'convert_salesman_by_id' =>$data->convert_salesman_by_id,
                'convert_by' =>$data->convert_by,
                'convert_salesman_date' =>$convert_salesman_date,
                'convert_stage_id' =>$data->convert_stage_id,
                'convert_status_id' =>$data->convert_status_id,
                'convert_status' =>$data->convert_status,
                'convert_status_ar' =>$data->convert_status_ar,
                'convert_note' =>$data->convert_note,
                'convert_status_date' =>$data->convert_status_date,
                'modified_date' =>$modified_date,
                'lead_score' =>$data->lead_score,
                'service_code' =>$data->service_code,
                'crmutm_source' =>$data->crmutm_source,
                'crmutm_campaign' =>$data->crmutm_campaign,
                'app_id' =>$data->app_id,
                'full_name' =>$data->full_name,
                'Mobile' =>$data->Mobile,
                'natid' =>$data->natid,
                'natid_expiry_date' =>$data->natid_expiry_date,
                'email_address' =>$data->email_address,
                'LEADCF69' =>$data->LEADCF69,
                'house_allowance' =>$data->house_allowance,
                'other_allowance' =>$data->other_allowance,
                'social_status' =>$data->social_status,
                'social_status_v' =>$data->social_status_v,
                'social_status_kastle' =>$data->social_status_kastle,
                'date' =>$data->date,
                'month' =>$data->month,
                'DOB' =>$data->DOB,
                'rdob' =>$rdob,
                'employer_name' =>$data->employer_name,
                'employer_city' =>$data->employer_city,
                'employer_city_v' =>$data->employer_city_v,
                'employer_city_kastle' =>$data->employer_city_kastle,
                'employer_address' =>$data->employer_address,
                'office_phone' =>$data->office_phone,
                'employer_type' =>$data->employer_type,
                'employer_type_v' =>$data->employer_type_v,
                'employer_type_kastle' =>$data->employer_type_kastle,
                'employment_tennure' =>$data->employment_tennure,
                'employment_tennure_y' =>$data->employment_tennure_y,
                'gross_salary' =>$data->gross_salary,
                'bank_type' =>$data->bank_type,
                'bank_type_v' =>$data->bank_type_v,
                'iban_number' =>$data->iban_number,
                'ishaveadditionalincome' =>$data->ishaveadditionalincome,
                'ishaveadditionalincome_v' =>$data->ishaveadditionalincome_v,
                'additionalincomesource' =>$data->additionalincomesource,
                'additionalincomesource_v' =>$data->additionalincomesource_v,
                'additionalincomevalue' =>$data->additionalincomevalue,
                'ishavecreditcard' =>$data->ishavecreditcard,
                'ishavecreditcard_v' =>$data->ishavecreditcard_v,
                'creditcardvalue' =>$data->creditcardvalue,
                'ishavededuction' =>$data->ishavededuction,
                'ishavededuction_v' =>$data->ishavededuction_v,
                'deduction_value' =>$data->deduction_value,
                'ishavemortage' =>$data->ishavemortage,
                'ishavemortage_v' =>$data->ishavemortage_v,
                'mortagesupportamount' =>$data->mortagesupportamount,
                'monthlymortageinstallment' =>$data->monthlymortageinstallment,
                'noofinstallment' =>$data->noofinstallment,
                'noofinstallment_v' =>$data->noofinstallment_v,
                'finance_amount' =>$data->finance_amount,
                'document_type' =>$data->document_type,
                'document_type_v' =>$data->document_type_v,
                'job_title' =>$data->job_title,
                'job_title_v' =>$data->job_title_v,
                'purpose_of_loan_v' =>$data->purpose_of_loan_v,
                'tc' =>$data->tc,
                'samah' =>$data->samah,
                'docbankstatementupload_file' =>$data->docbankstatementupload_file,
                'docsalaryletterupload_file' =>$data->docsalaryletterupload_file,
                'docgosiupload_file' =>$data->docgosiupload_file,
                'docnatidupload_file' =>$data->docnatidupload_file,
                'verification_code' =>$data->verification_code,
                'application_creation_date' =>$application_creation_date,
                'house_expense' =>$data->house_expense,
                'housing_type' =>$data->housing_type,
                'housing_type_v' =>$data->housing_type_v,
                'housing_ownership' =>$data->housing_ownership,
                'housing_ownership_v' =>$data->housing_ownership_v,
                'housing_ownership_kastle' =>$data->housing_ownership_kastle,
                'monthly_rent' =>$data->monthly_rent,
                'no_of_dependent' =>$data->no_of_dependent,
                'no_of_domestic_worker' =>$data->no_of_domestic_worker,
                'domestic_worker_salary' =>$data->domestic_worker_salary,
                'invoices' =>$data->invoices,
                'insurance_value' =>$data->insurance_value,
                'number_of_dependent_in_private_school' =>$data->number_of_dependent_in_private_school,
                'number_of_dependent_in_gov_school' =>$data->number_of_dependent_in_gov_school,
                'monthly_education_expense' =>$data->monthly_education_expense,
                'transport_expenses' =>$data->transport_expenses,
                'health_service_expense' =>$data->health_service_expense,
                'calling_expense' =>$data->calling_expense,
                'food_beverage_expense' =>$data->food_beverage_expense,
                'dependent_expense' =>$data->dependent_expense,
                'monthly_aid_parents' =>$data->monthly_aid_parents,
                'other_monthly_expense' =>$data->other_monthly_expense,
                'refer_mobile' =>$data->refer_mobile,
                'refer_name' =>$data->refer_name,
                'is_relative_hp' =>$data->is_relative_hp,
                'is_previous_account' =>$data->is_previous_account,
                'is_real_beneficiary' =>$data->is_real_beneficiary,
                'education_level' =>$data->education_level,
                'education_level_v' =>$data->education_level_v,
                'education_level_kastle' =>$data->education_level_kastle,
                'utm_source' =>$data->utm_source,
                'utm_campaign'=>$data->utm_campaign
            ];
        return $input;
    }catch (Throwable $e) {
        printf($e->getMessage()."\n");
        printf("ERROR WHEN HANDLED INPUTS lead_id= ".$data->lead_id."\n");
        return array();
    }
    }
}