<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserManageController;
use App\Http\Controllers\author\AuthorPostController;
use App\Http\Controllers\author\AuthorProfileController;
//Useing Route
Route::get('/', function () {
    return view('welcome');
})->name('homepage');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('auth/login', [LoginController::class,'login'])->name('login.custom');

Route::group(['middleware' => ['auth']], function () {

    //Redirect Dashboard
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/superadmin',[LoginController::class,'superAdminDashboard'])->name('superadmin.dashboard');
        Route::get('/admin',[LoginController::class,'adminDashboard'])->name('admin.dashboard');
        Route::get('/author',[LoginController::class,'authorDashboard'])->name('author.dashboard');
        Route::get('/user',[LoginController::class,'userDashboard'])->name('user.dashboard');
    });


    //Super Admin
    Route::prefix('superadmin')->group(function () {

        //Categories
        Route::resource('category', CategoryController::class);
        Route::get('/category/publish/{id}',[CategoryController::class,'publish'])->name('category.publish');
        Route::get('/category/hide/{id}',[CategoryController::class,'hide'])->name('category.hide');

        //Posts
        Route::resource('post', PostController::class);
        Route::get('/destroy/{id}',[PostController::class,'destroy'])->name('post.delete');
        Route::get('/publish/{id}',[PostController::class,'publish'])->name('post.publish');
        Route::get('/hide/{id}',[PostController::class,'hide'])->name('post.hide');
        Route::post('/content/file',[PostController::class,'fileUpload'])->name('post.content_file');

        //Profile
        Route::prefix('profile')->group(function () {
            Route::resource('user', SuperAdminController::class);
            Route::post('/profile', [SuperAdminController::class,'updateProfile']);
            Route::get('/superadmin/password/change',[SuperAdminController::class,'getPassword'])->name('profile.passChange');
            Route::post('/superadmin/password/change',[SuperAdminController::class,'updatePassword'])->name('password.update');
        });

        //User Manage
        Route::prefix('user')->group(function () {
            Route::resource('manage', UserManageController::class);
            Route::get('/role/manage',[UserManageController::class,'getAllRoles'])->name('role.manage');
            Route::get('/deactive',[UserManageController::class,'getAllDeactiveUsers'])->name('user.deactive');
            Route::get('/change/deactive/{id}',[UserManageController::class,'activeToDeactive'])->name('activeuser.deactive');
            Route::get('/change/active/{id}',[UserManageController::class,'deactiveToActive'])->name('deactiveuser.active');

            //request
            Route::get('/request',[UserManageController::class,'requestUserHandle'])->name('request.user');
            Route::get('/request/show/{id}',[UserManageController::class,'requestDetailsShow'])->name('user.request.show');
            Route::get('/request/accept/{id}',[UserManageController::class,'requestUserAccept'])->name('user.request.accept');
            
                
        });
    });



               //Author Area
               Route :: prefix('author')->group( function () {

                //Author Profile
               Route::get('/profile',[AuthorProfileController::class,'view_profile'])->name('AuthorProfileController.view_profile');
               Route::post('/profile',[AuthorProfileController::class,'save_profile'])->name('AuthorProfileController.save_profile');
               Route::get('/add_post',[AuthorPostController::class,'add_post'])->name('AuthorPostController.add_post');
                
               //Author Post
               Route::get('/edit_post/{id}',[AuthorPostController::class,'get_edit_post'])->name('AuthorPostController.get_edit_post');
               Route::post('/update_post/{id}',[AuthorPostController::class,'update_post'])->name('AuthorPostController.update_post');
               Route::post('/store_new_post',[AuthorPostController::class,'store_new_post'])->name('AuthorPostController.store_new_post');
               Route::get('/view_all_post',[AuthorPostController::class,'all_post_show'])->name('AuthorPostController.all_post_show');
               Route::get('/view_all_unpublished_post',[AuthorPostController::class,'view_all_unpublished_post'])->name('AuthorPostController.view_all_unpublished_post');
               Route::get('/preview/post/{id}',[AuthorPostController::class,'preview'])->name('AuthorPostController.preview');
               Route::delete('/move_to_trash/post/{id}',[AuthorPostController::class,'soft_destroy'])->name('AuthorPostController.soft_destroy');
               Route::get('/all_trash/post',[AuthorPostController::class,'trashed_post_show'])->name('AuthorPostController.trashed_post_show');
            
            
            });
});


//User
Route::get('/user-home', [UserController::class, 'index'])->name('user.home');
Route::get('/single-blog/{id}', [UserController::class, 'singleBlog'])->name('user.single-blog');
