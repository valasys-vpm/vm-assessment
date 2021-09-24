<?php

Route::prefix('category')->name('category.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('list');
    Route::any('/get-categories', [App\Http\Controllers\Admin\CategoryController::class, 'getCategories'])->name('get_categories');
    Route::any('/store', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('store');
    Route::any('/edit/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('edit');
    Route::any('/update/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
    Route::any('/destroy/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('destroy');

});
