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
            return [
                'Nama User' => $item->user->name,
                'Tanggal' => $item->scanned_at,
                'Masuk' => $item->check_in,
                'Keluar' => $item->check_out,
                'Catatan' => $item->note,
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
            'Catatan',
        ];
    }
}
