<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Exibir todas as imagens da galeria.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = NewsImage::with('news')
                    ->whereNotNull('news_id')
                    ->whereHas('news', function ($query) {
                        $query->where('approved', true)
                            ->whereNotNull('published_at');
                    });
                    
        // Filtragem por tipo de imagem
        if ($request->has('type') && in_array($request->type, ['cover', 'content'])) {
            $query->where('is_cover', $request->type === 'cover');
        }
        
        // Buscar a notícia específica
        if ($request->has('news_id') && is_numeric($request->news_id)) {
            $query->where('news_id', $request->news_id);
        }
                    
        $images = $query->latest()
                    ->paginate(24)
                    ->withQueryString();
        
        return view('gallery.index', compact('images'));
    }

    /**
     * Exibir imagens de uma notícia específica.
     *
     * @param int $newsId
     * @return \Illuminate\View\View
     */
    public function showNewsImages($newsId)
    {
        $news = News::findOrFail($newsId);
        
        if (!$news->approved && !auth()->user()) {
            abort(404);
        }
        
        $images = NewsImage::where('news_id', $newsId)
                    ->latest()
                    ->get();
                    
        return view('gallery.news-images', compact('images', 'news'));
    }
}