<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KelolaKonsumenController extends Controller
{
    public function index () {
        return view('pages.admin.kelolaKonsumen');
    }
}
