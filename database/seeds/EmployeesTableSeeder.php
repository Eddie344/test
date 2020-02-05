<?php

use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            [
                'name' => 'Иван',
                'surname' => 'Петров',
                'patronymic' => 'Васильевич',
                'gender_id' => '1',
                'salary' => '10000',
            ],
            [
                'name' => 'Анастасия',
                'surname' => 'Иванова',
                'patronymic' => 'Петровна',
                'gender_id' => '2',
                'salary' => '12000',
            ],
            [
                'name' => 'Дмитрий',
                'surname' => 'Данилов',
                'patronymic' => 'Валерьевич',
                'gender_id' => '1',
                'salary' => '15000',
            ],
            [
                'name' => 'Людмила',
                'surname' => 'Рыжова',
                'patronymic' => 'Борисовна',
                'gender_id' => '2',
                'salary' => '13000',
            ],
        ]);
    }
}
