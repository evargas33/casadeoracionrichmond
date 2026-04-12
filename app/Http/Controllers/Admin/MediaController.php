<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        abort_if(! $request->user()->hasAnyRole(['superadmin', 'admin', 'editor']), 403);

        $request->validate([
            'files'   => 'required|array|max:20',
            'files.*' => 'file|max:51200|mimes:jpg,jpeg,png,gif,webp,svg,mp3,wav,mp4,mov',
        ]);

        foreach ($request->file('files', []) as $file) {
            $diskName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('media', $diskName, 'public');

            $data = [
                'user_id'   => $request->user()->id,
                'name'      => $file->getClientOriginalName(),
                'disk_name' => $diskName,
                'path'      => $path,
                'mime_type' => $file->getMimeType(),
                'size'      => $file->getSize(),
            ];

            if (str_starts_with($file->getMimeType(), 'image/')) {
                [$w, $h]      = @getimagesize($file->getRealPath()) ?: [null, null];
                $data['width']  = $w;
                $data['height'] = $h;
            }

            Media::create($data);
        }

        return back()->with('success', count($request->file('files')) . ' archivo(s) subido(s) correctamente.');
    }

    public function destroy(Media $media)
    {
        abort_if(! request()->user()->hasAnyRole(['superadmin', 'admin']), 403);

        \Illuminate\Support\Facades\Storage::disk('public')->delete($media->path);
        $media->delete();

        return back()->with('success', 'Archivo eliminado.');
    }
}
