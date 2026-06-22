<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TableExportService
{
    public function toCsv(array $headers, iterable $rows, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, $headers, ';');

            foreach ($rows as $row) {
                fputcsv($handle, $row, ';');
            }

            fclose($handle);
        }, "{$filename}.csv", [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function toExcel(array $headers, iterable $rows, string $filename): StreamedResponse
    {
        return $this->toCsv($headers, $rows, $filename);
    }

    public function toPdf(string $view, array $data, string $filename): Response
    {
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', 'landscape');

        return $pdf->download("{$filename}.pdf");
    }
}
