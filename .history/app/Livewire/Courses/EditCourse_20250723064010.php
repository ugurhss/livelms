<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\Category;
use App\Models\Level;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditCourse extends Component
{
    use WithFileUploads;

    public $course;
    public $courseId;

    public $form = [
        'title' => '',
        'description' => '',
        'price' => 0,
        'category_id' => '',
        'level_id' => '',
        'outcomes' => [],
        'prerequisites' => [],
        'lessons' => [],
    ];

    public $newOutcome = '';
    public $newPrerequisite = '';
    public $currentImage;
    public $image;

    public $currentLesson = [
        'title' => '',
        'description' => '',
        'video_url' => '',
        'duration_minutes' => 30,
        'is_free' => false,
    ];

    protected $rules = [
        'form.title' => 'required|string|max:255',
        'form.description' => 'required|string',
        'form.price' => 'required|numeric|min:0',
        'form.category_id' => 'required|exists:categories,id',
        'form.level_id' => 'required|exists:levels,id',
        'image' => 'nullable|image|max:2048',
    ];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
        $this->course = Course::with('lessons')->findOrFail($courseId);

        // Yetki kontrolü
        if (auth()->id() !== $this->course->user_id) {
            abort(403, 'Bu kursu düzenleme yetkiniz yok.');
        }

        $this->form = [
            'title' => $this->course->title,
            'description' => $this->course->description,
            'price' => $this->course->price,
            'category_id' => $this->course->category_id,
            'level_id' => $this->course->level_id,
            'outcomes' => $this->course->outcomes ?? [],
            'prerequisites' => $this->course->prerequisites ?? [],
            'lessons' => $this->course->lessons->map(function ($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'description' => $lesson->description,
                    'video_url' => $lesson->video_url,
                    'duration_minutes' => $lesson->duration_minutes,
                    'is_free' => $lesson->is_free,
                ];
            })->toArray(),
        ];

        $this->currentImage = $this->course->image;
    }

    public function addOutcome()
    {
        $this->validate(['newOutcome' => 'required|string|max:255']);
        $this->form['outcomes'][] = $this->newOutcome;
        $this->newOutcome = '';
    }

    public function removeOutcome($index)
    {
        unset($this->form['outcomes'][$index]);
        $this->form['outcomes'] = array_values($this->form['outcomes']);
    }

    public function addPrerequisite()
    {
        $this->validate(['newPrerequisite' => 'required|string|max:255']);
        $this->form['prerequisites'][] = $this->newPrerequisite;
        $this->newPrerequisite = '';
    }

    public function removePrerequisite($index)
    {
        unset($this->form['prerequisites'][$index]);
        $this->form['prerequisites'] = array_values($this->form['prerequisites']);
    }

    public function addLesson()
    {
        $this->validate([
            'currentLesson.title' => 'required|string|max:255',
            'currentLesson.description' => 'required|string',
            'currentLesson.video_url' => 'required|url',
            'currentLesson.duration_minutes' => 'required|integer|min:1',
        ]);

        $this->form['lessons'][] = $this->currentLesson;
        $this->resetCurrentLesson();
    }

    public function removeLesson($index)
    {
        unset($this->form['lessons'][$index]);
        $this->form['lessons'] = array_values($this->form['lessons']);
    }

    public function removeImage()
    {
        $this->currentImage = null;
    }

    private function resetCurrentLesson()
    {
        $this->currentLesson = [
            'title' => '',
            'description' => '',
            'video_url' => '',
            'duration_minutes' => 30,
            'is_free' => false,
        ];
    }

   public function save()
{
    // Validasyonu çalıştır
    $this->validate();

    // Veritabanı transaction'ı başlat
    DB::beginTransaction();

    try {
        $data = $this->form;

        // Resim yükleme işlemi
        if ($this->image) {
            // Yeni resmi yükle
            $data['image'] = $this->image->store('course_images', 'public');

            // Eski resmi sil (varsa)
            if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                Storage::disk('public')->delete($this->currentImage);
            }
        } else {
            // Resim silinmişse null yap, aksi halde mevcut resmi koru
            $data['image'] = $this->currentImage ?: null;
        }

        // Ders verilerini işle
        if (isset($data['lessons'])) {
            foreach ($data['lessons'] as &$lesson) {
                // Eksik alanları default değerlerle doldur
                $lesson['duration_minutes'] = $lesson['duration_minutes'] ?? 30;
                $lesson['is_free'] = $lesson['is_free'] ?? false;
            }
            unset($lesson); // Referansı kaldır
        }

        // Kursu güncelle
        $service = app(\App\Services\CourseService::class);
        $updatedCourse = $service->updateCourse($this->courseId, $data);

        // Transaction'ı onayla
        DB::commit();

        // Başarılı yönlendirme
        return redirect()->route('courses.show', $this->courseId)
                         ->with('success', 'Kurs başarıyla güncellendi!');

    } catch (\Exception $e) {
        // Hata durumunda geri al
        DB::rollBack();

        // Log kaydı
        logger()->error('Course update failed: '.$e->getMessage(), [
            'course_id' => $this->courseId,
            'user_id' => auth()->id(),
            'exception' => $e
        ]);

        // Kullanıcıya hata mesajı göster
        $this->addError('general', 'Kurs güncellenirken bir hata oluştu: '.$e->getMessage());

        // Sayfada kal
        return;
    }
}

    public function render()
    {
        return view('livewire.courses.edit-course', [
            'categories' => Category::all(),
            'levels' => Level::all(),
        ]);
    }
}
