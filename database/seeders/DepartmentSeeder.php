<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        DB::table('departments')->insert([
            ['name' => 'IT Department'],
            ['name' => 'Finance Department'],
            ['name' => 'HR Department'],
            ['name' => 'Operations Department'],
        ]);
    }
}
