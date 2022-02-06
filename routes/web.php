<?php

use App\Http\Controllers\MonitoreController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PatternController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BotController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MonitoreController::class,'index']);

Route::get('/test-search', [HistoryController::class,'testSearch']);

Route::get('/monitore', [MonitoreController::class,'index'])->name('monitoring');

Route::get('/history', [HistoryController::class,'History'])->name('showHistory');

Route::get('/signals', [HistoryController::class,'signals'])->name('showSignals');

Route::get('/patterns', [PatternController::class,'patterns'])->name('showPatterns');

Route::get('/binanceTickers', [MonitoreController::class,'takeAllBinanceTickersFromApi']);



Route::post('/load-profile', [ProfileController::class,'load_profile'])->name('loadProfile');

Route::post('/delete-profile', [ProfileController::class,'delete_profile'])->name('deleteProfile');

Route::post('/edit-profile', [ProfileController::class,'change_profile'])->name('editProfile');

Route::post('/add-profile', [ProfileController::class,'add_profile'])->name('addProfile');



Route::post('/search',[MonitoreController::class,'takeTickersListInHTML']);

Route::post('/updateFollow', [MonitoreController::class,'updateFollowInDB']);

Route::post('/deleteFollow', [MonitoreController::class,'deleteFollowInDB']);

Route::post('/saveFollow', [MonitoreController::class,'saveFollowInDB']);

Route::get('/saveReloadFollow', [MonitoreController::class, 'saveReloadFollow']);

Route::get('/checkReloadFollow', [MonitoreController::class, 'checkReloadFollow']);

Route::post('/get-all-tickers', [MonitoreController::class,'getTickersInJSON'])->name('getTickersInJSON');



Route::post('/historySearch', [HistoryController::class,'historySearch']);

Route::post('/writeDataHistory', [HistoryController::class,'writeDataHistory']);

Route::post('/historyDelete', [HistoryController::class,'deleteDataHistory']);



Route::post('/writePattern', [PatternController::class,'writePatternSignal']);

Route::post('/delete-patterns', [PatternController::class,'deletePatterns'])->name('deletePatterns');



Route::post('/botVolumeListen', [BotController::class,'runVolume']);

Route::post('/botPercentsListen', [BotController::class,'runPercents']);

Route::post('/botAllPairsPercentsListen', [BotController::class,'runAllPairsPercents']);

Route::get('/botInit', [BotController::class,'registerUrlInTelegram']);



Route::post('/processingToggler', [MonitoreController::class, 'serverProcessingToggler']);

