<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\PropertyCategoryController;
use App\Http\Controllers\Admin\RequestController;

use App\Http\Controllers\Guest\PropertyGuestController;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserReference;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();


Route::get('/api/employee-number/{employee_number}', function (string $employee_number) {
    return UserResource::collection(UserReference::where('employee_no', $employee_number)->whereNull('user_id')->get());
})->name('api.register.validate');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::middleware('CheckUserRole')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('admin.users');
        Route::put('users/{user_reference}', [UserController::class, 'update'])->where('user_reference', '[0-9]+')->name('admin.users.update');
        Route::get('users/{user_reference}/delete', [UserController::class, 'destroy'])->where('user_reference', '[0-9]+')->name('admin.users.destroy');
        Route::get('users/{id}/restore', [UserController::class, 'restore'])->where('id', '[0-9]+')->name('admin.users.restore');
        
        Route::get('property-categories', [PropertyCategoryController::class, 'index'])->name('admin.property_categories');
        Route::put('property-categories/{property_category}', [PropertyCategoryController::class, 'update'])->where('property_category', '[0-9]+')->name('admin.property_categories.update');
        Route::get('property-categories/{property_category}/delete', [PropertyCategoryController::class, 'destroy'])->where('property_category', '[0-9]+')->name('admin.property_categories.destroy');
        Route::post('property-categories', [PropertyCategoryController::class, 'store'])->name('admin.property_categories.store');
        
        Route::get('properties', [PropertyController::class, 'index'])->name('admin.properties');
        Route::post('properties', [PropertyController::class, 'store'])->name('admin.properties.store');
        Route::get('properties/{property}/dispose', [PropertyController::class, 'dispose'])->where('property', '[0-9]+')->name('admin.properties.dispose');
        Route::get('properties/{property}/restore', [PropertyController::class, 'restore'])->where('property', '[0-9]+')->name('admin.properties.restore');
        Route::get('properties/{property}/delete', [PropertyController::class, 'destroy'])->where('property', '[0-9]+')->name('admin.properties.destroy');
        Route::put('properties/{property}', [PropertyController::class, 'update'])->where('property', '[0-9]+')->name('admin.properties.update');
        Route::post('properties/batch', [PropertyController::class, 'batch'])->name('admin.properties.batch');
    
        Route::get('borrow/pending', [RequestController::class, 'borrow_pending'])->name('admin.borrow.pending');
        Route::delete('borrow/pending', [RequestController::class, 'borrow_pending_destroy'])->name('admin.borrow.pending.destroy');
        Route::post('borrow/pending/approve', [RequestController::class, 'borrow_pending_approve'])->name('admin.borrow.pending.approve');
        Route::post('borrow/pending/decline', [RequestController::class, 'borrow_pending_decline'])->name('admin.borrow.pending.decline');
        
        Route::get('borrow/borrowed', [RequestController::class, 'borrow_borrowed'])->name('admin.borrow.borrowed');
        Route::post('borrow/borrowed/return', [RequestController::class, 'borrow_borrowed_return'])->name('admin.borrow.borrowed.return');
        Route::get('borrow/history', [RequestController::class, 'borrow_history'])->name('admin.borrow.history');
        Route::get('borrow/rejected', [RequestController::class, 'borrow_rejected'])->name('admin.borrow.rejected');
        Route::post('borrow/rejected/restore', [RequestController::class, 'borrow_restore'])->name('admin.borrow.rejected.restore');
        
        Route::get('purchase/pending', [RequestController::class, 'purchase_pending'])->name('admin.purchase.pending');
        Route::delete('purchase/pending', [RequestController::class, 'purchase_pending_destroy'])->name('admin.purchase.pending.destroy');
        Route::post('purchase/pending/approve', [RequestController::class, 'purchase_pending_approve'])->name('admin.purchase.pending.approve');
        Route::post('purchase/pending/decline', [RequestController::class, 'purchase_pending_decline'])->name('admin.purchase.pending.decline');
        
        Route::get('purchase/history', [RequestController::class, 'purchase_history'])->name('admin.purchase.history');
        Route::get('purchase/rejected', [RequestController::class, 'purchase_rejected'])->name('admin.purchase.rejected');
        Route::post('purchase/rejected/restore', [RequestController::class, 'purchase_restore'])->name('admin.purchase.rejected.restore');
    });
    
    Route::get('guest/properties', [PropertyGuestController::class, 'index'])->name('guest.properties');
    Route::post('guest/properties/borrow', [PropertyGuestController::class, 'borrow'])->name('guest.properties.borrow');
    Route::post('guest/properties/purchase', [PropertyGuestController::class, 'purchase'])->name('guest.properties.purchase');
    
    Route::get('guest/borrow/pending', [PropertyGuestController::class, 'borrow_pending'])->name('guest.borrow.pending');
    Route::delete('guest/borrow/pending', [PropertyGuestController::class, 'borrow_pending_destroy'])->name('guest.borrow.pending.destroy');
    Route::get('guest/borrow/history', [PropertyGuestController::class, 'borrow_history'])->name('guest.borrow.history');
    
    Route::get('guest/purchase/pending', [PropertyGuestController::class, 'purchase_pending'])->name('guest.purchase.pending');
    Route::delete('guest/purchase/pending', [PropertyGuestController::class, 'purchase_pending_destroy'])->name('guest.purchase.pending.destroy');
    Route::get('guest/purchase/history', [PropertyGuestController::class, 'purchase_history'])->name('guest.purchase.history');
    Route::put('guest/purchase/{property_purchase}', [PropertyGuestController::class, 'purchase_update'])->where('property_purchase', '[0-9]+')->name('guest.purchase.update');
});