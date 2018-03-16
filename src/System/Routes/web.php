<?php


Route::get('/{any}', ['as' => 'NitsEditor', 'uses' => 'NitsEditorController@index']);