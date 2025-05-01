<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\LiveStream;
use Illuminate\Http\Request;
use App\Models\NewsImage;
use App\Models\Interview;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial do site com as notícias aprovadas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Código existente para buscar notícias em destaque e ao vivo
        $featuredNews = News::where('approved', true)
                            ->whereNotNull('published_at')
                            ->orderBy('published_at', 'desc')
                            ->limit(5)
                            ->get();
                            
        $latestNews = News::where('approved', true)
                          ->whereNotNull('published_at')
                          ->orderBy('published_at', 'desc')
                          ->limit(6)
                          ->get();
                          
        // Corrija a consulta para usar a coluna is_active em vez de active
        $activeLiveStream = LiveStream::where('is_active', true)
                                   ->latest('start_time')
                                   ->first();
                                   
        // Buscar imagens para a galeria da página inicial
        $galleryImages = NewsImage::with('news')
                                ->whereHas('news', function ($query) {
                                    $query->where('approved', true)
                                        ->whereNotNull('published_at');
                                })
                                ->latest()
                                ->limit(12)
                                ->get();
        
        // Buscar entrevistas em destaque
        $featuredInterview = Interview::where('featured', true)
                                ->first();
                                
        $latestInterviews = Interview::orderBy('interview_date', 'desc')
                              ->limit(3)
                              ->get();
    
        return view('home', compact('featuredNews', 'latestNews', 'activeLiveStream', 'galleryImages', 'featuredInterview', 'latestInterviews'));
    }
}