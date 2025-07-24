<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    // app/Http/Requests/UpdateCourseRequest.php

public function rules()
{
    return [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'price' => 'nullable|numeric|min:0',
        'level' => 'required|in:beginner,intermediate,advanced',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'lessons' => 'nullable|array',
        'lessons.*.title' => 'required|string|max:255',
        'lessons.*.duration_minutes' => 'required|integer|min:1',
        'lessons.*.order' => 'required|integer|min:1',
        'lessons.*.description' => 'nullable|string',
        'lessons.*.free' => 'nullable|boolean',
    ];
}
}
