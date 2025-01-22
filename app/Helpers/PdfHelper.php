<?php
namespace App\Helpers;

use App\Models\File;
use App\Models\FileLog;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

Class PdfHelper
{

    public function getById($id)
    {
        return  FileLog::findOrFail($id);
    }
    public function compare( $oldId)
    {
        $old = $this->getById($oldId);
        $currentPath = File::where('id', $old ->file_id)->value('path');
        $current = Storage::get($currentPath);

        $oldFileData = json_decode($old->file, true);

        if ($oldFileData === null) {
            throw new \Exception('Failed to decode JSON from file log.');
        }


        $oldContent = $oldFileData['old'];
        $diff = FileParser::getFileDiff($oldContent, $current);

        return $diff;
    }





    public function archive($oldId)
    {
        $archiveResults = FileLog::findOrFail($oldId);
        return $this->createArchivePdf($archiveResults);
    }

    public function createDiffPdf($diffResults)
    {
        $html = View::make('diff_report', ['diffResults' => $diffResults])->render();
        return  $this->domPdf($html);
    }

    public function createArchivePdf($archiveResults)
    {
        $html = View::make('archive_report', ['diffResults' => $archiveResults])->render();
        return  $this->domPdf($html);
    }

    public function domPdf($html)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "diffReport.pdf";

        $output = $dompdf->output();
        return response()->streamDownload(function () use ($output) {
            echo $output;
        }, $filename, ['Content-Type' => 'application/pdf']);
    }

}
