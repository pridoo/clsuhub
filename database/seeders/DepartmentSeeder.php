<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Bachelor of Science in Agriculture'],
            ['name' => 'Bachelor of Science in Agribusiness'],
            ['name' => 'Bachelor of Science in Agricultural and Biosystems Engineering'],
            ['name' => 'Bachelor of Science in Fisheries'],
            ['name' => 'Bachelor of Science in Food Technology'],
            ['name' => 'Bachelor of Science in Accountancy'],
            ['name' => 'Bachelor of Science in Biology'],
            ['name' => 'Bachelor of Science in Chemistry'],
            ['name' => 'Bachelor of Science in Environmental Science'],
            ['name' => 'Bachelor of Science in Mathematics'],
            ['name' => 'Bachelor of Science in Hospitality Management'],
            ['name' => 'Bachelor of Science in Development Communication'],
            ['name' => 'Bachelor of Science in Statistics'],
            ['name' => 'Bachelor of Science in Civil Engineering'],
            ['name' => 'Bachelor of Science in Information Technology'],
            ['name' => 'Bachelor of Science in Psychology'],
            ['name' => 'Bachelor of Science in Textile and Fashion Technology'],
            ['name' => 'Bachelor of Science in Tourism Management'],
            ['name' => 'Bachelor of Science in Business Administration (Majors: Business Economics, Human Resource Development Management, Marketing Management)'],
            ['name' => 'Bachelor of Science in Entrepreneurship'],
            ['name' => 'Bachelor of Science in Management Accounting'],
            ['name' => 'Bachelor of Arts in Filipino'],
            ['name' => 'Bachelor of Arts in International Studies'],
            ['name' => 'Bachelor of Arts in Literature'],
            ['name' => 'Bachelor of Arts in Social Sciences'],
            ['name' => 'Bachelor of Secondary Education (Majors: English, Filipino, Mathematics, Science, Social Studies, Values Education)'],
            ['name' => 'Bachelor of Technology and Livelihood Education'],
            ['name' => 'Bachelor of Early Childhood Education'],
            ['name' => 'Bachelor of Physical Education'],
            ['name' => 'Bachelor of Elementary Education'],
            ['name' => 'Bachelor of Culture and Arts Education'],
            ['name' => 'Bachelor of Science in Meteorology'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate($dept);
        }
    }
}
