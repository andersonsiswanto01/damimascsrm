<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;


class DownloadController extends Controller
{
      public function download(Request $request, $fileName)
    {
        $filePath = storage_path('app/public/' . $fileName);

        // Optional: Check if file exists to avoid exception
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Return the file as a download and delete it after sending
        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
