<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $categories = Lesson::count();
        $signs = Question::count();

        return view('dashboard', compact('categories', 'signs'));
    }
}
