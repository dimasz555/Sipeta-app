<?php

namespace App\Http\Controllers;

use App\Models\Boking;
use App\Models\Project;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectLaporanMultipleSheetsExport;

class LaporanController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view('pages.admin.laporan', [
            'projects' => $projects,
        ]);
    }

    public function exportToExcel($projectId)
    {
        // Menyaring data berdasarkan project ID
        $project = Project::findOrFail($projectId);
        
        // Debug untuk melihat nilai projectId
        // dd($projectId);

        // Pastikan projectId adalah integer
        $projectId = (int) $projectId;

        return Excel::download(
            new ProjectLaporanMultipleSheetsExport($projectId), 
            "laporan_{$project->name}.xlsx"
        );
    }
}