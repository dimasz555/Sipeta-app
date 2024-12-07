<?php

namespace App\Exports\Sheets;

use App\Models\Boking;
use App\Models\Pembelian;
use App\Models\Cicilan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class BokingSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
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
            'pembelian.cicilans' => function ($query) {
                $query->orderBy('tahun', 'asc')
                    ->orderBy('bulan', 'asc');
            }
        ])
            ->where('project_id', $this->projectId)
            ->where('blok_id', $this->blokId)
            ->whereHas('pembelian')
            ->get();

        $result = [];
        $no = 1;

        foreach ($data as $boking) {
            $pembelian = $boking->pembelian;

            $totalBulanDibayar = $pembelian ? $pembelian->cicilans()
                ->where('status', 'lunas')
                ->count() : 0;

            $dp = $boking->harga_boking + ($pembelian ? $pembelian->dp : 0);
            $cicilans = $pembelian ? $pembelian->cicilans()->sum('harga_cicilan') : 0;
            $sisa = $cicilans - $dp;

            $row = [
                'No' => $no++,
                'Nama Pembeli' => $boking->user->name,
                'Nomor Blok' => $boking->no_blok,
                'Harga Beli' => $pembelian ? 'Rp ' . number_format($pembelian->harga, 0, ',', '.') : 'Rp 0',
                'Tanggal Pembelian' => $pembelian
                    ? Carbon::parse($pembelian->tgl_pembelian)->translatedFormat('j F Y')
                    : '-',
                'Jumlah Total Bulan Pembayaran' => $pembelian ? $pembelian->jumlah_bulan_cicilan : '0',
                'Total Perbulan yang Sudah Dibayar' => $totalBulanDibayar > 0 ? $totalBulanDibayar : 0,
                'Jumlah Cicilan Perbulan' => $pembelian ? 'Rp ' . number_format($pembelian->harga_cicilan_perbulan, 0, ',', '.') : 'Rp 0',
                'Sisa' => $pembelian
                    ? ($pembelian->jumlah_bulan_cicilan - $pembelian->cicilans->where('status', 'lunas')->count())
                    : '0',
                'No HP' => $boking->user->phone,
            ];

            // Tahun dan bulan cicilan
            if ($pembelian) {
                $tahunMin = Cicilan::min('tahun');
                $tahunMax = Cicilan::max('tahun');

                for ($tahun = $tahunMin; $tahun <= $tahunMax; $tahun++) {
                    foreach (range(1, 12) as $bulan) {
                        $cicilan = $pembelian->cicilans
                            ->where('tahun', $tahun)
                            ->where('bulan', $bulan)
                            ->first();
                        if ($cicilan) {
                            $row["{$tahun}_{$bulan}"] = $cicilan->status === 'lunas' ? 'Rp ' . number_format($cicilan->harga_cicilan, 0, ',', '.') : 'Belum Dibayar';
                        } else {
                            $row["{$tahun}_{$bulan}"] = '-';
                        }
                    }
                }
            }
            $row['DP'] = 'Rp ' . number_format($dp, 0, ',', '.');
            $row['CICILAN'] = 'Rp ' . number_format($cicilans, 0, ',', '.');
            $row['SISA'] = 'Rp ' . number_format($sisa, 0, ',', '.');

            $result[] = $row;
        }

        return collect($result);  // Mengembalikan koleksi untuk ekspor ke Excel
    }

    // Helper untuk mendapatkan nama kolom Excel berdasarkan indeks
    private function getColumnName($index)
    {
        $letters = '';
        while ($index > 0) {
            $index--;
            $letters = chr($index % 26 + 65) . $letters;
            $index = (int)($index / 26);
        }
        return $letters;
    }

    public function headings(): array
    {
        // Header dasar pada baris pertama
        $headersRow1 = [
            'No',
            'Nama Pembeli',
            'Nomor Blok',
            'Harga Beli',
            'Tanggal Pembelian',
            'Jumlah Total Bulan Pembayaran',
            'Total Perbulan yang Sudah Dibayar',
            'Jumlah Cicilan Perbulan',
            'Sisa',
            'No HP',
        ];

        // Baris kedua untuk header dasar (kosong karena di-merge)
        $headersRow2 = array_fill(0, count($headersRow1), '');

        // Ambil rentang tahun dari tabel cicilan
        $tahunMin = \App\Models\Cicilan::min('tahun');
        $tahunMax = \App\Models\Cicilan::max('tahun');

        // Header tahun dan bulan
        $tahunHeader = [];
        $bulanHeader = [];

        for ($tahun = $tahunMin; $tahun <= $tahunMax; $tahun++) {
            // Tambahkan header tahun untuk baris pertama (dimerge)
            $tahunHeader[] = $tahun;

            // Tambahkan nama bulan untuk setiap tahun di baris kedua
            $bulanHeader = array_merge($bulanHeader, [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ]);
        }

        // Gabungkan header dasar dengan tahun dan bulan
        $headersRow1 = array_merge($headersRow1, $tahunHeader);
        $headersRow2 = array_merge($headersRow2, $bulanHeader);


        // Tambahkan header DP, CICILAN, SISA setelah kolom tahun dan bulan
        $headersRow1 = array_merge($headersRow1, ['DP', 'CICILAN', 'SISA']);
        $headersRow2 = array_merge($headersRow2, ['', '', '']);

        return [$headersRow1, $headersRow2];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge header utama (A1: A2, B1: B2, ... )
                $mergeColumns = range('A', 'J'); // Sesuaikan kolom awal
                foreach ($mergeColumns as $column) {
                    $sheet->mergeCells("{$column}1:{$column}2");
                }

                // Merge tahun (misalnya, K1:V1 untuk 12 bulan)
                $startIndex = 11; // Indeks untuk kolom K
                $tahunMin = \App\Models\Cicilan::min('tahun');
                $tahunMax = \App\Models\Cicilan::max('tahun');

                for ($tahun = $tahunMin; $tahun <= $tahunMax; $tahun++) {
                    $startColumn = $this->getColumnName($startIndex);
                    $endColumn = $this->getColumnName($startIndex + 11); // 12 bulan
                    $sheet->mergeCells("{$startColumn}1:{$endColumn}1");
                    $sheet->setCellValue("{$startColumn}1", $tahun);
                    $startIndex += 12; // Lanjutkan ke tahun berikutnya
                }

                // Styling untuk header
                $highestColumn = $this->getColumnName($startIndex - 1); // Kolom terakhir
                $headerRange = "A1:{$highestColumn}2";

                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '01FFFF',
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Mendapatkan jumlah baris data yang diekspor
                $rowCount = count($this->collection()) + 2; // Menambahkan 2 untuk header

                // Styling untuk data (seluruh tabel)
                $dataRange = "A3:{$highestColumn}{$rowCount}"; // Seluruh data
                $sheet->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Ambil data original sebelum transformasi
                $bokings = Boking::with(['pembelian'])
                    ->where('project_id', $this->projectId)
                    ->where('blok_id', $this->blokId)
                    ->whereHas('pembelian')
                    ->get();


                // Loop melalui data original
                $no = 1;
                foreach ($bokings as $boking) {
                    if ($boking->pembelian && $boking->pembelian->status === 'batal') {
                        $rowIndex = $no + 2;
                        $rowRange = "A{$rowIndex}:{$highestColumn}{$rowIndex}";

                        $sheet->getStyle($rowRange)->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => 'FFFF0000',
                                ],
                            ],
                        ]);

                        // teks putih agar terlihat
                        $sheet->getStyle($rowRange)->applyFromArray([
                            'font' => [
                                'color' => [
                                    'argb' => 'FFFFFFFF',
                                ],
                            ],
                        ]);
                    }
                    $no++;
                }

                // Hitung posisi kolom untuk DP, CICILAN, SISA
                $startIndex = 11; // Kolom K
                $tahunCount = $tahunMax - $tahunMin + 1;
                $totalMonthColumns = $tahunCount * 12;

                $dpColumnIndex = $startIndex + $totalMonthColumns;
                $cicilanColumnIndex = $dpColumnIndex + 1;
                $sisaColumnIndex = $cicilanColumnIndex + 1;

                // Merge dan style untuk DP
                $dpColumn = $this->getColumnName($dpColumnIndex);
                $sheet->mergeCells("{$dpColumn}1:{$dpColumn}2");
                $sheet->setCellValue("{$dpColumn}1", "DP"); // Menambahkan teks DP
                $sheet->getStyle("{$dpColumn}1:{$dpColumn}2")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FF0000',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Merge dan style untuk CICILAN
                $cicilanColumn = $this->getColumnName($cicilanColumnIndex);
                $sheet->mergeCells("{$cicilanColumn}1:{$cicilanColumn}2");
                $sheet->setCellValue("{$cicilanColumn}1", "CICILAN"); // Menambahkan teks CICILAN
                $sheet->getStyle("{$cicilanColumn}1:{$cicilanColumn}2")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FF0000',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Merge dan style untuk SISA
                $sisaColumn = $this->getColumnName($sisaColumnIndex);
                $sheet->mergeCells("{$sisaColumn}1:{$sisaColumn}2");
                $sheet->setCellValue("{$sisaColumn}1", "SISA"); // Menambahkan teks SISA
                $sheet->getStyle("{$sisaColumn}1:{$sisaColumn}2")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'FF0000',
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Styling untuk data di kolom DP, CICILAN, dan SISA
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("{$dpColumn}3:{$dpColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getStyle("{$cicilanColumn}3:{$cicilanColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $sheet->getStyle("{$sisaColumn}3:{$sisaColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
