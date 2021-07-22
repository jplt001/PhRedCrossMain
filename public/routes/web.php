<?php

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

Route::get('/', function () {
    return view('templates.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/upload', 'HomeController@uplodReport')->name('upload');
Route::get('/getAggregates', 'HomeController@getAggregates');

// USERS ROUTE
Route::get('/users', 'HomeController@user')->name('users')->middleware('auth');
Route::get('/users/create', 'HomeController@createUser')->name('ucreate')->middleware('auth');
Route::post('/users/createUser', 'HomeController@saveUser')->name('ucreate')->middleware('auth');
Route::get('/users/view/{id}','HomeController@viewUser')->name('ucreate')->middleware('auth');
Route::get('/users/delete/{id}','HomeController@deleteUser')->name('delete')->middleware('auth');
Route::get('/users/update/{id}','HomeController@getEditUser')->name('update')->middleware('auth');
Route::post('/users/update','HomeController@updateUser')->name('update')->middleware('auth');

// Daily Reservation Routes
Route::get('/dre', 'HomeController@dre')->name('dre')->middleware('auth');
Route::get('/dre/add', 'HomeController@addDre')->name('dre')->middleware('auth');
Route::post('/dre/generateDre', 'HomeController@generateDre')->name('dre')->middleware('auth');
Route::post('/dre/createdre', 'HomeController@postCreateDre')->name('dre')->middleware('auth');

//Branch Setup Routes
Route::get('/branch','BranchController@index')->middleware('auth');
Route::get('/branch/create','BranchController@getCreate')->middleware('auth');
Route::post('/branch/create','BranchController@postCreate')->middleware('auth');
Route::get('/branch/edit/{id}','BranchController@getEdit')->middleware('auth');
Route::post('/branch/update','BranchController@postEdit')->middleware('auth');

//Hospital Setup Routes
Route::get('/hospital', 'HospitalController@index')->middleware('auth');
Route::get('/hospital/create', 'HospitalController@create')->middleware('auth');
Route::get('/hospital/view/{id}', 'HospitalController@view')->middleware('auth');
Route::post('/hospital/create', 'HospitalController@postCreate')->middleware('auth');
Route::post('/hospital/update', 'HospitalController@postUpdate')->middleware('auth');
Route::get('/hospital/delete/{id}', 'HospitalController@getDelete')->middleware('auth');

// Patient Routes
Route::get('/patient', 'PatientController@index')->middleware('auth');
Route::get('/patient/create', 'PatientController@create')->middleware('auth');
Route::post('/patient/create', 'PatientController@postCreate')->middleware('auth');
Route::get('/patient/view/{id}', 'PatientController@view')->middleware('auth');
Route::get('/patient/getPatients', 'PatientController@ajaxGetPatients')->middleware('auth');
Route::post('/patient/postApprove', 'PatientController@postApprove')->middleware('auth');
Route::post('/patient/postDeny', 'PatientController@postDeny')->middleware('auth');
Route::get('/patient/patient_delete/{id}', 'PatientController@patientDelete')->middleware('auth');
Route::post('/patient/update', 'PatientController@postUpdate')->middleware('auth');

// Blood Bank Routes
Route::get('/bloodbank', 'BloodBankController@index')->middleware('auth');
Route::get('/bloodbank/getCensus', 'BloodBankController@getCensus')->middleware('auth');
Route::get('/bloodbank/getInventory', 'BloodBankController@getInventory')->middleware('auth');
Route::get('/bloodbank/getReservation', 'BloodBankController@getReservation')->middleware('auth');
Route::get('/bloodbank/getInventoryTable', 'BloodBankController@getInventoryTable')->middleware('auth');
Route::post('/bloodbank/insertReservation', 'BloodBankController@insertReservation')->middleware('auth');
Route::post('/bloodbank/updateReservation', 'BloodBankController@updateReservation')->middleware('auth');
Route::post('/bloodbank/deleteReservation', 'BloodBankController@deleteReservation')->middleware('auth');
Route::post('/bloodbank/releaseReservation', 'BloodBankController@releaseReservation')->middleware('auth');
Route::post('/bloodbank/cancelReservation', 'BloodBankController@cancelReservation')->middleware('auth');
Route::get('/bloodbank/getComponentList', 'BloodBankController@getComponentList')->middleware('auth');
Route::get('/bloodbank/getAvailableSerology', 'BloodBankController@getAvailableSerology')->middleware('auth');


//Blood Referral Routes
Route::get('/blood_referral', 'BloodReferralController@index')->middleware('auth');
Route::post('/blood_referral/savePatientReferral', 'BloodReferralController@savePatientReferral')->middleware('auth');
Route::post('/blood_referral/saveActivityHeld', 'BloodReferralController@saveActivityHeld')->middleware('auth');
Route::post('/blood_referral/saveNewPatientReferall', 'BloodReferralController@saveNewPatientReferall')->middleware('auth');


//Blood Register Routes
Route::get('/blood_register', 'BloodRegisterController@index')->middleware('auth');
Route::post('/blood_register/save', 'BloodRegisterController@postSave')->middleware('auth');
Route::get('/blood_register/delete/{id}', 'BloodRegisterController@getDelete')->middleware('auth');
Route::post('/blood_register/setDeffered', 'BloodRegisterController@setDeffered');
Route::post('/blood_register/postOldDonorSave', 'BloodRegisterController@postOldDonorSave');
Route::post('/blood_register/postUpdateDonorReg', 'BloodRegisterController@postUpdateDonorReg');
Route::get('/blood_register/getInfo/{id}', 'BloodRegisterController@getInfo');
Route::get('/blood_register/setApproved/{id}', 'BloodRegisterController@setApproved');
Route::post('/blood_register/update', 'BloodRegisterController@postUpdate');

// Forecasting Routes
Route::get('/forecast', 'ForecastingController@index')->middleware('auth');
Route::get('/forecast/getForecast', 'ForecastingController@getForecast')->middleware('auth');
Route::get('/forecast/getForecastReason', 'ForecastingController@getForecastReason');
Route::get('/forecast/generateData', 'ForecastingController@generateData');

// AJAX Routes
Route::get('/ajax/getUserInfo/{id}', 'AjaxController@getUserInf')->middleware('auth');
Route::get('/ajax/getBloodReferrals', 'AjaxController@getBloodReferrals')->middleware('auth');
Route::get('/ajax/getAccountedRedemmed', 'AjaxController@getTotalAccountedRedemeed')->middleware('auth');
Route::get('/ajax/getActivityHeld', 'AjaxController@getActivityHeld')->middleware('auth');
Route::get('/ajax/getHospitals', 'AjaxController@getHospitals')->middleware('auth');
Route::get('/ajax/getPatients', 'AjaxController@getPatients')->middleware('auth');
Route::get('/ajax/getOrganizations', 'AjaxController@getOrganizations')->middleware('auth');
Route::get('/ajax/getOrgInfo/{id}', 'AjaxController@getOrgInfo');
Route::get('/ajax/getHospitalInfo/{id}', 'AjaxController@getHospitalInfo');
Route::get('/ajax/getPatientInfo/{id}', 'AjaxController@getPatientInfo');
Route::get('/ajax/getDonorInfo/{id}', 'AjaxController@getDonorInfo');
Route::get('/ajax/getBloodAvailableToday', 'AjaxController@getBloodAvailableToday');


/*
 *
 * Report Routes
 *
 */

Route::get('/report/generateReferralReport', 'ReportController@generateReportReservation')->middleware('auth');
Route::post('/report/postGenerateReferralReport', 'ReportController@postGenerateReferralReport')->middleware('auth');
// Serology
Route::get('/report/serology', 'ReportController@generateReportSerology')->middleware('auth');
Route::post('/report/serology', 'ReportController@postGenerateReportSerology')->middleware('auth');
// Report Blood Donor
Route::get('/report/blooddonor', 'ReportController@getReportBloodDonor')->middleware('auth');
Route::post('/report/blooddonor', 'ReportController@postReportBloodDonor')->middleware('auth');
// Blood Bank
Route::get('/report/bloodbank', 'ReportController@getReportBloodbank')->middleware('auth');
Route::post('/report/bloodbank/postReportBloodbank', 'ReportController@postReportBloodbank')->middleware('auth');

Route::post('/report/generatePdf', 'ReportController@generatePdf')->middleware('auth');

//Serology
Route::get('serology', 'SerologyController@index')->middleware('auth');
Route::post('serology/save', 'SerologyController@save')->middleware('auth');
Route::get('serology/getSerologyResults', 'SerologyController@getSerologyResults')->middleware('auth');
Route::get('serology/failed/{id}', 'SerologyController@setFailed');
Route::get('serology/passed/{id}', 'SerologyController@setPassed');
Route::post('serology/updateSerologyLabResults', 'SerologyController@updateSerologyLabResults');
Route::post('serology/updateFinalLabResults', 'SerologyController@updateFinalLabResults');
Route::get('serology/getLabResults', 'SerologyController@getLabResults');
Route::get('serology/getDonorResults', 'SerologyController@getDonorResults');

// Organization
Route::get('organization', 'OrganizationsController@index');
Route::get('organization/setDelete/{id}', 'OrganizationsController@setDelete');
Route::get('organization/create', 'OrganizationsController@getCreate');
Route::post('organization/create', 'OrganizationsController@postCreate');
Route::post('organization/update', 'OrganizationsController@postUpdate');

// Items
Route::get('item', 'ItemController@index')->middleware('auth');
Route::get('item/edit/{id}', 'ItemController@edit')->middleware('auth');
Route::get('item/view/{id}', 'ItemController@show')->middleware('auth');
Route::post('item/update', 'ItemController@update')->middleware('auth');
Route::get('item/delete/{id}', 'ItemController@destroy')->middleware('auth');
Route::get('item/create', 'ItemController@create')->middleware('auth');
Route::post('item/save', 'ItemController@save')->middleware('auth');

// Role Routes

Route::get('role', 'RoleController@index');


