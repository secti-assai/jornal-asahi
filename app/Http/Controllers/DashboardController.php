<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isReporter()) {
            $news = News::where('author_id', $user->id)->latest()->paginate(10);
        } elseif ($user->isApprover()) {
            $news = News::where('approved', false)->latest()->paginate(10);
        } else { // Admin
            $news = News::latest()->paginate(10);
        }
        
        return view('dashboard.index', compact('news'));
    }
}