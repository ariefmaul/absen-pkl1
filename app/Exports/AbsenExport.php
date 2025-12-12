<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsenExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {

            // Hitung total waktu kerja
            if ($item->check_in && $item->check_out) {
                $in = strtotime($item->check_in);
                $out = strtotime($item->check_out);
                $diff = $out - $in;

                $hours = floor($diff / 3600);
                $minutes = floor(($diff % 3600) / 60);

                $totalTime = sprintf('%02d jam %02d menit', $hours, $minutes);
            } else {
                $totalTime = '-';
            }

            // Data export
            return [
                'Nama User'     => $item->user->name,
                'Tanggal'       => $item->scanned_at,
                'Masuk'         => $item->check_in,
                'Keluar'        => $item->check_out,
                'Total Waktu'   => $totalTime,
                'Catatan'       => $item->note,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama User',
            'Tanggal',
            'Masuk',
            'Keluar',
            'Total Waktu',
            'Catatan',
        ];
    }
}
