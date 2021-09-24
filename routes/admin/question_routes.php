<?php

Route::prefix('question')->name('question.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\Admin\QuestionController::class, 'index'])->name('list');
    Route::any('/get-questions', [App\Http\Controllers\Admin\QuestionController::class, 'getQuestions'])->name('get_questions');
    Route::any('/store', [App\Http\Controllers\Admin\QuestionController::class, 'store'])->name('store');
    Route::any('/edit/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'edit'])->name('edit');
    Route::any('/update/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'update'])->name('update');
    Route::any('/destroy/{id}', [App\Http\Controllers\Admin\QuestionController::class, 'destroy'])->name('destroy');

});
