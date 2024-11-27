<?php

namespace App\Exports;

use App\Models\Boking;
use App\Models\Blok;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\BokingSheet;

class ProjectLaporanMultipleSheetsExport implements WithMultipleSheets
{
    use Exportable;

    protected $projectId;

    public function __construct($projectId)
    {
        if (is_object($projectId)) {
            if ($projectId instanceof \Illuminate\Database\Eloquent\Collection) {
                $this->projectId = $projectId->first()->project_id;
            } else {
                $this->projectId = $projectId->id;
            }
        } else {
            $this->projectId = (int) $projectId;
        }
    }

    public function sheets(): array
    {
        $bloks = Blok::whereHas('bokings', function ($query) {
            $query->where('project_id', $this->projectId);
        })->get();

        $sheets = [];

        foreach ($bloks as $blok) {
            $sheets[] = new BokingSheet($this->projectId, $blok->id, "Blok {$blok->blok}");
        }

        return $sheets;
    }
}
