<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Quiz;


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
