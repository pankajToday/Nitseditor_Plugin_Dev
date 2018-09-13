<?php

namespace Nitseditor\System\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class NitsEditorController extends Controller
{
    public function index()
    {
        return view('NitsEditor::index');
    }

}