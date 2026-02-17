<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SupportController as AdminSupportController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\LessonController;
use App\Http\Controllers\Instructor\SupportController as InstructorSupportController;
use App\Http\Controllers\Instructor\StudentController as InstructorStudentController;
use App\Http\Controllers\Instructor\QuizController as InstructorQuizController;
use App\Http\Controllers\Instructor\QuestionController; 
use App\Http\Controllers\Instructor\AssignmentController as InstructorAssignmentController; 
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\MyCourseController; 
use App\Http\Controllers\Student\CertificateController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\AssignmentController as StudentAssignmentController;
use App\Http\Controllers\ChatController; // 🔥 Chat Controller eklendi
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Certificate;

/* --------------------------------------------------------------------------
   ANA SAYFA VE YÖNLENDİRME
-------------------------------------------------------------------------- */
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        if ($role === 'instructor') return redirect()->route('instructor.dashboard');
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

/* --------------------------------------------------------------------------
   MİSAFİR (GUEST) ROTALARI
-------------------------------------------------------------------------- */
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/* --------------------------------------------------------------------------
   EĞİTMEN (INSTRUCTOR) ROTALARI
-------------------------------------------------------------------------- */
Route::prefix('/instructor')->name('instructor.')->middleware(['auth', 'verified', 'role:instructor'])->group(function () {
    
    Route::get('/dashboard', [InstructorCourseController::class, 'dashboard'])->name('dashboard');
    Route::resource('/courses', InstructorCourseController::class);
    
    // 🔥 DERS YÖNETİMİ
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
    
    // 🔥 ÖDEV YÖNETİMİ
    Route::post('/courses/{course}/assignments', [InstructorAssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}/edit', [InstructorAssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/{assignment}', [InstructorAssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [InstructorAssignmentController::class, 'destroy'])->name('assignments.destroy');
    
    // 🔥 EĞİTMEN İÇİN ÖDEV TESLİMLERİNİ GÖRME ROTASI
    Route::get('/assignments/{assignment}/submissions', [InstructorAssignmentController::class, 'showSubmissions'])->name('assignments.submissions');

    // 🔥 SINAV VE SORU YÖNETİMİ
    Route::get('/quizzes/create', [InstructorQuizController::class, 'create'])->name('quizzes.create');
    Route::post('/courses/{course}/quizzes', [InstructorQuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', [InstructorQuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/update-quiz-bilgisi/{quiz}', [InstructorQuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [InstructorQuizController::class, 'destroy'])->name('quizzes.destroy');
    
    Route::post('/add-single-question/{quiz}', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('/patch-single-question/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/delete-question/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Destek ve Öğrenciler
    Route::get('/support', [InstructorSupportController::class, 'index'])->name('support.index');
    Route::get('/support/create', [InstructorSupportController::class, 'create'])->name('support.create');
    Route::post('/support', [InstructorSupportController::class, 'store'])->name('support.store');
    Route::get('/support/{supportTicket}', [InstructorSupportController::class, 'show'])->name('support.show');
    Route::get('/students', [InstructorStudentController::class, 'index'])->name('students.index');
});

/* --------------------------------------------------------------------------
   ADMIN ROTALARI
-------------------------------------------------------------------------- */
Route::prefix('/admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'students' => User::where('role', 'student')->count(), 
                'instructors' => User::where('role', 'instructor')->count(), 
                'courses' => Course::count()
            ],
            'chart' => [
                'labels' => collect(range(6, 0))->map(fn($i) => now()->subDays($i)->format('d M')),
                'data'   => collect(range(6, 0))->map(fn($i) => User::whereDate('created_at', now()->subDays($i)->toDateString())->count())
            ],
            'recentUsers' => User::latest()->take(5)->get()
        ]);
    })->name('dashboard');

    Route::resource('/users', UserController::class);
    Route::resource('/courses', AdminCourseController::class);
    Route::get('/support', [AdminSupportController::class, 'index'])->name('support.index');
    Route::get('/support/{supportTicket}', [AdminSupportController::class, 'show'])->name('support.show');
    Route::post('/support/{supportTicket}', [AdminSupportController::class, 'update'])->name('support.update');
});

/* --------------------------------------------------------------------------
   ÖĞRENCİ (STUDENT) ROTALARI
-------------------------------------------------------------------------- */
Route::prefix('/student')->name('student.')->middleware(['auth', 'verified', 'role:student'])->group(function () {
    Route::get('/dashboard', function () {
        $student = auth()->user();
        return Inertia::render('Student/Dashboard', [
            'stats' => [
                'enrolled' => $student->courses()->count(), 
                'completed' => $student->completedLessons()->count(), 
                'total_lessons' => Lesson::whereIn('course_id', $student->courses()->pluck('courses.id'))->count(),
                'certificates' => Certificate::where('user_id', $student->id)->count()
            ],
            'myCourses' => $student->courses()->with('instructor')->latest()->take(3)->get(),
            'allCourses' => Course::with('instructor')->latest()->take(6)->get()
        ]);
    })->name('dashboard');

    Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
    Route::get('/my-courses', [MyCourseController::class, 'index'])->name('my-courses');
    Route::get('/course/{slug}', [MyCourseController::class, 'show'])->name('course.show');
    Route::post('/lesson/{lesson}/complete', [MyCourseController::class, 'toggleComplete'])->name('lesson.complete');
    
    // 🔥 ÖDEV TESLİM ROTALARI
    Route::post('/assignments/{assignment}/submit', [StudentAssignmentController::class, 'submit'])->name('assignments.submit');

    Route::get('/quizzes/{id}', [StudentQuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{id}/submit', [StudentQuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/download/{code}', [CertificateController::class, 'download'])->name('certificates.download');
});

/* --------------------------------------------------------------------------
   CHAT SİSTEMİ ROTALARI (🔥 YENİ EKLENDİ)
-------------------------------------------------------------------------- */
Route::middleware(['auth', 'verified'])->group(function () {
    // Hem eğitmen hem öğrenci erişebilir
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/messages/{userId}', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});

/* --------------------------------------------------------------------------
   PROFİL VE GENEL
-------------------------------------------------------------------------- */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

require __DIR__.'/auth.php';