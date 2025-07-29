<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Note;
use App\Models\Choice;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $lessons = [1, 2, 3]; // Assuming these lesson IDs already exist

        foreach ($lessons as $lessonId) {
            for ($quizNum = 1; $quizNum <= 3; $quizNum++) {
                $quiz = Quiz::create([
                    'title' => "Quiz $quizNum for Lesson $lessonId",
                    'description' => "This is quiz $quizNum description for lesson $lessonId.",
                    'choices_type' => 'text',
                    'lesson_id' => $lessonId,
                ]);

                $questionCount = rand(5, 8);
                for ($q = 1; $q <= $questionCount; $q++) {
                    $question = Question::create([
                        'quiz_id' => $quiz->id,
                        'question_text' => "Question $q for Quiz $quizNum (Lesson $lessonId)",
                        'media_path' => 'media/media_placeholder.webp',
                    ]);

                    $correctAnswer = "Correct Answer $q";

                    Note::create([
                        'quiz_id' => $quiz->id,
                        'note_text' => $correctAnswer,
                        'media_path' => 'media/media_placeholder.webp',
                        'order' => $q,
                    ]);

                    // Create 4 choices: first one is correct
                    Choice::create([
                        'question_id' => $question->id,
                        'choice_text' => $correctAnswer,
                        'choice_media' => 'media/media_placeholder.webp',
                        'is_correct' => true,
                    ]);

                    for ($i = 1; $i <= 3; $i++) {
                        Choice::create([
                            'question_id' => $question->id,
                            'choice_text' => "Wrong Answer $q.$i",
                            'choice_media' => 'media/media_placeholder.webp',
                            'is_correct' => false,
                        ]);
                    }
                }
            }
        }
    }
}
