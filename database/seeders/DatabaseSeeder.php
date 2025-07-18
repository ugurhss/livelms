<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Review;
use App\Models\Category;
use App\Models\Assignment;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;
use App\Models\LessonCompletion;
use Illuminate\Support\Facades\DB;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Tabloları temizlemeden önce varlıklarını kontrol et
        $tables = [
            'users', 'categories', 'courses', 'lessons',
            'enrollments', 'lesson_completions', 'reviews',
            'assignments', 'assignment_submissions'
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'bio' => 'System administrator',
            'avatar' => 'https://ui-avatars.com/api/?name=Admin+User&background=random'
        ]);

        // Create instructors
        $instructor1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'bio' => 'Web development expert with 10 years of experience',
            'avatar' => 'https://ui-avatars.com/api/?name=John+Doe&background=random'
        ]);

        $instructor2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'instructor',
            'bio' => 'Data science specialist and machine learning engineer',
            'avatar' => 'https://ui-avatars.com/api/?name=Jane+Smith&background=random'
        ]);

        // Create students
        $student1 = User::create([
            'name' => 'Student One',
            'email' => 'student1@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'bio' => 'Aspiring web developer',
            'avatar' => 'https://ui-avatars.com/api/?name=Student+One&background=random'
        ]);

        $student2 = User::create([
            'name' => 'Student Two',
            'email' => 'student2@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'bio' => 'Learning data science',
            'avatar' => 'https://ui-avatars.com/api/?name=Student+Two&background=random'
        ]);

        // Create categories
        $webDevCategory = Category::create([
            'name' => 'Web Development',
            'slug' => 'web-development',
            'description' => 'Learn to build modern web applications',
            'icon' => 'fas fa-code'
        ]);

        $dataScienceCategory = Category::create([
            'name' => 'Data Science',
            'slug' => 'data-science',
            'description' => 'Master data analysis and machine learning',
            'icon' => 'fas fa-chart-line'
        ]);

        $mobileDevCategory = Category::create([
            'name' => 'Mobile Development',
            'slug' => 'mobile-development',
            'description' => 'Build apps for iOS and Android',
            'icon' => 'fas fa-mobile-alt'
        ]);

        // Create courses
        $laravelCourse = Course::create([
            'title' => 'Laravel From Scratch',
            'description' => 'Learn Laravel framework from the ground up',
            'slug' => 'laravel-from-scratch',
            'thumbnail' => 'https://via.placeholder.com/640x360.png?text=Laravel+Course',
            'category' => 'web-development',
            'level' => 'intermediate',
            'price' => 49.99,
            'original_price' => 99.99,
            'status' => 'published',
            'outcomes' => ['Build RESTful APIs', 'Create authentication systems', 'Work with databases'],
            'prerequisites' => ['Basic PHP knowledge', 'Understanding of MVC'],
            'user_id' => $instructor1->id
        ]);

        $reactCourse = Course::create([
            'title' => 'React Masterclass',
            'description' => 'Become a React expert with this comprehensive course',
            'slug' => 'react-masterclass',
            'thumbnail' => 'https://via.placeholder.com/640x360.png?text=React+Course',
            'category' => 'web-development',
            'level' => 'intermediate',
            'price' => 59.99,
            'original_price' => 119.99,
            'status' => 'published',
            'outcomes' => ['Build complex React applications', 'Use React hooks effectively', 'Implement state management'],
            'prerequisites' => ['JavaScript fundamentals', 'Basic HTML/CSS'],
            'user_id' => $instructor1->id
        ]);

        $pythonCourse = Course::create([
            'title' => 'Python for Data Science',
            'description' => 'Learn Python for data analysis and visualization',
            'slug' => 'python-data-science',
            'thumbnail' => 'https://via.placeholder.com/640x360.png?text=Python+Course',
            'category' => 'data-science',
            'level' => 'beginner',
            'price' => 39.99,
            'original_price' => 79.99,
            'status' => 'published',
            'outcomes' => ['Data analysis with Pandas', 'Data visualization with Matplotlib', 'Basic machine learning'],
            'prerequisites' => ['Basic programming concepts'],
            'user_id' => $instructor2->id
        ]);

        // Create lessons for Laravel course
        $laravelLesson1 = Lesson::create([
            'title' => 'Introduction to Laravel',
            'slug' => 'introduction-to-laravel',
            'description' => 'Overview of Laravel framework and its features',
            'duration_minutes' => 45,
            'video_url' => 'https://example.com/videos/laravel-intro.mp4',
            'order' => 1,
            'is_free' => true,
            'course_id' => $laravelCourse->id
        ]);

        $laravelLesson2 = Lesson::create([
            'title' => 'Routing in Laravel',
            'slug' => 'routing-in-laravel',
            'description' => 'Learn how to define routes in Laravel applications',
            'duration_minutes' => 60,
            'video_url' => 'https://example.com/videos/laravel-routing.mp4',
            'order' => 2,
            'is_free' => false,
            'course_id' => $laravelCourse->id
        ]);

        // Create lessons for React course
        $reactLesson1 = Lesson::create([
            'title' => 'React Components',
            'slug' => 'react-components',
            'description' => 'Learn about React components and props',
            'duration_minutes' => 50,
            'video_url' => 'https://example.com/videos/react-components.mp4',
            'order' => 1,
            'is_free' => true,
            'course_id' => $reactCourse->id
        ]);

        $reactLesson2 = Lesson::create([
            'title' => 'React Hooks',
            'slug' => 'react-hooks',
            'description' => 'Master useState, useEffect and other React hooks',
            'duration_minutes' => 75,
            'video_url' => 'https://example.com/videos/react-hooks.mp4',
            'order' => 2,
            'is_free' => false,
            'course_id' => $reactCourse->id
        ]);

        // Create enrollments
        $enrollment1 = Enrollment::create([
            'user_id' => $student1->id,
            'course_id' => $laravelCourse->id,
            'progress' => 50,
            'completed_at' => null
        ]);

        $enrollment2 = Enrollment::create([
            'user_id' => $student2->id,
            'course_id' => $reactCourse->id,
            'progress' => 25,
            'completed_at' => null
        ]);

        // Create lesson completions
        LessonCompletion::create([
            'user_id' => $student1->id,
            'lesson_id' => $laravelLesson1->id
        ]);

        LessonCompletion::create([
            'user_id' => $student2->id,
            'lesson_id' => $reactLesson1->id
        ]);

        // Create reviews
        Review::create([
            'course_id' => $laravelCourse->id,
            'user_id' => $student1->id,
            'rating' => 5,
            'comment' => 'Excellent course! Very comprehensive.'
        ]);

        Review::create([
            'course_id' => $reactCourse->id,
            'user_id' => $student2->id,
            'rating' => 4,
            'comment' => 'Great content, but some sections could be more detailed.'
        ]);

        // Create assignments
        $laravelAssignment = Assignment::create([
            'course_id' => $laravelCourse->id,
            'title' => 'Build a Blog System',
            'description' => 'Create a simple blog system with CRUD operations',
            'due_date' => now()->addDays(14),
            'points' => 100,
            'file_path' => null
        ]);

        $reactAssignment = Assignment::create([
            'course_id' => $reactCourse->id,
            'title' => 'Todo App',
            'description' => 'Build a todo application with React',
            'due_date' => now()->addDays(10),
            'points' => 80,
            'file_path' => null
        ]);

        // Create assignment submissions
        AssignmentSubmission::create([
            'assignment_id' => $laravelAssignment->id,
            'user_id' => $student1->id,
            'file_path' => 'submissions/laravel-blog.zip',
            'notes' => 'Here is my blog system implementation',
            'grade' => 90,
            'feedback' => 'Good work! Just need to handle some edge cases.'
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $reactAssignment->id,
            'user_id' => $student2->id,
            'file_path' => 'submissions/react-todo.zip',
            'notes' => 'My todo app submission',
            'grade' => null,
            'feedback' => null
        ]);
    }
}
