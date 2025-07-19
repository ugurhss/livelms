<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Varsayalım 1 numaralı kurs var
        $quiz = Quiz::create([
            'course_id'     => 1,
            'title'         => 'Laravel Temel Sınavı',
            'description'   => 'Laravel bilgilerinizi test edin.',
            'time_limit'    => 30,
            'start_date'    => now(),
            'end_date'      => now()->addDays(7),
            'passing_score' => 70,
            'is_published'  => true,
        ]);

        // Soru ekle
        $question = Question::create([
            'quiz_id'        => $quiz->id,
            'question_text'  => 'Laravel Route tanımı için doğru yöntem hangisidir?',
            'question_type'  => 'multiple_choice',
            'points'         => 5,
        ]);

        // Cevaplar
        Answer::insert([
            [
                'question_id' => $question->id,
                'answer_text' => "Route::get('url', [Controller::class, 'method']);",
                'is_correct'  => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'question_id' => $question->id,
                'answer_text' => "get.route('url', 'Controller@method');",
                'is_correct'  => false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);

        // Bir kullanıcı varsayalım (id: 1)
        $user = User::first(); // ya da User::find(1)

        // Quiz attempt
        $attempt = QuizAttempt::create([
            'user_id'     => $user->id,
            'quiz_id'     => $quiz->id,
            'started_at'  => now(),
            'completed_at'=> now()->addMinutes(5),
            'score'       => 100,
            'is_passed'   => true,
        ]);

        // Kullanıcının verdiği cevap
        UserAnswer::create([
            'attempt_id'   => $attempt->id,
            'question_id'  => $question->id,
            'answer_id'    => $question->answers()->where('is_correct', true)->first()->id,
            'is_correct'   => true,
        ]);
    }
}
