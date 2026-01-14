<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ScoresExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $scores;
    protected $filters;

    public function __construct($scores, $filters = [])
    {
        $this->scores = $scores;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->scores;
    }

    public function headings(): array
    {
        return [
            'ID',
            'User Name',
            'User Email',
            'Level Title',
            'Game Type',
            'Score',
            'Time (seconds)',
            'Time (formatted)',
            'Completed At',
        ];
    }

    public function map($score): array
    {
        $minutes = floor($score->time_seconds / 60);
        $seconds = $score->time_seconds % 60;
        $timeFormatted = sprintf('%02d:%02d', $minutes, $seconds);

        return [
            $score->id,
            $score->user->name,
            $score->user->email,
            $score->level->title,
            ucfirst($score->game_type),
            $score->score,
            $score->time_seconds,
            $timeFormatted,
            $score->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Check if we have any filters
                if (empty($this->filters)) {
                    return;
                }

                // Insert rows at the top for filter information
                $filterRows = [];
                $filterRows[] = ['EXPORT FILTERS'];
                $filterRows[] = [''];

                if (!empty($this->filters['game_type'])) {
                    $filterRows[] = ['Game Type:', ucfirst($this->filters['game_type'])];
                }

                if (!empty($this->filters['level_name'])) {
                    $filterRows[] = ['Level:', $this->filters['level_name']];
                }

                if (!empty($this->filters['date_from'])) {
                    $filterRows[] = ['Date From:', $this->filters['date_from']];
                }

                if (!empty($this->filters['date_to'])) {
                    $filterRows[] = ['Date To:', $this->filters['date_to']];
                }

                if (!empty($this->filters['user_search'])) {
                    $filterRows[] = ['User Search:', $this->filters['user_search']];
                }

                $filterRows[] = ['Export Date:', now()->format('Y-m-d H:i:s')];
                $filterRows[] = ['Total Records:', $this->scores->count()];
                $filterRows[] = [''];

                // Insert filter rows at the beginning
                $sheet->insertNewRowBefore(1, count($filterRows));

                foreach ($filterRows as $index => $row) {
                    $rowNum = $index + 1;
                    $sheet->setCellValue('A' . $rowNum, $row[0]);
                    if (isset($row[1])) {
                        $sheet->setCellValue('B' . $rowNum, $row[1]);
                    }
                }

                // Style the filter section
                $sheet->getStyle('A1:B1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '4A5568']],
                ]);

                $sheet->getStyle('A3:A' . (count($filterRows) - 1))->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // Auto-size columns
                foreach(range('A','I') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Make the data header row bold (now pushed down by filter rows)
                $headerRow = count($filterRows) + 1;
                $sheet->getStyle('A' . $headerRow . ':I' . $headerRow)->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'E2E8F0']
                    ],
                ]);
            },
        ];
    }
}
