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
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Preparar os dados
        $data = [
            'youtube_video_id' => $request->youtube_video_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_active' => $request->has('is_active') ? true : false
        ];

        // Se esta transmissão será ativada, desative todas as outras
        if ($data['is_active']) {
            LiveStream::where('is_active', true)->update(['is_active' => false]);
        }

        // Criar a transmissão
        LiveStream::create($data);

        return redirect()->route('dashboard')->with('success', 'Transmissão criada com sucesso!');
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
            'youtube_video_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Preparar os dados - garantir que is_active seja tratado corretamente
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Se esta transmissão será ativada, desative todas as outras
        if ($data['is_active']) {
            LiveStream::where('id', '!=', $id)->where('is_active', true)->update(['is_active' => false]);
        }

        $liveStream->update($data);

        return redirect()->route('dashboard')->with('success', 'Transmissão atualizada com sucesso!');
    }

    /**
     * Set specified live stream as active
     */
    public function activate($id)
    {
        // Desativar todas as transmissões ao vivo
        LiveStream::where('is_active', true)->update(['is_active' => false]);

        // Ativar a transmissão especificada
        $liveStream = LiveStream::findOrFail($id);
        $liveStream->is_active = true;
        $liveStream->save();

        return redirect()->route('dashboard')->with('success', 'Transmissão ativada com sucesso!');
    }

    /**
     * Remove the specified live stream
     */
    public function destroy($id)
    {
        $liveStream = LiveStream::findOrFail($id);
        $liveStream->delete();
        
        return redirect()->route('dashboard')->with('success', 'Transmissão excluída com sucesso!');
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

    /**
     * Lista todas as transmissões ao vivo (passadas e atuais)
     * 
     * @return \Illuminate\View\View
     */
    public function listAll()
    {
        // Buscar todas as transmissões ordenadas pela mais recente primeiro
        $liveStreams = LiveStream::orderBy('start_time', 'desc')
                                ->paginate(9); // 9 itens por página para uma grade 3x3
        
        // Pegar a transmissão ativa atual, se houver
        $activeLiveStream = LiveStream::where('is_active', true)->first();
        
        return view('live-streams.list', compact('liveStreams', 'activeLiveStream'));
    }
}
