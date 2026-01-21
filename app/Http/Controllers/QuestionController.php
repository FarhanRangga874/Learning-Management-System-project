<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Lesson $lesson)
    {
        // Pastikan tipe assignment
        if ($lesson->type !== 'assignment') {
            return back()->with('error', 'Fitur ini hanya untuk tipe Assignment.');
        }
        
        $questions = $lesson->questions()->orderBy('id', 'asc')->get();
        return view('admin.questions.index', compact('lesson', 'questions'));
    }

    public function store(Request $request, Lesson $lesson)
    {
        $request->validate([
            'question_text' => 'required',
            'type' => 'required|in:multiple_choice,essay',
            'points' => 'required|integer',
            'option_a' => 'required_if:type,multiple_choice',
            'option_b' => 'required_if:type,multiple_choice',
            'correct_answer' => 'required_if:type,multiple_choice',
        ]);

        $options = null;
        if ($request->type == 'multiple_choice') {
            $options = [
                'A' => $request->option_a,
                'B' => $request->option_b,
                'C' => $request->option_c,
                'D' => $request->option_d,
            ];
        }

        Question::create([
            'lesson_id' => $lesson->id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'points' => $request->points,
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }
}