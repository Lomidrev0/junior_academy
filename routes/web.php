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




Route::namespace('Front')->group(function () {
    Route::get('/', 'FrontController@index');
    Route::get('/contact','FrontController@getContact');
    Route::get('/course_detail/{slug?}','FrontController@getDetail')->name('course-detail');
});

Route::middleware(['student'])->prefix('/student')->name('student.')->group(function () {
    Route::namespace('Student')->group(function () {
        Route::get('/','StudentController@index')->name('home');
    });
});
/*teacher routes*/

Route::middleware(['teacher'])->prefix('/teacher')->name('teacher.')->group(function () {
    Route::namespace('Teacher')->group(function () {
        Route::get('/','TeacherController@index')->name('home');
        Route::get('/members','TeacherController@members')->name('members');
        Route::get('/gallery', 'TeacherController@gallery')->name('gallery');
        Route::get('/reset_password','TeacherController@password')->name('password');
        Route::get('/gallery/{slug?}','TeacherController@directory')->name('directory');
        Route::post('/update_active_member', 'TeacherController@updateActive')->name('update-active-member');
        Route::post('/delete_member', 'TeacherController@deleteMember')->name('delete-member');
        Route::post('/set_note', 'TeacherController@setNote')->name('set-note');
        Route::post('/save_album','TeacherController@saveAlbum')->name('save-album');
        Route::post('/update_active_album','TeacherController@updateActiveAlbum')->name('update-active');
        Route::post('/delete_album','TeacherController@deleteAlbum')->name('delete-album');

    });
});

/*admin routes*/

Route::middleware(['admin'])->prefix('/admin')->name('admin.')->group(function () {
    Route::namespace('Admin')->group(function () {
        Route::get('/','AdminController@index')->name('home');
        Route::get('/home','AdminController@index')->name('home');
        Route::get('/courses','AdminController@courses')->name('courses');
        Route::get('/members','AdminController@members')->name('members');
        Route::get('/reset_password','AdminController@password')->name('password');
        Route::get('/add_user_form','AdminController@getUsers')->name('add_user');
        Route::get('/detail/{slug?}', 'AdminController@courseDetail' )->name('detail');
        Route::post('/add_user','AdminController@addUser')->name('add');
        Route::post('/store_course', 'AdminController@storeCourse')->name('store');
        Route::post('/update_course', 'AdminController@updateCourse')->name('update');
        Route::post('/update_delete', 'AdminController@deleteCourse')->name('delete');
        Route::post('/update_active', 'AdminController@updateActive')->name('update-active');
    });
});

Route::namespace('Shared')->group(function () {
    Route::post('/reset_password','ResetPassword@passReset')->name('reset');
    Route::post('/change_course','CourseController@changeCourse')->name('change-course');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
