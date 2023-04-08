<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DonaturSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker  = Faker::create();
        $gender = $faker->randomElement(['male', 'female']);

        foreach (range(1,200) as $index) {
            DB::table('donatur')->insert([
                'name'            => $faker->name($gender),
                'telp'            => str_replace(' ', '', $faker->phoneNumber),
                'want_to_contact' => $faker->randomElement(['1', '0'])
            ]);
        }
    }
}
