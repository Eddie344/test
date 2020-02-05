<?php

use Illuminate\Database\Seeder;

class DepartmentEmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('department_employee')->insert([
            [
                'department_id' => '1',
                'employee_id' => '1',
            ],
            [
                'department_id' => '2',
                'employee_id' => '2',
            ],
            [
                'department_id' => '2',
                'employee_id' => '3',
            ],
            [
                'department_id' => '3',
                'employee_id' => '3',
            ],
            [
                'department_id' => '1',
                'employee_id' => '4',
            ],
            [
                'department_id' => '3',
                'employee_id' => '4',
            ],
        ]);
    }
}
