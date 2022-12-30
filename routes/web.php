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

// Controllers
$controller_user = '\App\Http\Controllers\UserController@';
$controller_cites = 'App\Http\Controllers\CiteController@';
$controller_notification = 'App\Http\Controllers\NotificationController@';
$controller_employee = 'App\Http\Controllers\EmployeeController@';
$controller_speciality = 'App\Http\Controllers\SpecialityController@';
$controller_doctors =  'App\Http\Controllers\DoctorController@';
$controller_status = 'App\Http\Controllers\StatusController@';
$controller_family = 'App\Http\Controllers\FamilyController@';


// rutas de usuarios
Route::post('api/register', $controller_user.'register');
Route::post('api/login', $controller_user.'login');
Route::put('api/user/update', $controller_user.'update');
Route::put('api/user/change', $controller_user.'changePassword');

// rutas de usuario referente a citas
Route::post('api/user/cite', $controller_cites.'addCite');
Route::get('api/user/cites', $controller_cites.'showCites');
Route::get('api/user/cite/{id}', $controller_cites.'showCite');
Route::get('api/user/searchCites/{params}', $controller_cites.'getCitesByParamas');
Route::delete('api/user/cite/{id}', $controller_cites.'deleteCite');
Route::put('api/user/update/{id}', $controller_cites.'updateRecommendations');  
Route::put('api/user/citeDelete/{id}', $controller_cites.'updateStatusToDelete');
Route::post('api/user/cite/upload', $controller_cites.'uploadFile');    
// rutas de familiares del usuario
Route::post('api/user/family', $controller_family.'register');
Route::get('api/user/familys', $controller_family.'familys');
Route::get('api/user/family/{id}', $controller_family.'family');
Route::put('api/user/family/{id}', $controller_family."update");

// rutas de notifications
// rutas del usuario referente a notificacion
Route::get('api/user/notifications', $controller_notification.'getNotifications');
Route::delete('api/user/notification/{id}', $controller_notification.'deleteNotification');


// rutas del admin
// ruta de actualizacion de datos del admin
Route::put('api/admin/profile/{id}', $controller_employee.'updateAdmin');

// rutas con creacion de empleados
Route::post('api/employee/login', $controller_employee.'login');
Route::post('api/admin/register', $controller_employee.'register');
Route::get('api/admin/employees', $controller_employee.'employees');
Route::get('api/admin/employee/{id}', $controller_employee.'employee');
Route::put('api/admin/employee/{id}', $controller_employee.'update');
Route::delete('api/admin/employee/{id}', $controller_employee.'delete');

// rutas especialidades
Route::get('api/admin/specialitys', $controller_speciality.'specialitys');
Route::get('api/admin/speciality/{id}', $controller_speciality.'speciality');
Route::get('api/admin/specialitys/{param}', $controller_speciality.'specialityByParam');
Route::post('api/admin/speciality', $controller_speciality.'register');
Route::put('api/admin/speciality/{id}', $controller_speciality.'update');
Route::delete('api/admin/speciality/{id}', $controller_speciality.'delete');

// rutas doctors
Route::get('api/admin/doctors', $controller_doctors.'doctors');
Route::get('api/admin/doctor/{id}', $controller_doctors.'doctor');
Route::post('api/admin/doctor', $controller_doctors.'register');
Route::put('api/admin/doctor/{id}', $controller_doctors.'update');
Route::delete('api/admin/doctor/{id}', $controller_doctors.'delete');

// rutas de empleados
// cites
Route::put('api/employee/update/{id}', $controller_employee.'updateEmployee');

Route::get('api/employee/notifications', $controller_notification.'getNotificationsEmployee');

Route::get('api/employee/cites', $controller_cites.'cites');
Route::get('api/employee/cite/{id}', $controller_cites.'cite');
Route::put('api/employee/cite/{id}', $controller_cites.'update');
Route::delete('api/employee/notification/{id}', $controller_notification.'deleteNotificationEmployee');
Route::get('api/employee/status/{id}', $controller_status.'getStatusById');
Route::get('api/employee/doctor/{params}', $controller_doctors.'getDoctorByParams');
Route::get('api/employee/doctors/{id}', $controller_doctors.'getDoctorBySpeciality');
Route::get('api/employee/searchCites/{params}', $controller_cites.'getSearchByParameters');
Route::put('api/employee/requiredFiles/{id}', $controller_cites.'requiredFiles');
Route::get('api/employee/getFile/{filename}', $controller_cites.'getFile');
Route::delete('api/employee/deleteCite/{id}', $controller_cites.'delete');
