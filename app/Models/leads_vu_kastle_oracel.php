<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class leads_vu_kastle_oracel extends Model
{
    protected $connection = 'oracle';
    protected $table = 'leads_vu_kastle';
    public $timestamps = false;
    protected $primaryKey = 'lead_id';
    protected $fillable = [
        'lead_id',
        'acc_id',
        'kyc_id',
        'kastle_id',
        'fname',
        'phone1',
        'phone2',
        'phone3',
        'crmnatid',
        'pref_lang_id',
        'acc_lang',
        'email',
        'gender_id',
        'acc_gender',
        'crmdob',
        'city_id',
        'acc_city',
        'profession_id',
        'acc_profession',
        'salaryCat_id',
        'acc_salary_cat',
        'salaryType_id',
        'acc_salary_type',
        'salary',
        'salaryDeduction',
        'nationality',
        'acc_nationality',
        'salary_bank_id',
        'acc_bank',
        'acc_source',
        'acc_channel',
        'acc_created_by',
        'car_brand_id',
        'car_brand',
        'car_model_year_id',
        'car_model_years',
        'product_type_id',
        'product_type',
        'prospect_status_id',
        'prospect_status',
        'crmfinance_amount',
        'source_id',
        'prospect_source',
        'channel_id',
        'prospect_channel',
        'preferred_call_time_id',
        'pref_call_time',
        'created_by',
        'prospect_created_by',
        'creation_date',
        'assign_by',
        'prospect_assign_by',
        'assignment_to_id',
        'prospect_assign_to',
        'prospect_assign_to_email',
        'assign_date',
        'assignment_callback_date',
        'assignment_callback_time_id',
        'prospect_call_back_time',
        'assignment_note',
        'assignment_status_id',
        'assignment_status',
        'assignment_status_date',
        'convert_br',
        'convert_branch',
        'convert_branch_ar',
        'branch_url',
        'convert_br_date',
        'convert_salesman_id',
        'convert_salesman_name',
        'convert_salesman_name_ar',
        'convert_salesman_email',
        'convert_salesman_mobile',
        'convert_salesman_by_id',
        'convert_by',
        'convert_salesman_date',
        'convert_stage_id',
        'convert_status_id',
        'convert_status',
        'convert_status_ar',
        'convert_note',
        'convert_status_date',
        'modified_date',
        'lead_score',
        'service_code',
        'crmutm_source',
        'crmutm_campaign',
        'app_id',
        'full_name',
        'Mobile',
        'natid',
        'natid_expiry_date',
        'email_address',
        'LEADCF69',
        'house_allowance',
        'other_allowance',
        'social_status',
        'social_status_v',
        'social_status_kastle',
        'date',
        'month',
        'DOB',
        'rdob',
        'employer_name',
        'employer_city',
        'employer_city_v',
        'employer_city_kastle',
        'employer_address',
        'office_phone',
        'employer_type',
        'employer_type_v',
        'employer_type_kastle',
        'employment_tennure',
        'employment_tennure_y',
        'gross_salary',
        'bank_type',
        'bank_type_v',
        'iban_number',
        'ishaveadditionalincome',
        'ishaveadditionalincome_v',
        'additionalincomesource',
        'additionalincomesource_v',
        'additionalincomevalue',
        'ishavecreditcard',
        'ishavecreditcard_v',
        'creditcardvalue',
        'ishavededuction',
        'ishavededuction_v',
        'deduction_value',
        'ishavemortage',
        'ishavemortage_v',
        'mortagesupportamount',
        'monthlymortageinstallment',
        'noofinstallment',
        'noofinstallment_v',
        'finance_amount',
        'document_type',
        'document_type_v',
        'job_title',
        'job_title_v',
        'purpose_of_loan_v',
        'tc',
        'samah',
        'docbankstatementupload_file',
        'docsalaryletterupload_file',
        'docgosiupload_file',
        'docnatidupload_file',
        'verification_code',
        'application_creation_date',
        'house_expense',
        'housing_type',
        'housing_type_v',
        'housing_ownership',
        'housing_ownership_v',
        'housing_ownership_kastle',
        'monthly_rent',
        'no_of_dependent',
        'no_of_domestic_worker',
        'domestic_worker_salary',
        'invoices',
        'insurance_value',
        'number_of_dependent_in_private_school',
        'number_of_dependent_in_gov_school',
        'monthly_education_expense',
        'transport_expenses',
        'health_service_expense',
        'calling_expense',
        'food_beverage_expense',
        'dependent_expense',
        'monthly_aid_parents',
        'other_monthly_expense',
        'refer_mobile',
        'refer_name',
        'is_relative_hp',
        'is_previous_account',
        'is_real_beneficiary',
        'education_level',
        'education_level_v',
        'education_level_kastle',
        'utm_source',
        'utm_campaign'
    ];
}
