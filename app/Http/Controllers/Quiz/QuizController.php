<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\GenerateQuizRequest;
use App\Http\Requests\Quiz\SubmitAnswerRequest;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Patterns\Factory\QuizFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // GET /api/quizzes
    public function index(Request $request): JsonResponse
    {
        $quizzes = Quiz::active()
            ->when($request->type, fn($q) => $q->byType($request->type))
            ->withCount('attempts')
            ->latest()
            ->paginate(15);

        return $this->success($quizzes);
    }

    // GET /api/quizzes/{quiz}
    public function show(Quiz $quiz): JsonResponse
    {
        $quiz->load('questions:id,title,category,difficulty');

        return $this->success($quiz);
    }

    // POST /api/quizzes/generate
    public function generate(GenerateQuizRequest $request): JsonResponse
    {
        try {
            $quiz = QuizFactory::create([
                ...$request->validated(),
                'created_by' => auth()->id(),
            ]);

            return $this->created($quiz, 'Quiz generated successfully');

        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), 422);
        } catch (\InvalidArgumentException $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    // POST /api/quizzes/{quiz}/start
    public function start(Quiz $quiz): JsonResponse
    {
        $user = auth()->user();

        // Check for existing in-progress attempt
        $existingAttempt = QuizAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existingAttempt) {
            return $this->success([
                'attempt' => $existingAttempt,
                'questions' => $quiz->questions,
            ], 'Resuming existing attempt');
        }

        // Create new attempt
        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'status' => 'in_progress',
            'total_questions' => $quiz->total_questions,
            'started_at' => now(),
        ]);

        return $this->created([
            'attempt' => $attempt,
            'questions' => $quiz->questions,
        ], 'Quiz started successfully');
    }

    // POST /api/quizzes/attempts/{attempt}/answer
    public function submitAnswer(SubmitAnswerRequest $request, QuizAttempt $attempt): JsonResponse
    {
        // Only attempt owner can submit
        if ($attempt->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        if ($attempt->isCompleted()) {
            return $this->error('This attempt is already completed', 409);
        }

        // Upsert answer (allow updating answer before completing)
        $answer = $attempt->answers()->updateOrCreate(
            ['question_id' => $request->question_id],
            [
                'answer' => $request->answer,
                'self_rating' => $request->self_rating,
                'note' => $request->note,
                'time_spent_seconds' => $request->time_spent_seconds,
            ]
        );

        return $this->success($answer, 'Answer saved');
    }

    // POST /api/quizzes/attempts/{attempt}/complete
    public function complete(QuizAttempt $attempt): JsonResponse
    {
        if ($attempt->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        if ($attempt->isCompleted()) {
            return $this->error('Attempt is already completed', 409);
        }

        $attempt->complete();

        return $this->success(
            $attempt->load('answers.question'),
            'Quiz completed successfully'
        );
    }

    // GET /api/quizzes/attempts/{attempt}/result
    public function result(QuizAttempt $attempt): JsonResponse
    {
        if ($attempt->user_id !== auth()->id()) {
            return $this->forbidden();
        }

        if (!$attempt->isCompleted()) {
            return $this->error('Attempt is not yet completed', 422);
        }

        $attempt->load(['quiz', 'answers.question']);

        // Build result breakdown
        $breakdown = [
            'score_percentage' => $attempt->score_percentage,
            'points_earned' => $attempt->points_earned,
            'points_total' => $attempt->points_total,
            'correct' => $attempt->correct_answers,
            'wrong' => $attempt->wrong_answers,
            'skipped' => $attempt->skipped_answers,
            'time_taken' => $attempt->time_taken_seconds,
            'grade' => self::calculateGrade($attempt->score_percentage),
            'answers' => $attempt->answers,
        ];

        return $this->success($breakdown);
    }

    // GET /api/quizzes/my-attempts
    public function myAttempts(Request $request): JsonResponse
    {
        $attempts = QuizAttempt::where('user_id', auth()->id())
            ->with('quiz:id,title,type,difficulty')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return $this->success($attempts);
    }

    private static function calculateGrade(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default => 'F',
        };
    }
}