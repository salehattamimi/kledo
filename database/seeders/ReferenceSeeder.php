<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('references')->insert([
            [
            "code"=>"overtime_method",
            "name"=>"Salary / 173",
            "expression"=>"(salary / 173) * overtime_duration_total",
            ],
            [
                "code"=>"overtime_method",
                "name"=>"Fixed",
                "expression"=>"10000 * overtime_duration_total",
            ]
        ]);
    }
}