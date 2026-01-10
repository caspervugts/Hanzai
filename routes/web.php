<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CrimeController;
use App\Http\Controllers\GamblingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\GangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatRoomController;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard', [ProfileController::class, 'view']);
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'view'])->name('dashboard');

    Route::get('/crime', [CrimeController::class, 'view'])->name('crime');    
    Route::get('/jail')->name('jail');
    Route::get('/crime/result', [CrimeController::class, 'result'])->name('crimeResult');

    Route::get('/crime/1/{whichCrime}', [CrimeController::class, 'performRobbery'])->name('performRobbery');
    Route::get('/crime/2/{whichCrime}', [CrimeController::class, 'performCarTheft'])->name('performCarTheft');

    Route::get('/gang', [GangController::class, 'view'])->name('gang');    
    Route::post('/gang/create', [GangController::class, 'createGang'])->name('createGang');
    Route::get('/gang/apply/{gangId}', [GangController::class, 'applyToGang'])->name('applyToGang');
    Route::get('/gang/leave/{gangId}', [GangController::class, 'leaveGang'])->name('leaveGang');
    Route::get('/gang/approve/{userId}', [GangController::class, 'approveApplication'])->name('approveApplication');    
    Route::get('/gang/reject/{userId}', [GangController::class, 'rejectApplication'])->name('rejectApplication');

    Route::get('/gambling', [GamblingController::class, 'view'])->name('gambling');    
    Route::get('/gambling/race', [GamblingController::class, 'createRace']);    
    Route::post('/gambling/placebet/{horseId}', [GamblingController::class, 'placeBet']);    

    Route::get('/leaderboard', [ProfileController::class, 'leaderboard'])->name('leaderboard');  
    
    Route::get('/chat/messages', [ChatRoomController::class, 'view']);
    Route::post('/chat/messages', [ChatRoomController::class, 'store']);
    
    Route::get('/garage', [ProfileController::class, 'viewGarage'])->name('garage');
    Route::get('/shop', [ShopController::class, 'viewShop'])->name('shop');
    Route::get('/shop/buy/{weaponId}/{value}', [ShopController::class, 'buyWeapon'])->name('buyWeapon');
    Route::get('/garage/sell/{carId}/{value}', [ProfileController::class, 'sellCar'])->name('sellCar');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/crimes/truncate', function () {
        DB::table('crimes_performed')->truncate();
        return response()->json(['ok' => true, 'message' => 'Table truncated']);
    });
});

require __DIR__.'/auth.php';
