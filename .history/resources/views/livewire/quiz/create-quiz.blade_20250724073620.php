<div>
    <div class="container mx-auto px-4 py-8">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="saveQuiz">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h2 class="text-xl font-bold mb-6">{{ $quizId ? 'Edit Quiz' : 'Create New Quiz' }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="course_id">
                                Course
                            </label>
                            <select wire:model="course_id" id="course_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ $course->id == $course_id ? 'selected' : '' }}>{{ $course->title }}</option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                                Quiz Title
                            </label>
                            <input wire:model="title" id="title" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                                Description
                            </label>
                            <textarea wire:model="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="time_limit">
                                Time Limit (minutes)
                            </label>
                            <input wire:model="time_limit" id="time_limit" type="number" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('time_limit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                                Start Date
                            </label>
                            <input wire:model="start_date" id="start_date" type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                                End Date
                            </label>
                            <input wire:model="end_date" id="end_date" type="datetime-local" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('end_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="passing_score">
                                Passing Score (%)
                            </label>
                            <input wire:model="passing_score" id="passing_score" type="number" min="1" max="100" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('passing_score') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input wire:model="is_published" type="checkbox" class="form-checkbox">
                                <span class="ml-2 text-gray-700">Publish Quiz</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        {{ $quizId ? 'Update Quiz' : 'Create Quiz' }}
                    </button>
                </div>
            </div>
        </form>

        @if($quizId)
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="text-xl font-bold mb-6">Questions</h2>

            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">{{ isset($newQuestion['id']) ? 'Edit Question' : 'Add New Question' }}</h3>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Question Text</label>
                        <textarea wire:model="newQuestion.question_text" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        @error('newQuestion.question_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Question Type</label>
                            <select wire:model="newQuestion.question_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="true_false">True/False</option>
                                <option value="short_answer">Short Answer</option>
                            </select>
                            @error('newQuestion.question_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Points</label>
                            <input wire:model="newQuestion.points" type="number" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            @error('newQuestion.points') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if(in_array($newQuestion['question_type'], ['multiple_choice', 'true_false']))
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Answers</label>
                            <div class="space-y-2">
                                @foreach($newQuestion['answers'] as $index => $answer)
                                    <div class="flex items-center space-x-2">
                                        <input wire:model="newQuestion.answers.{{ $index }}.answer_text" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                        <input wire:model="newQuestion.answers.{{ $index }}.is_correct" type="radio" name="correct_answer" value="{{ $index }}" class="form-radio h-4 w-4 text-blue-600">
                                        <span class="text-sm text-gray-600">Correct</span>
                                        <button type="button" wire:click="removeAnswer({{ $index }})" class="text-red-500 hover:text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                    @error('newQuestion.answers.'.$index.'.answer_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                @endforeach
                            </div>
                            <button type="button" wire:click="addAnswer" class="mt-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-3 rounded text-sm">
                                Add Answer
                            </button>
                        </div>
                    @endif

                    <div class="flex justify-end space-x-2">
                        @if(isset($newQuestion['id']))
                            <button type="button" wire:click="updateQuestion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update Question
                            </button>
                            <button type="button" wire:click="resetNewQuestion" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Cancel
                            </button>
                        @else
                            <button type="button" wire:click="addQuestion" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Add Question
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($questions as $index => $question)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold">{{ $question['question_text'] }}</h4>
                                <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $question['question_type'])) }} - {{ $question['points'] }} points</p>
                            </div>
                            <div class="flex space-x-2">
                                <button wire:click="editQuestion({{ $index }})" class="text-blue-500 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </button>
                                <button wire:click="deleteQuestion({{ $question['id'] }})" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if($question['question_type'] !== 'short_answer' && !empty($question['answers']))
                            <div class="mt-2 ml-4 space-y-1">
                                @foreach($question['answers'] as $answer)
                                    <div class="flex items-center">
                                        <span class="mr-2">{{ $answer['answer_text'] }}</span>
                                        @if($answer['is_correct'])
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-0.5 rounded">Correct</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
