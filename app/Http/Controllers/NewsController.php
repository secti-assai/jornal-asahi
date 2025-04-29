<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::where('approved', true);
        
        // Verificar se existe um termo de pesquisa
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            
            // Pesquisa no título e conteúdo
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Ordenar por data de publicação (mais recentes primeiro)
        $news = $query->orderBy('published_at', 'desc')->paginate(10);
        
        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        if (!$news->approved && !Auth::check()) {
            abort(404);
        }
        
        // Buscar as últimas 3 notícias publicadas que não sejam a notícia atual
        $latestNews = News::where('id', '!=', $news->id)
            ->where('approved', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
        
        return view('news.show', compact('news', 'latestNews'));
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
            
            // Processar imagem de capa se existir
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imagePath = $request->file('image')->store('news_images', 'public');
                $news->image = $imagePath;
            }
            
            $news->save();
            
            // Salvar a imagem de capa no modelo NewsImage se existir
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                NewsImage::create([
                    'news_id' => $news->id,
                    'path' => $imagePath,
                    'is_cover' => true,
                    'caption' => $news->title
                ]);
            }
            
            // Extrair imagens do conteúdo e salvá-las no modelo NewsImage
            $this->extractAndSaveContentImages($news);
            
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
            // Apagar a imagem de capa antiga no model NewsImage
            NewsImage::where('news_id', $news->id)
                    ->where('is_cover', true)
                    ->delete();
                    
            // Apagar a imagem física antiga
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            
            // Salvar nova imagem
            $path = $request->file('image')->store('news_images', 'public');
            $news->image = $path;
            
            // Salvar nova imagem no modelo NewsImage
            NewsImage::create([
                'news_id' => $news->id,
                'path' => $path,
                'is_cover' => true,
                'caption' => $news->title
            ]);
        }
        
        $news->approved = false;
        $news->save();
        
        // Limpar imagens antigas que não sejam de capa
        NewsImage::where('news_id', $news->id)
                ->where('is_cover', false)
                ->delete();
                
        // Extrair novas imagens do conteúdo
        $this->extractAndSaveContentImages($news);
        
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

    /**
     * Extrair as URLs das imagens do conteúdo e salva no modelo NewsImage.
     *
     * @param News $news
     * @return void
     */
    private function extractAndSaveContentImages(News $news)
    {
        // Usar expressão regular para encontrar todas as tags IMG no conteúdo
        preg_match_all('/<img[^>]+src="([^"]+)"[^>]*>/i', $news->content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $imageUrl) {
                // Verificar se a URL é de uma imagem armazenada no storage
                if (strpos($imageUrl, '/storage/news_content_images') !== false) {
                    // Extrair o caminho relativo da URL
                    $path = str_replace(url('/storage/'), '', $imageUrl);
                    
                    // Também tente extrair sem o URL completo (apenas o caminho relativo)
                    if (strpos($path, 'news_content_images/') === false) {
                        $path = 'news_content_images/' . basename($path);
                    }
                    
                    // Verificar se esta imagem já está registrada
                    $existingImage = \App\Models\NewsImage::where('news_id', $news->id)
                                        ->where('path', $path)
                                        ->first();
                    
                    if (!$existingImage) {
                        // Log para depuração
                        \Illuminate\Support\Facades\Log::info("Registrando imagem: {$path} para notícia {$news->id}");
                        
                        \App\Models\NewsImage::create([
                            'news_id' => $news->id,
                            'path' => $path,
                            'is_cover' => false,
                            'caption' => null
                        ]);
                    }
                }
            }
        }
    }
}