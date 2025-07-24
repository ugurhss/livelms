<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Add Questions to Quiz: {{ $quiz->title }}</h1>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-8">
            <h2 class="text-xl font-semibold mb-4">Add New Question</h2>

            <form wire:submit.prevent="addQuestion">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="question_text">
                        Question Text
                    </label>
                    <textarea wire:model="question_text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="question_text" rows="3" placeholder="Enter your question"></textarea>
                    @error('question_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="question_type">
                            Question Type
                        </label>
                        <select wire:model="question_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                            <option value="short_answer">Short Answer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="points">
                            Points
                        </label>
                        <input wire:model="points" type="number" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('points') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Answers
                        <button type="button" wire:click="addAnswer" class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded text-xs">
                            Add Answer
                        </button>
                    </label>

                    @foreach($answers as $index => $answer)
                        <div class="flex items-center mb-2">
                            <input wire:model="answers.{{ $index }}.answer_text" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Answer text">

                            @if($question_type !== 'short_answer')
                                <label class="flex items-center ml-4">
                                    <input wire:model="answers.{{ $index }}.is_correct" type="{{ $question_type === 'multiple_choice' ? 'radio' : 'checkbox' }}" name="correct_answer" class="form-{{ $question_type === 'multiple_choice' ? 'radio' : 'checkbox' }}">
                                    <span class="ml-2">Correct</span>
                                </label>
                            @endif

                            @if(count($answers) > 2)
                                <button type="button" wire:click="removeAnswer({{ $index }})" class="ml-2 text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                        @error('answers.'.$index.'.answer_text') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @endforeach
                    @error('answers') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Add Question
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8">
            <h2 class="text-xl font-semibold mb-4">Quiz Questions ({{ $questions->count() }})</h2>

            @if($questions->isEmpty())
                <p class="text-gray-500">No questions added yet.</p>
            @else
                <div class="space-y-6">
                    @foreach($questions as $question)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-medium">{{ $question->question_text }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ ucfirst(str_replace('_', ' ', $question->question_type)) }} - {{ $question->points }} points
                                    </p>
                                </div>
                                <button wire:click="deleteQuestion({{ $question->id }})" class="text-red-500 hover:text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            @if($question->answers->isNotEmpty())
                                <div class="mt-3 space-y-2">
                                    @foreach($question->answers as $answer)
                                        <div class="flex items-center">
                                            <p class="{{ $answer->is_correct ? 'font-bold text-green-600' : 'text-gray-600' }}">
                                                {{ $answer->answer_text }}
                                                @if($answer->is_correct)
                                                    <span class="ml-2">✓</span>
                                                @endif
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


</div>
