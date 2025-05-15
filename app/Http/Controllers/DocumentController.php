<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
     /**
     * Show the requested document if the user is authenticated.
     *
     * @param string $file
     * @return \Illuminate\Http\Response
     */
    public function showDocument($file)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            abort(403, 'Unauthorized');  // You can customize this message
        }

        // Set the file path based on the 'private' storage disk
        $filePath = storage_path('app/private/signature/' . $file);

        // Check if the file exists
        if (!file_exists($filePath)) {
            abort(404, 'File not found');  // Custom error message if file is missing
        }

        // Dynamically determine the mime type of the file
        $mimeType = mime_content_type($filePath);

        // Return the file with the appropriate headers
        return Response::make(file_get_contents($filePath), 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $file . '"',  // Displays the file inline (can change to 'attachment' to force download)
        ]);
    }
}
