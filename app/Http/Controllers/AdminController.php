<?php

namespace App\Http\Controllers;

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

    public function createQuiz()
    {
        return view('admin.quizzes.create-quiz');
    }

    public function storeQuiz(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.choices' => 'required|array|min:2',
            'questions.*.choices.*' => 'required|string',
            'questions.*.correct_choice' => 'required|numeric',
            'questions.*.media' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,mp4,webm|max:10240'
        ]);

        try {
            $quiz = Quiz::create([
                'title' => $validated['title'],
                'description' => $validated['description']
            ]);

            foreach ($validated['questions'] as $index => $questionData) {
                $mediaPath = null;
                if (isset($request->file('questions')[$index]['media'])) {
                    $mediaPath = $request->file('questions')[$index]['media']
                        ->store('question-media', 'public');
                }

                $question = $quiz->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'media_path' => $mediaPath
                ]);

                foreach ($questionData['choices'] as $choiceIndex => $choiceText) {
                    $question->choices()->create([
                        'choice_text' => $choiceText,
                        'is_correct' => $choiceIndex == $questionData['correct_choice']
                    ]);
                }
            }

            session()->flash('success', 'Quiz created successfully!');

            return response()->json([
                'success' => true,
                'redirect' => route('admin.dashboard', ['message' => 'Quiz created successfully!'])
            ]);

            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            Log::error('Quiz creation failed: ' . $e->getMessage());

            session()->flash('error', 'Failed to create quiz. Please try again.');

            return response()->json([
                'success' => false,
                'message' => 'Failed to create quiz. Please try again.'
            ], 500);

            return back()->withInput();
        }
    }

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create quiz. Please try again.');
        }
    }
}
