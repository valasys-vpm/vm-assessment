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
    return redirect()->route('login');
});

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['guest'])->group(function (){
    Route::view('/login', 'login')->name('login');
});

Route::any('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware(['web', 'check.admin'])->name('admin.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    //User Management Routes
    include('admin/user_routes.php');

    //Role Management Routes
    include('admin/role_routes.php');
    //Department Management Routes
    include('admin/department_routes.php');
    //Designation Management Routes
    include('admin/designation_routes.php');

    //Category Management Routes
    include('admin/category_routes.php');

    //Assessment Management Routes
    include('admin/assessment_routes.php');

    //Question Management Routes
    include('admin/question_routes.php');

});

Route::middleware(['web', 'check.user'])->name('user.')->group(function (){

    Route::get('/dashboard', [App\Http\Controllers\User\HomeController::class, 'index'])->name('dashboard');

    Route::get('/{id}/start-assessment', [App\Http\Controllers\User\AssessmentController::class, 'startAssessment'])->name('assessment.start_assessment');
    Route::get('/{id}/assessment-test', [App\Http\Controllers\User\AssessmentController::class, 'assessmentLive'])->name('assessment.live');
    Route::get('/{id}/assessment-result', [App\Http\Controllers\User\AssessmentController::class, 'assessmentResult'])->name('assessment.result');

    Route::prefix('assessment')->name('assessment.')->group(function()
    {
        //Route::get('/', [App\Http\Controllers\User\AssessmentController::class, 'index'])->name('list');

        Route::any('/get-categories', [App\Http\Controllers\User\AssessmentController::class, 'getCategories'])->name('get_categories');
        Route::any('/store', [App\Http\Controllers\User\AssessmentController::class, 'store'])->name('store');
        Route::any('/edit/{id}', [App\Http\Controllers\User\AssessmentController::class, 'edit'])->name('edit');
        Route::any('/update/{id}', [App\Http\Controllers\User\AssessmentController::class, 'update'])->name('update');
        Route::any('/destroy/{id}', [App\Http\Controllers\User\AssessmentController::class, 'destroy'])->name('destroy');

        Route::any('/submit', [App\Http\Controllers\User\AssessmentController::class, 'submit'])->name('submit');

    });

});
