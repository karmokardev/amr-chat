<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'max:51200'], // 50MB max
        ]);

        $file      = $request->file('file');
        $mime      = $file->getMimeType();
        $extension = $file->getClientOriginalExtension();
        $original  = $file->getClientOriginalName();
        $size      = $file->getSize();

        // Determine type
        $type = match(true) {
            str_starts_with($mime, 'image/') => 'image',
            str_starts_with($mime, 'video/') => 'video',
            str_starts_with($mime, 'audio/') => 'voice',
            default                           => 'file',
        };

        // Store file
        $path = $file->store("media/" . date('Y/m'), 'public');

        // Thumbnail for images
        $thumbnailPath = null;
        if ($type === 'image') {
            $thumbnailPath = $path; // same path for now
        }

        $media = Media::create([
            'uploaded_by'   => Auth::id(),
            'disk'          => 'public',
            'path'          => $path,
            'original_name' => $original,
            'mime_type'     => $mime,
            'extension'     => $extension,
            'size'          => $size,
            'thumbnail_path' => $thumbnailPath,
        ]);

        return response()->json([
            'id'            => $media->id,
            'url'           => asset('storage/' . $path),
            'thumbnail_url' => $thumbnailPath ? asset('storage/' . $thumbnailPath) : null,
            'type'          => $type,
            'original_name' => $original,
            'size'          => $size,
            'mime_type'     => $mime,
        ]);
    }

    public function destroy(Media $media)
    {
        if ($media->uploaded_by !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($media->path);

        if ($media->thumbnail_path && $media->thumbnail_path !== $media->path) {
            Storage::disk('public')->delete($media->thumbnail_path);
        }

        $media->delete();

        return response()->json(['success' => true]);
    }
}