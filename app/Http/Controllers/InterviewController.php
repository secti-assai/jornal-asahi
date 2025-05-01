<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InterviewController extends Controller
{
    /**
     * Display a listing of interviews
     */
    public function index()
    {
        $interviews = Interview::orderBy('created_at', 'desc')->get();
        return view('interviews.index', compact('interviews'));
    }

    /**
     * Store a newly created interview
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'youtube_video_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'interview_date' => 'nullable|date',
            'interviewee' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Preparar os dados
        $data = $request->all();
        $data['featured'] = $request->has('featured') ? true : false;

        // Criar a entrevista
        Interview::create($data);

        return redirect()->route('dashboard')->with('success', 'Entrevista adicionada com sucesso!');
    }

    /**
     * Display the specified interview
     */
    public function show($id)
    {
        $interview = Interview::findOrFail($id);
        return view('interviews.show', compact('interview'));
    }

    /**
     * Update the specified interview
     */
    public function update(Request $request, $id)
    {
        $interview = Interview::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'youtube_video_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'interview_date' => 'nullable|date',
            'interviewee' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Preparar os dados
        $data = $request->all();
        $data['featured'] = $request->has('featured') ? true : false;

        $interview->update($data);

        return redirect()->route('dashboard')->with('success', 'Entrevista atualizada com sucesso!');
    }

    /**
     * Toggle the featured status of an interview
     */
    public function toggleFeatured($id)
    {
        $interview = Interview::findOrFail($id);
        $interview->featured = !$interview->featured;
        $interview->save();

        return redirect()->back()->with('success', 'Status de destaque atualizado com sucesso!');
    }

    /**
     * Remove the specified interview
     */
    public function destroy($id)
    {
        $interview = Interview::findOrFail($id);
        $interview->delete();
        
        return redirect()->route('dashboard')->with('success', 'Entrevista excluída com sucesso!');
    }

    /**
     * Lista todas as entrevistas
     */
    public function listAll()
    {
        $interviews = Interview::orderBy('interview_date', 'desc')->paginate(9);
        $featuredInterview = Interview::where('featured', true)->first();
        
        return view('interviews.list', compact('interviews', 'featuredInterview'));
    }
}