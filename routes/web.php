<?php

use Illuminate\Support\Facades\Route;

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
    return (!auth()->user()) ? view('auth.login') : redirect('/home');
});

Auth::routes();
// Auth::routes([
//     'register' => false, // Registration Routes...
//     // 'reset' => false, // Password Reset Routes...
//     // 'verify' => false, // Email Verification Routes...
// ]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/userdataregister/{year}', 'HomeController@user_data_register')->name('userdataregister');
Route::resource('/notifications', 'NotificationsController');

Route::group(['middleware' => ['auth']], function() {

    // Mail
    Route::resource('/mail', 'UserMailController');
    Route::get('/mail/view/{id}', 'UserMailController@view')->name('mail.view');

    // Applicants
    Route::resource('/applicants','ApplicantController');
    Route::post('/applicants/{id}/resetpassword','ApplicantController@resetpassword')->name('applicants.resetpassword');
    Route::post('/applicants/{id}/lock','ApplicantController@lock')->name('applicants.lock');
    Route::post('/applicants/{id}/approve','ApplicantController@approve')->name('applicants.approve');

    Route::post('/post/{id}/update', 'ApplicantController@post_update')->name('post.update');

    // Employees
    Route::resource('/employees','EmployeeManagementController');
    Route::post('/employees/{id}/resetpassword','EmployeeManagementController@resetpassword')->name('employees.resetpassword');

    // Testimonials
    Route::resource('/testimonials', 'TestimonialsController');
    Route::post('/testimonials/activate/{id}','TestimonialsController@activate')->name('testimonials.activate');
    Route::post('/testimonials/deactivate/{id}','TestimonialsController@deactivate')->name('testimonials.deactivate');

    // Transactions
    Route::resource('/transactions', 'CartController');
    Route::post('/transactions/finduser', 'CartController@find_user')->name('transactions.find_user');
    Route::post('/transactions/ordersummary', 'CartController@order_summary')->name('transactions.order_summary');
    Route::post('/transactions/placeorder', 'CartController@place_order')->name('transactions.place_order');

    // Reseller
    Route::resource('/reseller', 'ResellerController');
    Route::post('/reseller/{id}/activate','ApplicantController@activate')->name('reseller.activate');
    Route::post('/reseller/{id}/resetpassword','ResellerController@resetpassword')->name('reseller.resetpassword');
    Route::post('/reseller/{id}/update-code','ResellerController@update_code')->name('reseller.update_code');

    // Profile
    Route::get('/profile','UserProfileManagementController@show')->name('profile.show');
    Route::get('/profile/edit','UserProfileManagementController@edit')->name('profile.edit');
    Route::put('/profile/update','UserProfileManagementController@update')->name('profile.update');
    Route::put('/profile/{id}/resetpassword','UserProfileManagementController@resetpassword')->name('profile.resetpassword');
    Route::post('/profile/{id}/update-email','UserProfileManagementController@update_email')->name('profile.update_email');

    // Settings
    Route::resource('/forms','FormGroupController');
    Route::resource('/applications','ApplicationStatusController');
    Route::resource('/applicant-profile-form', 'ApplicantProfileFormController');
    Route::resource('/email-settings', 'EmailSettingsController');

    Route::resource('/input','FormInputController');
    Route::post('/save-user-form-group','FormGroupController@save_user_form_group')->name('save_user_form_group');
    Route::post('/save-user-application-status','ApplicationStatusController@save_user_application_status')->name('save_user_application_status');

    Route::resource('/roles-permissions', 'RolePermissionController');
    Route::post('/roles-permissions/assign-permissionto-role', 'RolePermissionController@assignpermissiontorole')->name('assignpermissiontorole');


    Route::resource('/services','ServicesController');
    Route::post('/services/activate/{id}','ServicesController@activate')->name('services.activate');
    Route::post('/services/deactivate/{id}','ServicesController@deactivate')->name('services.deactivate');

    Route::resource('/service-category', 'ServiceCategoryController');
    Route::resource('/currency', 'CurrencyController');

});

// Route::get('/spatieclearcache', function(){
//     app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
// });
// Route::get('/seedpermission', 'RolePermissionController@seedpermission')->name('seedpermission');