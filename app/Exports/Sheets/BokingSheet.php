<?php

namespace App\Exports\Sheets;

use App\Models\Boking;
use App\Models\Pembelian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BokingSheet implements FromCollection, WithHeadings, WithTitle
{
    protected $projectId;
    protected $blokId;
    protected $sheetName;

    public function __construct($projectId, $blokId, $sheetName)
    {
        $this->projectId = $projectId;
        $this->blokId = $blokId;
        $this->sheetName = $sheetName;
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function collection()
    {
        $data = Boking::with([
            'user', 
            'pembelian.cicilans' => function($query) {
                $query->orderBy('no_cicilan', 'asc');
            }, 
            'project', 
            'blok'
        ])
            ->where('project_id', $this->projectId)
            ->where('blok_id', $this->blokId)
            ->get();

        $result = [];
        foreach ($data as $boking) {
            $pembelian = $boking->pembelian;
            
            // Buat array dasar untuk data konsumen
            $row = [
                'Nama Konsumen' => $boking->user->name,
                'Username' => $boking->user->username,
                'No HP' => $boking->user->phone,
                'Gender' => $boking->user->gender,
                'No Blok' => $boking->no_blok,
                'Tanggal Boking' => $boking->tgl_boking,
                'Harga Boking' => $boking->harga_boking,
                'DP' => $pembelian ? $pembelian->dp : '-',
                'Jumlah Cicilan' => $pembelian ? $pembelian->jumlah_bulan_cicilan : '-',
                'Harga Cicilan Per Bulan' => $pembelian ? $pembelian->harga_cicilan_perbulan : '-',
                'Status Pembelian' => $pembelian ? $pembelian->status : 'Belum Lunas',
            ];

            // Tambahkan data cicilan
            if ($pembelian && $pembelian->cicilans->count() > 0) {
                foreach ($pembelian->cicilans as $cicilan) {
                    // Format tanggal bayar
                    $tglBayar = $cicilan->tgl_bayar ? $cicilan->tgl_bayar->format('Y-m-d') : '-';
                    
                    $row["Cicilan {$cicilan->no_cicilan} - No Transaksi"] = $cicilan->no_transaksi;
                    $row["Cicilan {$cicilan->no_cicilan} - Nominal"] = $cicilan->harga_cicilan;
                    $row["Cicilan {$cicilan->no_cicilan} - Tanggal Bayar"] = $tglBayar;
                    $row["Cicilan {$cicilan->no_cicilan} - Bulan"] = $cicilan->bulan;
                    $row["Cicilan {$cicilan->no_cicilan} - Tahun"] = $cicilan->tahun;
                    $row["Cicilan {$cicilan->no_cicilan} - Status"] = $cicilan->status;
                }
            }

            $result[] = $row;
        }

        return collect($result);
    }

    public function headings(): array
    {
        // Dasar headers
        $headers = [
            'Nama Konsumen',
            'Username',
            'No HP',
            'Gender',
            'No Blok',
            'Tanggal Boking',
            'Harga Boking',
            'DP',
            'Jumlah Cicilan',
            'Harga Cicilan Per Bulan',
            'Status Pembelian',
        ];

        // Tambahkan headers untuk cicilan
        // Asumsikan maksimal 36 cicilan (bisa disesuaikan)
        for ($i = 1; $i <= 36; $i++) {
            $headers[] = "Cicilan {$i} - No Transaksi";
            $headers[] = "Cicilan {$i} - Nominal";
            $headers[] = "Cicilan {$i} - Tanggal Bayar";
            $headers[] = "Cicilan {$i} - Bulan";
            $headers[] = "Cicilan {$i} - Tahun";
            $headers[] = "Cicilan {$i} - Status";
        }

        return $headers;
    }
}