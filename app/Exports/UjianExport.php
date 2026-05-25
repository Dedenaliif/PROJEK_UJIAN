<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UjianExport implements FromArray, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $judul;

    public function __construct($data, $judul)
    {
        $this->data = $data;
        $this->judul = $judul;
    }

    public function array(): array
    {
        return array_merge([
            [$this->judul],
            [],
        ], $this->data);
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $sheet->getHighestColumn();

        $sheet->mergeCells("A1:{$lastCol}1");

        return [

            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16
                ],
                'alignment' => [
                    'horizontal' => 'center'
                ]
            ],

            3 => [
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => [
                        'rgb' => '4F81BD'
                    ]
                ]
            ]
        ];
    }
}
