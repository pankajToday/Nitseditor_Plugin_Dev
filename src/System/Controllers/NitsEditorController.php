<?php

namespace Nitseditor\System\Controllers;


use App\Http\Controllers\Controller;

class NitsEditorController extends Controller
{
    public function index()
    {
        return view('NitsEditor::index');
    }
}