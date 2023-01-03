<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\member\MemberController;
use App\Http\Controllers\member\NotificationController;
use App\Http\Controllers\taipeiData\TaipeiDataController;
use App\Http\Controllers\login\LoginController;
use App\Http\Controllers\excel\ExportController;
use App\Http\Controllers\excel\ImportController;
use App\Http\Controllers\algorithm\TestController;
use App\Http\Controllers\leaveData\LeaveDataController;
use App\Http\Controllers\set\SetController;
use App\Http\Controllers\crm\CreateFormController;
use App\Http\Controllers\crm\crm_excel\CrmImportController;
use App\Http\Controllers\crm\crm_excel\CrmExportController;
use App\Http\Controllers\crm\CrmParameterController;
use App\Http\Controllers\crm\CrmSystemManagementController;
use App\Http\Controllers\crm\CrmVisitRecordManagementController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[LoginController::class,'loginForm']);
Route::get('/login',[LoginController::class,'loginForm']);
Route::post('/login',[LoginController::class,'loginPost']);
Route::get('/logout',[LoginController::class,'logout']);

Route::prefix('member')->group(function () {
    Route::get('/first_page',[MemberController::class,'first_page']);

    Route::get('/home',[MemberController::class,'home']);
    Route::get('/calendar_month',[MemberController::class,'calendar_month']); //行事曆月份
    Route::post('/add_case',[MemberController::class,'add_case']); //行事曆月份
    Route::get('/calendar_day',[MemberController::class,'calendar_day']); //行事曆 天

    Route::get('/calendar_day_dataget',[MemberController::class,'calendar_day_dataget']); //行事曆 天 (json抓資料)

    Route::get('/detail_edit',[MemberController::class,'detail_edit']); //詳細資料與編輯
    Route::post('/detail_edit',[MemberController::class,'detail_edit_post']); //詳細資料與編輯 送出修改
    Route::post('/detail_edit_delete',[MemberController::class,'detail_edit_delete']); //詳細資料與編輯 刪除資料
    Route::get('/file_delete',[MemberController::class,'file_delete']); //詳細資料與編輯 刪除資料
    //查詢行程
    Route::get('/search_calendar',[MemberController::class,'search_calendar']); //搜尋介面
    Route::post('/search_calendar_post',[MemberController::class,'search_calendar_post']); //搜尋執行

    Route::get('/get_informant_types',[MemberController::class,'get_informant_types']); //取得通報類型(json)
    Route::get('/phoneToMember',[MemberController::class,'phoneToMember']); //根據電話取得客戶名字(json)

    Route::get('/things_list',[MemberController::class,'things_list_form']); //處理清單
    Route::post('/things_list',[MemberController::class,'things_list_post']); //查詢處理清單
    Route::get('/thing_state_change',[MemberController::class,'thing_state_change']); //處理狀態更改(json)
    

    Route::get('/calendar_import',[ImportController::class,'calendar_import_form']); //行程資料 匯入資料庫 (介面)
    Route::post('/calendar_import',[ImportController::class,'calendar_import_post']); //EXCEL行程資料 匯入資料庫 (執行匯入)
    Route::post('/calendar_import_ics',[ImportController::class,'calendar_import_ics']); //GOOGLE ics行程資料 匯入資料庫 (執行匯入)

    Route::get('/calendar_export',[ExportController::class,'calendar_export']); //匯出個人行程

    Route::post('/calendar_delete_recored',[ExportController::class,'calendar_delete_recored']); //刪除不要的行程

    Route::prefix('set')->group(function () {
        Route::get('/',[SetController::class,'set_form']); //設定介面
        Route::get('/change_informant_list',[SetController::class,'change_informant_list']); //改變通報單位
    });
    //修改個人資料
    Route::get('/personalSet', [MemberController::class, 'personalSet']); //修改個人資料頁面(密碼介面)
    Route::post('/personalSetPost', [MemberController::class, 'personalSetPost']); //修改個人資料(密碼修改)

    //批次處理，邀請對象 
    Route::get('/calendarBatchGroup', [MemberController::class, 'calendarBatchGroup']); //批次處理，邀請對象 (介面)
    Route::post('/calendarBatchGroupSearch', [MemberController::class, 'calendarBatchGroupSearch']); //批次處理，邀請對象 (查詢)
    Route::post('/calendarBatchGroupPost', [MemberController::class, 'calendarBatchGroupPost']); //批次處理，邀請對象 (查詢)

    //通知
    Route::get('/notificationDetailEdit', [NotificationController::class, 'notificationDetailEdit']); //行程變動通知
    
});
//抓取農會請假平台資料
Route::prefix('leave_data')->group(function () {
    Route::get('/leave_first_page_data_json',[LeaveDataController::class,'leave_first_page_data_json']);
});

