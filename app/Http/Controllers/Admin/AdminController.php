<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::latest()->paginate(10);

        if (request()->has('message')) {
            session()->flash('success', request()->message);
        }

        return view('admin.dashboard', compact('quizzes'));
    }

    
}
