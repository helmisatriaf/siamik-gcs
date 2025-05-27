<?php

namespace App\Http\Controllers;
use App\Helpers\WatermarkPdf;

use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;


class DownloadController extends Controller
{
    
    public function downloadWithWatermark($fileName)
    {
        try{
            $filePath = storage_path("app/public/file/assessment/" . $fileName);
    
            if (!file_exists($filePath)) {
                return abort(404, 'File not found');
            }
    
            // Load FPDI untuk modifikasi PDF
            $pdf = new WatermarkPdf();
            $pdf->setSourceFile($filePath);
            $pageCount = $pdf->setSourceFile($filePath);
        
            for ($i = 1; $i <= $pageCount; $i++) {
                $tplIdx = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx, 10, 10, 200);
            }
    
            return response()->streamDownload(function () use ($pdf, $fileName) {
                $pdf->Output('D', $fileName); // 'D' untuk force download
            }, $fileName);    
        }
        catch(Exception $err){
            return response()->download($filePath, $fileName);    
        }
    }

}