// 載入節日資料(台北政府公開的節日資料)
Route::get('/getFestivals',[TaipeiDataController::class,'getFestivals']);


//test laravel excel export and import(可刪除)
Route::get('/ED',[ExportController::class,'ED']); //測試下載EXCEL
Route::get('/EU',[ImportController::class,'EU_form']); //測試匯入EXCEL介面
Route::post('/EU',[ImportController::class,'EU_post']); //測試匯入EXCEL介面

//line通知
Route::get('/line_notification',[NotificationController::class,'line_notification']); //預定通知 (給微軟跑15分鐘一次)


//測試演算
Route::get('/T1',[TestController::class,'T1']); //測試演算親屬
Route::get('/color_change',[TestController::class,'color_change']); //修改顏色順序變化用 (修改後可刪除)


Route::prefix('crm')->group(function () { 
    Route::get('/',[CreateFormController::class,'crmFrom']); //客戶管理介面
    Route::get('/basic_customer_data',[CreateFormController::class,'basic_customer_data']); //介面客戶基本資料
    Route::post('/basic_customer_data',[CreateFormController::class,'basic_customer_data_post']); //編輯客戶基本資料
    Route::post('/search_customer_data',[CreateFormController::class,'search_customer_data']); //查詢客戶資料

    Route::get('/create_customer_data',[CreateFormController::class,'create_customer_data']); //介面新建客戶基本資料
    Route::post('/create_customer_data',[CreateFormController::class,'create_customer_data_post']); //新建客戶基本資料
    Route::get('/delete_customer_data',[CreateFormController::class,'delete_customer_data']); //刪除客戶基本資料

    Route::get('/crm_search_customer',[CreateFormController::class,'crm_search_customer']); //同戶親屬-查詢客戶
    Route::get('/change_c_number_family',[CreateFormController::class,'change_c_number_family']); //同戶親屬-添加親屬
    Route::get('/customer_family',[CreateFormController::class,'customer_family']); //取得同戶親屬資料
    
    Route::get('/visit_record',[CreateFormController::class,'visit_record']); //列出拜訪紀錄
    Route::get('/visit_record_add',[CreateFormController::class,'visit_record_add']); //新增拜訪紀錄
    Route::get('/visit_customer_basic_information_get',[CreateFormController::class,'visit_customer_basic_information_get']); //取得該筆拜訪紀錄
    Route::get('/visit_record_edit_get',[CreateFormController::class,'visit_record_edit_get']); //取得該筆拜訪紀錄
    Route::get('/visit_record_edit',[CreateFormController::class,'visit_record_edit']); //編輯該筆拜訪紀錄
    Route::get('/visit_record_delete',[CreateFormController::class,'visit_record_delete']); //刪除所選的拜訪紀錄
    
    Route::get('/account_balance',[CreateFormController::class,'account_balance']); //帳戶餘額

    Route::get('/change_customer',[CreateFormController::class,'change_customer']); //大額異動

    Route::get('/contribution',[CreateFormController::class,'contribution']); //貢獻度

    Route::get('/insurance_information',[CreateFormController::class,'insurance_information']); //保險資訊

    Route::get('/vip_management',[CreateFormController::class,'vip_management']); //vip管理(介面)
    Route::get('/search_vip_management',[CreateFormController::class,'search_vip_management']); //vip管理(查詢)
    Route::get('/add_vip_management',[CreateFormController::class,'add_vip_management']); //vip管理(新增介面)
    Route::get('/get_customer_data',[CreateFormController::class,'get_customer_data']); //取得客戶資料
    Route::get('/add_vip_management_post',[CreateFormController::class,'add_vip_management_post']); //vip管理(新增送出)
    Route::get('/delete_vip_management',[CreateFormController::class,'delete_vip_management']); //vip管理(刪除)
    Route::get('/vip_management_export',[CrmExportController::class,'vip_management_export']); //vip管理(匯出資料)
    

    /* 資料管理 */
    //客戶資料
    Route::get('/basic_customer_data_import_check',[CrmImportController::class,'basic_customer_data_import_check']); //EXCEL客戶資料 匯入資料庫 (介面1)
    Route::get('/basic_customer_data_import',[CrmImportController::class,'basic_customer_data_import']); //EXCEL客戶資料 匯入資料庫 (介面2)
    Route::post('/basic_customer_data_import',[CrmImportController::class,'basic_customer_data_import_post']); //EXCEL客戶資料 匯入資料庫 (匯入)
    Route::get('/basic_customer_data_export',[CrmExportController::class,'basic_customer_data_export']); //EXCEL客戶資料 匯出
    //每月餘額轉入
    Route::get('/account_balance_import_check',[CrmImportController::class,'account_balance_import_check']); //EXCEL每月餘額轉入 匯入資料庫 (介面1)
    Route::get('/account_balance_import',[CrmImportController::class,'account_balance_import']); //EXCEL每月餘額轉入 匯入資料庫 (介面2)
    Route::post('/account_balance_import',[CrmImportController::class,'account_balance_import_post']); //EXCEL每月餘額轉入 匯入資料庫 (匯入)
    Route::get('/account_balance_export',[CrmExportController::class,'account_balance_export']); //EXCEL每月餘額轉入 匯出
    Route::get('/account_balance_check',[CrmImportController::class,'account_balance_check']); //EXCEL每月餘額轉入 查詢
    //每日大額轉入
    Route::get('/change_customer_import_check',[CrmImportController::class,'change_customer_import_check']); //EXCEL每日大額轉入 匯入資料庫 (介面1)
    Route::get('/change_customer_import',[CrmImportController::class,'change_customer_import']); //EXCEL每日大額轉入 匯入資料庫 (介面2)
    Route::post('/change_customer_import',[CrmImportController::class,'change_customer_import_post']); //EXCEL每日大額轉入 匯入資料庫 (匯入)
    Route::get('/change_customer_export',[CrmExportController::class,'change_customer_export']); //EXCEL每日大額轉入 匯出
    Route::get('/change_customer_check',[CrmImportController::class,'change_customer_check']); //EXCEL每日大額轉入 查詢
    //每月貢獻度轉入
    Route::get('/contribution_import_check',[CrmImportController::class,'contribution_import_check']); //EXCEL每月貢獻度轉入 匯入資料庫 (介面1)
    Route::get('/contribution_import',[CrmImportController::class,'contribution_import']); //EXCEL每月貢獻度轉入 匯入資料庫 (介面2)
    Route::post('/contribution_import',[CrmImportController::class,'contribution_import_post']); //EXCEL每月貢獻度轉入 匯入資料庫 (匯入)
    Route::get('/contribution_export',[CrmExportController::class,'contribution_export']); //EXCEL每月貢獻度轉入 匯出
    Route::get('/contribution_check',[CrmImportController::class,'contribution_check']); //EXCEL每月貢獻度轉入 查詢
    //每月保險資訊轉入
    Route::get('/insurance_information_import_check',[CrmImportController::class,'insurance_information_import_check']); //EXCEL每月保險資訊轉入 匯入資料庫 (介面1)
    Route::get('/insurance_information_import',[CrmImportController::class,'insurance_information_import']); //EXCEL每月保險資訊轉入 匯入資料庫 (介面2)
    Route::post('/insurance_information_import',[CrmImportController::class,'insurance_information_import_post']); //EXCEL每月保險資訊轉入 匯入資料庫 (匯入)
    Route::get('/insurance_information_export',[CrmExportController::class,'insurance_information_export']); //EXCEL每月保險資訊轉入 匯出
    Route::get('/insurance_information_check',[CrmImportController::class,'insurance_information_check']); //EXCEL每月保險資訊轉入 查詢
    
    /* 參數管理 */
    Route::get('/parameter_set/{select}',[CrmParameterController::class,'parameter_set']); //參數 (介面)
    Route::get('/parameter_data',[CrmParameterController::class,'parameter_data']); //參數資料 (介面)
    Route::get('/parameter_edit_data',[CrmParameterController::class,'parameter_edit_data']); //參數 編輯設定資料
    Route::get('/parameter_edit',[CrmParameterController::class,'parameter_edit']); //參數 編輯設定

    //使用者管理
    Route::get('/manage_accounts',[CrmSystemManagementController::class,'manage_accounts']); //使用者管理
    Route::get('/search_manage_accounts',[CrmSystemManagementController::class,'search_manage_accounts']); //使用者管理 查詢
    Route::get('/manage_account_edit_data',[CrmSystemManagementController::class,'manage_account_edit_data']); //使用者管理 (取得資料)
    Route::get('/manage_account_edit',[CrmSystemManagementController::class,'manage_account_edit']); //使用者管理 (儲存)
    
    //ao異動
    Route::get('/change_customer_ao_staff_record',[CrmSystemManagementController::class,'change_customer_ao_staff_record']); //客戶AO異動紀錄
    Route::get('/search_change_customer_ao_staff_record',[CrmSystemManagementController::class,'search_change_customer_ao_staff_record']); //客戶AO異動 查詢
    Route::get('/change_customer_ao_staff',[CrmSystemManagementController::class,'change_customer_ao_staff']); //客戶AO異動作業 (介面)
    Route::get('/search_change_customer_ao_staff',[CrmSystemManagementController::class,'search_change_customer_ao_staff']); //客戶AO異動作業 (查詢ao與相關ao客戶)
    Route::post('/change_customer_ao_staff_post',[CrmSystemManagementController::class,'change_customer_ao_staff_post']); //客戶AO異動作業 (送出修改)
    Route::get('/change_customer_ao_staff_export',[CrmExportController::class,'change_customer_ao_staff_export']); //客戶AO異動 匯出
    
    //資料庫備份/還原
    Route::get('/database_backup',[CrmSystemManagementController::class,'database_backup']); //資料庫備份 介面
    Route::get('/database_backup_go',[CrmSystemManagementController::class,'database_backup_go']); //資料庫備份 下載
    Route::post('/database_backup_add',[CrmSystemManagementController::class,'database_backup_add']); //資料庫備份 新增 
   
    Route::get('/database_restore',[CrmSystemManagementController::class,'database_restore']); //資料庫還原 介面
    Route::post('/database_restore_go',[CrmSystemManagementController::class,'database_restore_go']); //資料庫還原 介面
    

    //拜訪記錄管理
    Route::get('/visit_records_manage',[CrmVisitRecordManagementController::class,'visit_records_manage']); // 客戶拜訪紀錄
    Route::get('/search_visit_records_manage',[CrmVisitRecordManagementController::class,'search_visit_records_manage']); // 客戶拜訪紀錄 查詢
    Route::get('/visit_records_manage_delete',[CrmVisitRecordManagementController::class,'visit_records_manage_delete']); // 客戶拜訪紀錄 刪除
    Route::get('/visit_records_manage_add',[CrmVisitRecordManagementController::class,'visit_records_manage_add']); //客戶拜訪紀錄 新增
    Route::get('/search_visit_records_manage_export',[CrmExportController::class,'search_visit_records_manage_export']); //客戶拜訪紀錄 匯出
    

    Route::get('/get_city_area',[CreateFormController::class,'get_city_area']); //jquey get city area and postcode
    // Route::get('/createCustomerBasicData',[CreateFormController::class,'createCustomerBasicData']); //建立客戶資料
});

// Route::get('/ttt', function () {
//     return view('tt');
// });

// 1~95792  1~16497
Route::get('/rangeonetobignumberrandom', [TestController::class,'rangeonetobignumberrandom']);
