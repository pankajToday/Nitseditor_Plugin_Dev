<?php


Route::get('/', ['as' => 'NitsEditor', 'uses' => 'NitsEditorController@index']);
Route::get('/{any}', ['as' => 'NitsEditor', 'uses' => 'NitsEditorController@index'])->where('any', '.*');