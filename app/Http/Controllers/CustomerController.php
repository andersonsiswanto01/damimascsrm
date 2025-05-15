<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Customer;
use Symfony\Component\HttpFoundation\Response;


class CustomerController extends Controller
{
    public function showPhoto($filename)
    {
        $filePath = "private/documents/{$filename}";

      // Check if the file exists in the private storage
      if (!Storage::disk('private')->exists("documents/{$filename}")) {
        abort(404, 'File not found');
    }

        // Get the file and determine its MIME type
        $file = Storage::disk('private')->get("documents/{$filename}");
        $mimeType = Storage::disk('private')->mimeType("documents/{$filename}");


        return response($file, 200)->header('Content-Type', $mimeType);
    }
    }

