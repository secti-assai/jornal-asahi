<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('approved', true)
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        
        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        if (!$news->approved && !Auth::check()) {
            abort(404);
        }
        
        return view('news.show', compact('news'));
    }

    public function create()
    {
        return view('news.create');
    }

    public function store(Request $request)
    {
        // Validação dos dados com limite de tamanho para o conteúdo
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required|max:65000', // Limitar o texto para um tamanho razoável
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Limitar tamanho da imagem para 2MB
        ]);
        
        try {
            // Criar a notícia
            $news = new News();
            $news->title = $validated['title'];
            $news->content = $validated['content'];
            $news->author_id = auth()->id();
            
            // Processar imagem se existir
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imagePath = $request->file('image')->store('news_images', 'public');
                $news->image = $imagePath;
            }
            
            $news->save();
            
            return redirect()->route('dashboard')->with('success', 'Notícia criada com sucesso! Aguardando aprovação.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao salvar notícia: ' . $e->getMessage());
        }
    }

    public function edit(News $news)
    {
        if (Auth::user()->id !== $news->author_id && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        if (Auth::user()->id !== $news->author_id && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $news->title = $validated['title'];
        $news->content = $validated['content'];
        
        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $path = $request->file('image')->store('news_images', 'public');
            $news->image = $path;
        }
        
        $news->approved = false;
        $news->save();
        
        return redirect()->route('dashboard')->with('success', 'Notícia atualizada e aguardando aprovação.');
    }

    public function approve(News $news)
    {
        if (!Auth::user()->isApprover() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $news->approved = true;
        $news->approved_by = Auth::id();
        $news->published_at = now();
        $news->save();
        
        return redirect()->route('dashboard')->with('success', 'Notícia aprovada e publicada.');
    }

    public function destroy(News $news)
    {
        if (Auth::user()->id !== $news->author_id && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        
        $news->delete();
        
        return redirect()->route('dashboard')->with('success', 'Notícia excluída com sucesso.');
    }
}