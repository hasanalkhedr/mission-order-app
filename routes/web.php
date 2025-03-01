<?php

use App\Http\Controllers\BaremeController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TourneeApproveController;
use App\Http\Controllers\TourneeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MissionOrderController;
use App\Http\Controllers\MissionApproveController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TourneeExpenseController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use Livewire\Livewire;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Home route, which redirects based on user roles
Route::get('/', function () {
    if (auth()->check()) {
        /*$role = auth()->user()->employee->role;

        switch ($role) {
            case 'employee':
            case 'supervisor':
                return redirect()->route('mission_orders.index');
            case 'hr':
                return redirect()->route('mission_orders.hrIndex');
            case 'sg':
                return redirect()->route('mission_orders.sgIndex');
            default:
                return redirect()->route('login');
        }*/
        return redirect()->route('mission_orders.index');
    } else {
        return redirect()->route('login');
    }
})->middleware('auth');


Route::middleware('web')->group(function() {


// Mission Order routes
Route::get('/mission_orders/{missionOrder}/report', [MissionOrderController::class, 'showReport'])->middleware('auth')->name('mission_orders.report');
Route::get('mission_orders/m_index', [MissionOrderController::class, 'm_index'])->middleware('auth')->name('mission_orders.m_index');
Route::get('mission_orders/{missionOrder}/m_show', [MissionOrderController::class, 'm_show'])->middleware('auth')->name('mission_orders.m_show');
Route::get('mission_orders/{missionOrder}/m_create', [MissionOrderController::class, 'm_create'])->middleware('auth')->name('mission_orders.m_create');
Route::get('mission_orders/{missionOrder}/m_edit', [MissionOrderController::class, 'm_edit'])->middleware('auth')->name('mission_orders.m_edit');
Route::put('mission_orders/{missionOrder}/m_update', [MissionOrderController::class, 'm_update'])->middleware('auth')->name('mission_orders.m_update');
Route::get('mission_orders/{missionOrder}/m_report', [MissionOrderController::class, 'm_report'])->middleware('auth')->name('mission_orders.m_report');
Route::put('mission_orders/{missionOrder}/m_destroy', [MissionOrderController::class, 'm_destroy'])->middleware('auth')->name('mission_orders.m_destroy');
Route::put('/mission_orders/{missionOrder}/changeDates', [MissionOrderController::class, 'changeDates'])->middleware(['auth', 'role:hr'])->name('mission_orders.changeDates');
Route::resource('mission_orders', MissionOrderController::class)->middleware('auth');

// Tournee routes
Route::get('/tournees/{tournee}/report', [TourneeController::class, 'showReport'])->middleware('auth')->name('tournees.report');
Route::get('tournees/m_index', [TourneeController::class, 'm_index'])->middleware('auth')->name('tournees.m_index');
Route::get('tournees/{tournee}/m_show', [TourneeController::class, 'm_show'])->middleware('auth')->name('tournees.m_show');
Route::get('tournees/{tournee}/m_create', [TourneeController::class, 'm_create'])->middleware('auth')->name('tournees.m_create');
Route::get('tournees/{tournee}/m_edit', [TourneeController::class, 'm_edit'])->middleware('auth')->name('tournees.m_edit');
Route::put('tournees/{tournee}/m_update', [TourneeController::class, 'm_update'])->middleware('auth')->name('tournees.m_update');
Route::get('tournees/{tournee}/m_report', [TourneeController::class, 'm_report'])->middleware('auth')->name('tournees.m_report');
Route::put('tournees/{tournee}/m_destroy', [TourneeController::class, 'm_destroy'])->middleware('auth')->name('tournees.m_destroy');
Route::put('/tournees/{tournee}/changeDates', [TourneeController::class, 'changeDates'])->middleware(['auth', 'role:hr'])->name('tournees.changeDates');
Route::resource('tournees', TourneeController::class)->middleware('auth');

// Mission Approves routes
Route::post('mission_approves/{missionOrder}/approve', [MissionApproveController::class, 'approve'])->middleware('auth')->name('mission_approves.approve');
Route::post('mission_approves/{missionOrder}/m_approve', [MissionApproveController::class, 'm_approve'])->middleware('auth')->name('mission_approves.m_approve');

// Tournee Approves routes
Route::post('tournee_approves/{tournee}/approve', [TourneeApproveController::class, 'approve'])->middleware('auth')->name('tournee_approves.approve');
Route::post('tournee_approves/{tournee}/m_approve', [TourneeApproveController::class, 'm_approve'])->middleware('auth')->name('tournee_approves.m_approve');

// Expenses routes
Route::resource('expenses', ExpenseController::class)->middleware('auth');
Route::get('/expenses/{expense}/download', [ExpenseController::class, 'download_document'])->name('expenses.download_document');

// Tournee Expenses routes
Route::resource('tournee_expenses', TourneeExpenseController::class)->middleware('auth');
Route::get('/tournee_expenses/{expense}/download', [TourneeExpenseController::class, 'download_document'])->name('tournee_expenses.download_document');

// Employees routes (restricted to HR only)
Route::put('employees/updatePassword/{employee}', [EmployeeController::class, 'updatePassword'])->middleware('auth')->name('employees.updatePassword');
Route::resource('employees', EmployeeController::class)->middleware('auth');

// Departments routes (could be accessible to all roles depending on the policy)
Route::resource('departments', DepartmentController::class)->middleware(['auth','role:hr,sg']);

// Baremes routes
Route::resource('baremes', BaremeController::class)->middleware(['auth', 'role:hr,sg,supervisor']);
});

Route::get('/calendar',CalendarController::class)->middleware('auth')->name('calendar');
Route::get('/reports', [ReportsController::class, 'index'])->middleware('auth')->name('reports.index');
Route::post('/reports', [ReportsController::class, 'generateReport'])->middleware('auth')->name('reports.generateReport');
// Authentication routes (Laravel Breeze)
require __DIR__ . '/auth.php';

//Livewire::setUpdateRoute();
Route::get('/{anything}', function(){
    return view('errors.404');
});
