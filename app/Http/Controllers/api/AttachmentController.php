<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attachments = Attachment::all();
        return AttachmentResource::collection($attachments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $file = $request->file('attachment');
        $type = $file->getClientMimeType();
        $name = $file->getClientOriginalName();
        $path = $file->store('public/attachments/');
        $size = $file->getSize();

        $attachment = Attachment::create([
            'type' => $type,
            'name' => $name,
            'path' => $path,
            'size' => $size,
            'user_id' => $request->user_id,
            'card_id' => $request->card_id,
        ]);

        return new AttachmentResource($attachment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {
        //
        return new AttachmentResource($attachment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attachment $attachment)
    {
        //
        $attachment->update([
            'name' => $request->name,
        ]);

        return new AttachmentResource($attachment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        //
        Storage::delete($attachment->path);
        $attachment->delete();

        return response('Deleted', 200);
    }

    public function serveAttachment($id)
    {
        //
        $attachment = Attachment::findOrFail($id);
        $response = response()->download(storage_path('app/' . $attachment->path));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $attachment->name . '"');

        return $response;
    }
}
