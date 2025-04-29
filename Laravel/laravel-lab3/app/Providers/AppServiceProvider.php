<?php

namespace App\Providers;
use App\Models\Student;
use App\Policies\StudentPolicy;
use App\Models\Course;
use App\Policies\CoursePolicy;
use App\Models\Enrollment;
use App\Policies\EnrollmentPolicy;

use App\Models\Grade;
use App\Policies\GradePolicy;

use App\Models\Result;
use App\Policies\ResultPolicy;
use App\Models\Lecturer;
use App\Policies\LecturerPolicy;



use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Student::class => StudentPolicy::class,
        Course::class => CoursePolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
        Grade::class => GradePolicy::class,
        Result::class => ResultPolicy::class,
        Lecturer::class => LecturerPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
