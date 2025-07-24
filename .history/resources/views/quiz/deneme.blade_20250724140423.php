<x-layouts.app>

<!-- resources/views/courses/quizzes/create.blade.php -->
@extends('layouts.app')

@section('content')
    @livewire('quiz.create-quiz-with-questions', ['course' => $course])
@endsection
</x-layouts.app>
