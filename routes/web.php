<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Teacher\TeacherController;
use App\Http\Controllers\Admin\AdminController;


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
    return view('front/main');
});
Route::middleware(['student'])->prefix('/student')->name('student.')->group(function () {
    Route::namespace('Student')->group(function () {
        Route::get('/','StudentController@index')->name('home');

    });
});
Route::middleware(['teacher'])->prefix('/teacher')->name('teacher.')->group(function () {
    Route::namespace('Teacher')->group(function () {
        Route::get('/','TeacherController@index')->name('home');

    });
});
Route::middleware(['admin'])->prefix('/admin')->name('admin.')->group(function () {
    Route::namespace('Admin')->group(function () {
        Route::get('/','AdminController@index')->name('home');
        Route::get('/home','AdminController@index')->name('home');
        Route::get('/courses','AdminController@courses')->name('courses');
        Route::get('/members','AdminController@members')->name('members');
        Route::get('/reset_password','AdminController@password')->name('password');
        Route::get('/add_user_form','AdminController@getUsers')->name('add_user');
        Route::get('/detail/{slug?}', 'AdminController@courseDetail' )->name('detail');
        Route::post('/reset_password','AdminController@passReset')->name('reset');
        Route::post('/add_user','AdminController@addUser')->name('add');
        Route::post('/store_course', 'AdminController@storeCourse')->name('store');
        Route::post('/update_course', 'AdminController@updateCourse')->name('update');
        Route::post('/update_delete', 'AdminController@deleteCourse')->name('delete');
        Route::post('/update_active', 'AdminController@updateActive')->name('update-active');
    });
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
