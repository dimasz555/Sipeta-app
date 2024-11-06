<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\User;
use App\Models\Project;
use App\Models\Blok;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;


class BokingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $boking = Boking::all();
        $boking = $user->bokings()->orderBy('tgl_boking', 'desc')->get();
        return view('pages.konsumen.boking', [
            'boking' => $boking,
            'user' => $user,
        ]);
    }
}
