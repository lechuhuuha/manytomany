<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {  $faker = Faker::create();
        foreach(range(1, 50) as $index){
            DB::table('branches')->insert([
                'name' => $faker->name,
                'slug' => $faker->slug,
                'image'=>$faker->image,
            ]);
        }
    }
}
