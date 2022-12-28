<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $start = 1980;
        $end = 2010;
        for ($i = 0; $i < 1000; $i++) {
            DB::table('users')->insert([
                'name' => $faker->firstName("male"),
                'email' => $faker->unique()->email,
                'password' => $faker->sha1, 
                'created_at' => date('Y-m-d H:m:s'),
                'birth_date' => Carbon::createFromDate($start + rand(0, $end - $start), rand(1, 12), rand(1, 28)),
            ]);
        }
    }
}
