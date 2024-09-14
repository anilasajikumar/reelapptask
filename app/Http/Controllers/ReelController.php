<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ReelController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the request
        $request->validate([
            'file_id' => 'required|string|unique:reels,file_id',
            'original_filename' => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4,mov,avi,flv|max:102400', // Max 100MB
        ]);

        // Store the uploaded file in a public directory
        $path = $request->file('video')->store('reels', 'public');

        // Save reel metadata in the database
        $reel = Reel::create([
            'file_id' => $request->input('file_id'),
            'title' => $request->input('title'),
            'original_filename' => $request->input('original_filename'),
            'file_path' => $path,
            'mime_type' => $request->file('video')->getClientMimeType(),
            'size' => $request->file('video')->getSize(),
        ]);

        return response()->json([
            'message' => 'Reel uploaded successfully',
            'reel' => $reel
        ], 201);
    }

    // Handles reel playback (streaming)
    public function play($file_id)
    {
        $reel = Reel::where('file_id', $file_id)->firstOrFail();

        $path = storage_path('app/public/' . $reel->file_path);

        if (!file_exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // Streaming video in chunks for smooth playback
        return new StreamedResponse(function() use ($path) {
            $stream = fopen($path, 'rb');
            while (!feof($stream)) {
                echo fread($stream, 1024 * 8);  // Stream the file in chunks
                flush();
            }
            fclose($stream);
        }, 200, [
            'Content-Type' => $reel->mime_type,
            'Content-Length' => $reel->size,
            'Accept-Ranges' => 'bytes',
        ]);
    }


}
