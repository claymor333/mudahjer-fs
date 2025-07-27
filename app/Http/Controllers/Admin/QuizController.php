<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Choice;
use App\Models\Lesson;
use App\Models\Note;
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

            'notes' => 'required|array|min:1',
            'notes.*.note_text' => 'required|string',
            'notes.*.media' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,mp4,webm|max:10240',

            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
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

            // Notes
            foreach ($validated['notes'] as $index => $noteData) {
                $mediaPath = null;
                if (isset($request->file('notes')[$index]['media'])) {
                    $mediaPath = $request->file('notes')[$index]['media']
                        ->store('note-media', 'public');
                }

                $quiz->notes()->create([
                    'note_text' => $noteData['note_text'],
                    'media_path' => $mediaPath
                ]);
            }

            // Questions
            foreach ($validated['questions'] as $qIndex => $questionData) {
                $mediaPath = null;
                if (isset($request->file('questions')[$qIndex]['media'])) {
                    $mediaPath = $request->file('questions')[$qIndex]['media']
                        ->store('question-media', 'public');
                }

                $question = $quiz->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'media_path' => $mediaPath
                ]);

                // Choices
                $choices = $request->input("questions.{$qIndex}.choices", []);
                $correctIndex = $questionData['correct_choice'];

                foreach ($choices as $cIndex => $choice) {
                    $choiceText = $choice['choice_text'] ?? null;
                    $choiceMediaPath = null;

                    if ($validated['choices_type'] === 'media') {
                        if (isset($request->file("questions")[$qIndex]['choices'][$cIndex]['choice_media'])) {
                            $choiceMediaPath = $request->file("questions")[$qIndex]['choices'][$cIndex]['choice_media']
                                ->store('choice-media', 'public');
                        }
                    }

                    $question->choices()->create([
                        'choice_text' => $validated['choices_type'] === 'text' ? $choiceText : null,
                        'choice_media' => $validated['choices_type'] === 'media' ? $choiceMediaPath : null,
                        'is_correct' => $cIndex == $correctIndex
                    ]);
                }
            }

            session()->flash('success', 'Quiz created successfully!');

            return response()->json([
                'success' => true,
                'redirect' => route('admin.dashboard', ['message' => 'Quiz created successfully!'])
            ]);

        } catch (\Exception $e) {
            Log::error('Quiz creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create quiz. Please try again.'
            ], 500);
        }
    }


    public function editQuiz($quiz_id)
    {
        $quiz = Quiz::with([
            'questions' => function ($query) {
                $query->orderBy('id', 'asc')
                    ->with(['choices' => function ($q) {
                        $q->orderBy('id', 'asc');
                    }]);
            },
            'notes' => function ($query) {
                $query->orderBy('id', 'asc');
            }
        ])->findOrFail($quiz_id);

        // $quiz = Quiz::findOrFail($quiz_id);

        Log::info($quiz->notes);

        $lessons = Lesson::get();

        // Log::info('Editing quiz ID: ' . $quiz);

        return view('admin.quizzes.edit-quiz', compact('quiz', 'lessons'));
    }


    public function updateQuiz(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'choices_type' => 'required|in:media,text',
            'lesson_id' => 'required',

            'notes' => 'required|array|min:1',
            'notes.*.id' => 'nullable|exists:notes,id',
            'notes.*.note_text' => 'required|string',
            'notes.*.media' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,mp4,webm|max:10240',
            'notes.*.remove_media' => 'nullable',
            'deleted_notes' => 'nullable',

            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|exists:questions,id',
            'questions.*.question_text' => 'required|string',
            'questions.*.choices' => 'required|array|min:2',
            'questions.*.correct_choice' => 'required|numeric',
            'questions.*.media' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,mp4,webm|max:10240',
            'questions.*.remove_media' => 'nullable',
            'deleted_questions' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            $quiz->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'choices_type' => $validated['choices_type'],
                'lesson_id' => $validated['lesson_id'],
            ]);

            // Notes
            $noteIds = [];
            foreach ($validated['notes'] as $i => $noteData) {
                $note = isset($noteData['id'])
                    ? Note::find($noteData['id'])
                    : new Note();

                $note->quiz_id = $quiz->id;
                $note->note_text = $noteData['note_text'];

                // Media
                if (!empty($noteData['remove_media'])) {
                    if ($note->media_path) Storage::disk('public')->delete($note->media_path);
                    $note->media_path = null;
                } elseif ($request->file("notes.$i.media")) {
                    if ($note->media_path) Storage::disk('public')->delete($note->media_path);
                    $note->media_path = $request->file("notes.$i.media")->store('note-media', 'public');
                }

                $note->save();
                $noteIds[] = $note->id;
            }

            Note::where('quiz_id', $quiz->id)->whereNotIn('id', $noteIds)->get()->each(function ($note) {
                if ($note->media_path) Storage::disk('public')->delete($note->media_path);
                $note->delete();
            });

            // Questions & Choices
            $questionIds = [];
            $choiceIds = [];

            foreach ($validated['questions'] as $qIdx => $qData) {
                $question = isset($qData['id'])
                    ? Question::find($qData['id'])
                    : new Question();

                $question->quiz_id = $quiz->id;
                $question->question_text = $qData['question_text'];

                // Question media
                if (!empty($qData['remove_media'])) {
                    if ($question->media_path) Storage::disk('public')->delete($question->media_path);
                    $question->media_path = null;
                } elseif ($request->file("questions.$qIdx.media")) {
                    if ($question->media_path) Storage::disk('public')->delete($question->media_path);
                    $question->media_path = $request->file("questions.$qIdx.media")->store('question-media', 'public');
                }

                $question->save();
                $questionIds[] = $question->id;

                // Choices
                foreach ($qData['choices'] as $cIdx => $cData) {
                    $choice = isset($cData['id'])
                        ? Choice::find($cData['id'])
                        : new Choice();

                    $choice->question_id = $question->id;

                    if ($validated['choices_type'] === 'text') {
                        $choice->choice_text = $cData['choice_text'];
                        $choice->choice_media = null;
                    } else {
                        $choice->choice_text = null;

                        if (!empty($cData['remove_media'])) {
                            if ($choice->choice_media) Storage::disk('public')->delete($choice->choice_media);
                            $choice->choice_media = null;
                        } elseif ($request->file("questions.$qIdx.choices.$cIdx.choice_media")) {
                            if ($choice->choice_media) Storage::disk('public')->delete($choice->choice_media);
                            $choice->choice_media = $request->file("questions.$qIdx.choices.$cIdx.choice_media")
                                ->store('choice-media', 'public');
                        }
                    }

                    $choice->is_correct = ($cIdx == $qData['correct_choice']);
                    $choice->save();
                    $choiceIds[] = $choice->id;
                }
            }

            Question::where('quiz_id', $quiz->id)->whereNotIn('id', $questionIds)->get()->each(function ($q) {
                // Delete choices & their media first
                $q->choices->each(function ($choice) {
                    if ($choice->choice_media) {
                        Storage::disk('public')->delete($choice->choice_media);
                    }
                    $choice->delete();
                });

                // Then delete question media
                if ($q->media_path) {
                    Storage::disk('public')->delete($q->media_path);
                }

                $q->delete();
            });

            Choice::whereIn('question_id', $questionIds)->whereNotIn('id', $choiceIds)->get()->each(function ($c) {
                if ($c->choice_media) Storage::disk('public')->delete($c->choice_media);
                $c->delete();
            });

            DB::commit();

            session()->flash('success', `Quiz edited successfully!`);

            return response()->json([
                'success' => true,
                'redirect' => route('admin.dashboard', ['message' => 'Quiz edited successfully!'])
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Quiz update failed: {$e->getMessage()}");
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quiz. Please try again.',
            ], 500);
        }
    }


    public function deleteQuiz($id)
    {
        Log::info('Deleting quiz ID: ' . $id);

        $quiz = Quiz::with('questions.choices')->findOrFail($id);

        try {
            // delete related media files
            foreach ($quiz->questions as $question) {
                if ($question->media_path) {
                    Storage::disk('public')->delete($question->media_path);
                }

                foreach ($question->choices as $choice) {
                    if ($choice->choice_media) {
                        Storage::disk('public')->delete($choice->choice_media);
                    }
                }
            }

            // delete related media files
            foreach ($quiz->notes as $note) {
                if ($note->media_path) {
                    Storage::disk('public')->delete($note->media_path);
                }
            }

            // -> cascade to source
            // choice->question->notes->quiz
            $quiz->delete();

            session()->flash('success', 'Quiz deleted successfully!');
            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            Log::error('Failed to delete quiz: ' . $e->getMessage());

            session()->flash('error', 'Failed to delete quiz. Please try again.');
            return redirect()->route('admin.dashboard');
        }
    }

    public function storeLesson()
    {
        $validated = request()->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'required_level' => 'required|integer|min:1',
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

    public function getLesson($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            return response()->json($lesson);
        } catch (\Exception $e) {
            Log::error("Failed to fetch lesson with ID $id: " . $e->getMessage());
            return response()->json(['error' => 'Lesson not found.'], 404);
        }
    }

    public function updateLesson(Request $request, $id)
    {
        try {
            $lesson = Lesson::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'required_level' => 'required|integer|min:1',
            ]);

            $lesson->update([
                'title' => $request->title,
                'description' => $request->description,
                'required_level' => $request->required_level,
            ]);

            return response()->json($lesson);
        } catch (\Exception $e) {
            Log::error("Failed to update lesson with ID $id: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update lesson.'], 500);
        }
    }

    public function deleteLesson($id)
    {
        try {
            $lesson = Lesson::findOrFail($id);
            $lesson->delete();

            return response()->json(['message' => 'Lesson deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Failed to delete lesson with ID $id: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete lesson.'], 500);
        }
    }
}
