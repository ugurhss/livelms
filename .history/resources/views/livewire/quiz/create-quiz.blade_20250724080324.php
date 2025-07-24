<!-- resources/views/livewire/quiz-form.blade.php -->
<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="saveQuiz">
        <div class="card">
            <div class="card-header">
                <h3>{{ $isEditing ? 'Edit Quiz' : 'Create New Quiz' }}</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" wire:model="quiz.title">
                    @error('quiz.title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" wire:model="quiz.description" rows="3"></textarea>
                    @error('quiz.description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="time_limit">Time Limit (minutes)</label>
                            <input type="number" class="form-control" id="time_limit" wire:model="quiz.time_limit">
                            @error('quiz.time_limit') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="passing_score">Passing Score (%)</label>
                            <input type="number" class="form-control" id="passing_score" wire:model="quiz.passing_score" min="1" max="100">
                            @error('quiz.passing_score') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="course_id">Course</label>
                            <select class="form-control" id="course_id" wire:model="quiz.course_id">
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('quiz.course_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="datetime-local" class="form-control" id="start_date" wire:model="quiz.start_date">
                            @error('quiz.start_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="datetime-local" class="form-control" id="end_date" wire:model="quiz.end_date">
                            @error('quiz.end_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_published" wire:model="quiz.is_published">
                    <label class="form-check-label" for="is_published">Publish Quiz</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    {{ $isEditing ? 'Update Quiz' : 'Create Quiz' }}
                </button>
            </div>
        </div>
    </form>

    @if ($isEditing)
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Questions</h3>
                <button class="btn btn-success" wire:click="addQuestion">
                    <i class="fas fa-plus"></i> Add Question
                </button>
            </div>
            <div class="card-body">
                @if (count($questions) > 0)
                    <div class="list-group">
                        @foreach ($questions as $index => $question)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $question['question_text'] }}</strong>
                                    <div class="text-muted">
                                        Type: {{ ucfirst(str_replace('_', ' ', $question['question_type'])) }} |
                                        Points: {{ $question['points'] }}
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary" wire:click="editQuestion({{ $index }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="deleteQuestion({{ $index }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        No questions added yet.
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if ($showQuestionForm)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ isset($currentQuestion['id']) ? 'Edit Question' : 'Add New Question' }}
                        </h5>
                        <button type="button" class="close" wire:click="$set('showQuestionForm', false)">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="saveQuestion">
                            <div class="form-group">
                                <label>Question Text</label>
                                <textarea class="form-control" wire:model="currentQuestion.question_text" rows="3"></textarea>
                                @error('currentQuestion.question_text') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Question Type</label>
                                        <select class="form-control" wire:model="currentQuestion.question_type">
                                            <option value="multiple_choice">Multiple Choice</option>
                                            <option value="true_false">True/False</option>
                                            <option value="short_answer">Short Answer</option>
                                        </select>
                                        @error('currentQuestion.question_type') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Points</label>
                                        <input type="number" class="form-control" wire:model="currentQuestion.points" min="1">
                                        @error('currentQuestion.points') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            @if ($currentQuestion['question_type'] === 'multiple_choice')
                                <div class="form-group">
                                    <label>Answers</label>
                                    <div class="answers-container">
                                        @foreach ($currentQuestion['answers'] as $answerIndex => $answer)
                                            <div class="answer-item mb-2 d-flex align-items-center">
                                                <input type="text" class="form-control mr-2"
                                                    wire:model="currentQuestion.answers.{{ $answerIndex }}.text">
                                                <div class="form-check mr-2">
                                                    <input type="radio" class="form-check-input"
                                                        wire:model="currentQuestion.answers.{{ $answerIndex }}.is_correct"
                                                        name="correctAnswer" value="1">
                                                    <label class="form-check-label">Correct</label>
                                                </div>
                                                @if (count($currentQuestion['answers']) > 2)
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        wire:click="removeAnswer({{ $answerIndex }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-secondary mt-2"
                                        wire:click="addAnswer">
                                        <i class="fas fa-plus"></i> Add Answer
                                    </button>
                                    @error('currentQuestion.answers') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="$set('showQuestionForm', false)">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Save Question
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
