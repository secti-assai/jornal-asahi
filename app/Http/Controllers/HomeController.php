<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial do site com as notícias aprovadas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Busca as notícias aprovadas e publicadas, ordenadas pela data de publicação
        $featuredNews = News::where('approved', true)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();
        
        $latestNews = News::where('approved', true)
            ->orderBy('published_at', 'desc')
            ->paginate(9);
        
        return view('home', compact('featuredNews', 'latestNews'));
    }
}