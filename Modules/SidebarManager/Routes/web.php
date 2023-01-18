<?php

use Illuminate\Support\Facades\Route;

Route::prefix('sidebar-manager')->middleware(['auth','seller'])->group(function () {
    Route::get('/index', 'SidebarManagerController@index')->name('sidebar-manager.index');
    //section store
    Route::post('/section/store', 'SidebarManagerController@sectionStore')->name('sidebar-manager.section.store')->middleware('prohibited_demo_mode');
    Route::post('/section/menu-update', 'SidebarManagerController@menuUpdate')->name('sidebar-manager.menu-update')->middleware('prohibited_demo_mode');
    Route::post('/section/add-to-menu', 'SidebarManagerController@addToMenu')->name('sidebar-manager.add-to-menu')->middleware('prohibited_demo_mode');
    Route::post('/section/sort-section', 'SidebarManagerController@sortSection')->name('sidebar-manager.sort-section')->middleware('prohibited_demo_mode');
    Route::post('/section/delete-section', 'SidebarManagerController@deleteSection')->name('sidebar-manager.delete-section')->middleware('prohibited_demo_mode');
    Route::post('/section/reset-own-menu', 'SidebarManagerController@resetMenu')->name('sidebar-manager.reset-own-menu')->middleware('prohibited_demo_mode');
});
