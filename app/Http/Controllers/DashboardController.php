<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Models\LiveStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $myNews = null;
        $pendingNews = null;
        $allNews = null;
        $liveStreams = null;
        
        // Para todos os usuários, mostrar suas próprias notícias
        if ($user->isReporter() || $user->isApprover() || $user->isAdmin()) {
            $myNews = News::where('author_id', $user->id)->latest()->paginate(5, ['*'], 'my_news');
        }
        
        // Para aprovadores e admins, mostrar notícias pendentes
        if ($user->isApprover() || $user->isAdmin()) {
            $pendingNews = News::where('approved', false)->latest()->paginate(5, ['*'], 'pending_news');
        }
        
        // Somente para admins, mostrar todas as notícias e transmissões ao vivo
        if ($user->isAdmin()) {
            $allNews = News::latest()->paginate(10, ['*'], 'all_news');
            $liveStreams = LiveStream::latest()->get();
        }
        
        return view('dashboard.index', compact('myNews', 'pendingNews', 'allNews', 'liveStreams'));
    }
}