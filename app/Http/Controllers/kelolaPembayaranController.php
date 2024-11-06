<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\User;
use App\Models\Project;
use App\Models\Blok;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class kelolaPembayaranController extends Controller
{
    public function index()
    {
        // $projects = Project::all();
        // $bloks = Blok::all();
        // $user = User::all();
        // $boking = Boking::all();
        // $boking = Boking::orderBy('tgl_boking', 'desc')->get();
        return view('pages.admin.kelolaPembayaran', [
            // 'boking' => $boking,
            // 'projects' => $projects,
            // 'bloks' => $bloks,
            // 'user' => $user,
        ]);
    }
}
