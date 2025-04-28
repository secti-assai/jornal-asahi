<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LiveStreamController extends Controller
{
    /**
     * Display a listing of live streams
     */
    public function index()
    {
        return LiveStream::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created live stream
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'youtube_video_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // If this stream is active, deactivate all others
        if ($request->input('is_active', false)) {
            LiveStream::where('is_active', true)->update(['is_active' => false]);
        }

        $liveStream = LiveStream::create($request->all());
        return response()->json($liveStream, 201);
    }

    /**
     * Display the specified live stream
     */
    public function show($id)
    {
        return LiveStream::findOrFail($id);
    }

    /**
     * Update the specified live stream
     */
    public function update(Request $request, $id)
    {
        $liveStream = LiveStream::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'youtube_video_id' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // If this stream is being set to active, deactivate all others
        if ($request->has('is_active') && $request->input('is_active') == true) {
            LiveStream::where('id', '!=', $id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $liveStream->update($request->all());
        return response()->json($liveStream);
    }

    /**
     * Remove the specified live stream
     */
    public function destroy($id)
    {
        $liveStream = LiveStream::findOrFail($id);
        $liveStream->delete();
        return response()->json(null, 204);
    }

    /**
     * Set specified live stream as active
     */
    public function activate($id)
    {
        // Deactivate all live streams
        LiveStream::where('is_active', true)->update(['is_active' => false]);
        
        // Activate the specified live stream
        $liveStream = LiveStream::findOrFail($id);
        $liveStream->is_active = true;
        $liveStream->save();
        
        return response()->json($liveStream);
    }

    /**
     * Get currently active live stream
     */
    public function getActive()
    {
        $activeLiveStream = LiveStream::where('is_active', true)->first();
        
        if ($activeLiveStream) {
            return response()->json($activeLiveStream);
        }
        
        return response()->json(['message' => 'No active live stream found'], 404);
    }
}
