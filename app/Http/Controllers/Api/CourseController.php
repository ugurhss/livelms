<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $service;

    public function __construct(CourseService $service)
    {
       $this->service = $service;
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $courses = $this->service->getPublishedCourses($request->all());
            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $course = $this->service->getCourseDetails($id);
            return response()->json([
                'success' => true,
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

   public function store(Request $request): JsonResponse
{
    try {
        // Verileri doğrula
        $validated = $this->validateRequest($request);

        // Thumbnail'i işle (eğer varsa)
        if ($request->has('thumbnail') && $request->thumbnail) {
            $validated['thumbnail'] = $this->storeBase64Image($request->thumbnail);
        } else {
            $validated['thumbnail'] = null;
        }

        // Kullanıcı ID'sini ekle
        $validated['user_id'] = auth()->id();

        // Kursu oluştur
        $course = $this->service->createCourse($validated);

        return response()->json([
            'success' => true,
            'data' => $course,
            'message' => 'Kurs başarıyla oluşturuldu'
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}

protected function validateRequest(Request $request): array
{
    return $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'category' => 'nullable|string',
        'level' => 'required|string|in:beginner,intermediate,advanced',
        'price' => 'required|numeric|min:0',
        'original_price' => 'required|numeric|min:0',
        'thumbnail' => 'nullable|string', // base64 encoded image
        'outcomes' => 'required|array',
        'outcomes.*' => 'required|string',
        'prerequisites' => 'required|array',
        'prerequisites.*' => 'required|string',
        'lessons' => 'required|array',
        'lessons.*.title' => 'required|string|max:255',
        'lessons.*.description' => 'required|string',
        'lessons.*.duration_minutes' => 'required|integer|min:1',
        'lessons.*.video_url' => 'required|url',
    ]);
}

protected function storeBase64Image(string $base64): ?string
{
    try {
        // Base64'ün başlık kısmını kaldır (eğer varsa)
        if (strpos($base64, ';base64,') !== false) {
            [$_, $base64] = explode(';base64,', $base64);
        }

        // Base64'ü decode et
        $imageData = base64_decode($base64);

        // Resim mi kontrol et
        if (!@imagecreatefromstring($imageData)) {
            throw new \Exception('Geçersiz resim formatı');
        }

        // Benzersiz dosya adı oluştur
        $fileName = 'thumbnails/' . uniqid() . '.jpg';

        // Dosyayı kaydet
        \Storage::disk('public')->put($fileName, $imageData);

        return $fileName;

    } catch (\Exception $e) {
        Log::error('Thumbnail kaydedilemedi: ' . $e->getMessage());
        return null;
    }
}

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $course = $this->service->updateCourse($id, $request->validated());
            return response()->json([
                'success' => true,
                'data' => $course,
                'message' => 'Kurs başarıyla güncellendi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->deleteCourse($id);
            return response()->json([
                'success' => true,
                'message' => 'Kurs başarıyla silindi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function enroll(Request $request, int $courseId): JsonResponse
    {
        try {
            $this->service->enrollUser($courseId, $request->user()->id);
            return response()->json([
                'success' => true,
                'message' => 'Kursa başarıyla kaydoldunuz'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function unenroll(Request $request, int $courseId): JsonResponse
    {
        try {
            $this->service->unenrollUser($courseId, $request->user()->id);
            return response()->json([
                'success' => true,
                'message' => 'Kurs kaydınız iptal edildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function instructorCourses(Request $request): JsonResponse
    {
        try {
            $courses = $this->service->getInstructorCourses($request->user()->id, $request->all());
            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function enrolledCourses(Request $request): JsonResponse
    {
        try {
            $courses = $this->service->getEnrolledCourses($request->user()->id);
            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function stats(int $courseId): JsonResponse
    {
        try {
            $stats = $this->service->getCourseStatistics($courseId);
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function popularCourses(): JsonResponse
{
    try {
        $courses = $this->service->getPopularCourses();
        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }}

    public function search(Request $request): JsonResponse
    {
       $request->validate(['query' => 'required|string|min:3']);

        try {
            $courses = $this->service->searchCourses(
                $request->input('query'), // string olarak al
                $request->except('query') // diğer filtreler
            );
            return response()->json($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
