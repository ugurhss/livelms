<div>
    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Course Title</label>
                <input wire:model="title" type="text" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Thumbnail -->
            {{-- <div>
                <label for="thumbnail" class="block text-sm font-medium text-gray-700">Thumbnail</label>
                <input wire:model="thumbnail" type="file" id="thumbnail" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('thumbnail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                @if($thumbnail)
                    <div class="mt-2">
                        <img src="{{ $thumbnail->temporaryUrl() }}" class="h-20 w-auto">
                    </div>
                @endif
            </div> --}}

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <input wire:model="category" type="text" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Level -->
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                <select wire:model="level" id="level" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                </select>
                @error('level') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Pricing -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input wire:model="price" type="number" step="0.01" id="price" class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0.00">
                    </div>
                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="original_price" class="block text-sm font-medium text-gray-700">Original Price</label>
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input wire:model="original_price" type="number" step="0.01" id="original_price" class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="0.00">
                    </div>
                    @error('original_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select wire:model="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
                @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Outcomes -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Learning Outcomes</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input wire:model="currentOutcome" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Add an outcome">
                    <button wire:click.prevent="addOutcome" type="button" class="ml-3 inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Add
                    </button>
                </div>
                @error('currentOutcome') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
{{--
                <ul class="mt-2 space-y-1">
                    @foreach($outcomes as $index => $outcome)
                        <li class="flex items-center justify-between py-1 pl-3 pr-2 text-sm bg-gray-50 rounded">
                            <span>{{ $outcome }}</span>
                            <button wire:click.prevent="removeOutcome({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </li>
                    @endforeach
                </ul> --}}
            </div>

            <!-- Prerequisites -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Prerequisites</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input wire:model="currentPrerequisite" type="text" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Add a prerequisite">
                    <button wire:click.prevent="addPrerequisite" type="button" class="ml-3 inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Add
                    </button>
                </div>
                @error('currentPrerequisite') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                <ul class="mt-2 space-y-1">
                    @foreach($prerequisites as $index => $prerequisite)
                        <li class="flex items-center justify-between py-1 pl-3 pr-2 text-sm bg-gray-50 rounded">
                            <span>{{ $prerequisite }}</span>
                            <button wire:click.prevent="removePrerequisite({{ $index }})" type="button" class="text-red-500 hover:text-red-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Create Course
                </button>
            </div>
        </div>
    </form>
</div>
