<?php

use App\Mail\MessageMail;
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
    Route::get('/', 'FrontController@index')->name('front-home');
    Route::get('/contact','FrontController@getContact')->name('contact');
    Route::get('/gallery','FrontController@getGallery')->name('gallery');
    Route::get('/course_detail/{slug?}','FrontController@getDetail')->name('course-detail');
    Route::get('/gallery/{slug?}','FrontController@directory')->name('directory');
    Route::post('/add_watch_dog', 'FrontController@addWatchDog')->name('add-watch-dog');
});

Route::middleware(['student'])->prefix('/student')->name('student.')->group(function () {
    Route::namespace('Student')->group(function () {
        Route::get('/','StudentController@index')->name('home');
        Route::get('/messages','StudentController@getMessages')->name('messages');
        Route::get('/user_search', 'StudentController@userSearch')->name('user-search');
        Route::get('/reset_password','StudentController@password')->name('password');
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
        Route::get('/messages','TeacherController@getMessages')->name('messages');
        Route::post('/update_active_member', 'TeacherController@updateActive')->name('update-active-member');
        Route::post('/delete_member', 'TeacherController@deleteMember')->name('delete-member');
        Route::post('/set_note', 'TeacherController@setNote')->name('set-note');
        Route::post('/save_album','TeacherController@saveAlbum')->name('save-album');
        Route::post('/update_active_album','TeacherController@updateActiveAlbum')->name('update-active');
        Route::post('/delete_album','TeacherController@deleteAlbum')->name('delete-album');
        Route::post('/store_img','TeacherController@storeImg')->name('store-img');
        Route::post('/delete_img','TeacherController@deleteImg')->name('delete-img');
        Route::post('/delete_imgs','TeacherController@deleteImgs')->name('delete-imgs');
        Route::post('/update_album','TeacherController@updateAlbum')->name('update-album');
        Route::get('/download_album','TeacherController@downloadAlbum')->name('download-album');
        Route::get('/user_search', 'TeacherController@userSearch')->name('user-search');
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
        Route::get('/add_user_form','AdminController@getUsers')->name('add-user');
        Route::get('/detail/{slug?}', 'AdminController@courseDetail' )->name('detail');
        Route::get('/articles', 'AdminController@articles' )->name('articles');
        Route::post('/add_article','AdminController@storeArticle')->name('add-article');
        Route::post('/update_article','AdminController@updateArticle')->name('update-article');
        Route::post('/add_user','AdminController@addUser')->name('add');
        Route::post('/store_course', 'AdminController@storeCourse')->name('store');
        Route::post('/update_course', 'AdminController@updateCourse')->name('update');
        Route::post('/update_delete', 'AdminController@deleteCourse')->name('delete');
        Route::post('/update_active', 'AdminController@updateActive')->name('update-active');
        Route::post('/update_registration', 'AdminController@updateRegistration')->name('update-registration');
    });
});

Route::namespace('Shared')->group(function () {
    Route::post('/reset_password','ResetPassword@passReset')->name('reset');
    Route::post('/change_course','CourseController@changeCourse')->name('change-course');
    Route::post('/send_msg','MessageController@sendMessage')->name('send-message');
    Route::get('/get_selected_msg','MessageController@getMessage')->name('get-message');
    Route::post('/filter_msg','MessageController@filterMessage')->name('get-filtered-msg');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
