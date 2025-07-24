<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'level' => 'nullable|in:beginner,intermediate,advanced',
        'category_id' => 'nullable|exists:categories,id', // ✅ burada required yerine nullable
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,published,archived',
            'outcomes' => 'nullable|array',
            'outcomes.*' => 'string|max:255',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'string|max:255',
            'duration' => 'nullable|integer|min:0',
            'lessons' => 'required|array|min:1',
            'lessons.*.title' => 'required|string|max:255',
            'lessons.*.description' => 'nullable|string',
            'lessons.*.duration_minutes' => 'nullable|integer|min:0',
            'lessons.*.video_url' => 'nullable|url',
            'lessons.*.order' => 'nullable|integer|min:0',
            'lessons.*.is_free' => 'nullable|boolean',
        ];
    }
}
