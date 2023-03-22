<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $userAmount = 50;
        $courseAmount = 100;
        $enrollmentAmount = 40;

        User::factory($userAmount)->create();
        Course::factory($courseAmount)->create();
        Enrollment::factory($enrollmentAmount)->create();
    }
}
