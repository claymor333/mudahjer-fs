<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

//////////////// ------------------- quiz and notes controller

class QuizController extends Controller
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
        $lessons = Lesson::get();
        return view('admin.quizzes.create-quiz', compact('lessons'));
    }

    public function storeQuiz(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'choices_type' => 'required|in:text,media',
            'lesson_id' => 'required',
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
                'description' => $validated['description'],
                'choices_type' => $validated['choices_type'],
                'lesson_id' => $validated['lesson_id']
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

    public function editQuiz($quizId)
    {
        $quiz = Quiz::with([
            'questions' => function ($query) {
                $query->orderBy('id', 'asc')
                    ->with(['choices' => function ($q) {
                        $q->orderBy('id', 'asc');
                    }]);
            }
        ])->findOrFail($quizId);

        $lessons = Lesson::get();

        // Log::info('Editing quiz ID: ' . $quiz);

        return view('admin.quizzes.edit-quiz', compact('quiz', 'lessons'));
    }


    public function updateQuiz(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'choices_type' => 'required|in:media,text',
                'lesson_id' => 'required',
                'questions' => 'required|array|min:1',
                'questions.*.id' => 'nullable|exists:questions,id',
                'questions.*.question_text' => 'required|string',
                'questions.*.choices' => 'required|array|min:2',
                'questions.*.choices.*.choice_text' => 'required|string',
                'questions.*.correct_choice' => 'required|numeric',
                'questions.*.media' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,mp4,webm|max:10240',
                'questions.*.existing_media' => 'nullable|string',
                'questions.*.remove_media' => 'nullable|string',
                'deleted_questions' => 'nullable|string'
            ]);
            DB::beginTransaction();

            // Update quiz basic info
            $quiz->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'choices_type' => $validated['choices_type'],
                'lesson_id' => $validated['lesson_id']
            ]);

            // Keep track of processed questions and choices to remove deleted ones
            $processedQuestionIds = [];
            $processedChoiceIds = [];

            foreach ($validated['questions'] as $index => $questionData) {
                // Handle existing or create new question
                if (isset($questionData['id'])) {
                    $question = Question::find($questionData['id']);
                } else {
                    $question = new Question();
                }

                // Handle media
                $mediaPath = $question->media_path; // Keep existing path by default

                // Handle media removal if requested
                if (!empty($questionData['remove_media'])) {
                    Log::info('Removing media for question ID: ' . $question->id);
                    if ($question->media_path) {
                        Storage::disk('public')->delete($question->media_path);
                    }
                    $mediaPath = null;
                }
                // Handle new media upload
                elseif (isset($request->file('questions')[$index]['media'])) {
                    // Remove old media if exists
                    if ($question->media_path) {
                        Storage::disk('public')->delete($question->media_path);
                    }
                    $mediaPath = $request->file('questions')[$index]['media']
                        ->store('question-media', 'public');
                }

                // Update or create question
                $question->question_text = $questionData['question_text'];
                $question->media_path = $mediaPath;
                $question->quiz_id = $quiz->id;
                $question->save();

                $processedQuestionIds[] = $question->id;

                // Handle choices
                foreach ($questionData['choices'] as $choiceIndex => $choiceData) {
                    if (isset($choiceData['id'])) {
                        $choice = Choice::find($choiceData['id']);
                    } else {
                        $choice = new Choice();
                        $choice->question_id = $question->id;
                    }

                    $choice->choice_text = $choiceData['choice_text'];
                    $choice->is_correct = $choiceIndex == $questionData['correct_choice'];
                    $choice->save();

                    $processedChoiceIds[] = $choice->id;
                }
            }

            // Remove questions and choices that were deleted in the UI
            Question::where('quiz_id', $quiz->id)
                ->whereNotIn('id', $processedQuestionIds)
                ->get()
                ->each(function ($question) {
                    if ($question->media_path) {
                        Storage::disk('public')->delete($question->media_path);
                    }
                    $question->delete();
                });

            Choice::whereIn('question_id', $processedQuestionIds)
                ->whereNotIn('id', $processedChoiceIds)
                ->delete();

            DB::commit();

            session()->flash('success', 'Quiz updated successfully!');

            return response()->json([
                'success' => true,
                'message' => 'Quiz updated successfully!',
                'redirect' => route('admin.dashboard')
            ]);

            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quiz update failed: ' . $e->getMessage());

            session()->flash('error', 'Failed to update quiz. Please try again.');

            if ($request->wantsJson()) {    // ajax request must have accept: application/json header
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update quiz. Please try again.'
                ], 500);
            }

            return back()->withInput();
        }
    }

    public function deleteQuiz($id)
    {
        Log::info('Deleting quiz ID: ' . $id);

        $quiz = Quiz::with('questions')->findOrFail($id);

        try {
            // delete related media files
            foreach ($quiz->questions as $question) {
                if ($question->media_path) {
                    Storage::disk('public')->delete($question->media_path);
                }
            }

            // this assumes you have proper `onDelete('cascade')` on your foreign keys in the DB,
            // or `->cascadeDeletes()` on Eloquent relationships if you prefer soft-deletes.
            $quiz->delete();

            session()->flash('success', 'Quiz deleted successfully!');
            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            Log::error('Failed to delete quiz: ' . $e->getMessage());

            session()->flash('error', 'Failed to delete quiz. Please try again.');
            return redirect()->route('admin.dashboard');
        }
    }

    public function storeLesson() {
        $validated = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $lesson = Lesson::create($validated);

            session()->flash('success', 'Lesson created successfully!');

            return response()->json([
                'success' => true,
                'id' => $lesson->id,
                'title' => $lesson->title,
                'redirect' => route('admin.quizzes.create', ['message' => 'Lesson created successfully!'])
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create lesson: ' . $e->getMessage());

            session()->flash('error', 'Failed to create lesson. Please try again.');

            return response()->json([
                'success' => false,
                'message' => 'Failed to create lesson. Please try again.'
            ], 500);
        }
    }
}
