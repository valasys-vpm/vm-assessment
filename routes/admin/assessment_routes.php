<?php

Route::prefix('assessment')->name('assessment.')->group(function()
{
    Route::get('/list', [App\Http\Controllers\Admin\AssessmentController::class, 'index'])->name('list');
    Route::get('/{id}/view-assessment', [App\Http\Controllers\Admin\AssessmentController::class, 'show'])->name('show');
    Route::any('/{id}/send-assessment-result', [App\Http\Controllers\Admin\AssessmentController::class, 'sendAssessmentResult'])->name('send_assessment_result');

    Route::any('/get-assessments', [App\Http\Controllers\Admin\AssessmentController::class, 'getAssessments'])->name('get_assessments');
    Route::any('/store', [App\Http\Controllers\Admin\AssessmentController::class, 'store'])->name('store');
    Route::any('/edit/{id}', [App\Http\Controllers\Admin\AssessmentController::class, 'edit'])->name('edit');
    Route::any('/update/{id}', [App\Http\Controllers\Admin\AssessmentController::class, 'update'])->name('update');
    Route::any('/destroy/{id}', [App\Http\Controllers\Admin\AssessmentController::class, 'destroy'])->name('destroy');

});
