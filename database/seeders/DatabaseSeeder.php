<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        function random_0_1() 
        {
            return (float)rand() / (float)getrandmax();
        }
        
        $classificationList = array("Positive", "Negative", "Neutral");
        $drinksList = array(17222, 13501, 17225, 17837, 13938, 14610, 17833, 17839, 15106, 15266, 17835, 11023, 17228, 11046, 17180, 11014, 11020, 11021, 11055, 12560, 12756, 13162, 15182);

        // $this->call(UsersTableSeeder::class);
        $faker = \Faker\Factory::create();
        foreach (range(1,20) as $index) {
	        \DB::table('users')->insert([
                'name' => $faker->firstName,
                'lastname' => $faker->lastName,
                'title' => $faker->title,
                'city' => $faker->city,
                'street_name' => $faker->streetName,
                'street_address' => $faker->streetAddress,
                'country' => $faker->country,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'email' => $faker->email,
                'password' => $faker->password,
                'created_at' => $faker->dateTime,
	        ]);
        }

       
        foreach (range(1,200) as $index) {
	        \DB::table('posts')->insert([
                'user_id' => $faker->numberBetween($min = 1, $max = 20),
                'drink_id' => $drinksList[array_rand($drinksList)],
                'title' => $faker->sentence,
                'desc' => $faker->paragraph($nbSentences = 10, $variableNbSentences = true),
                'classification' => $classificationList[array_rand($classificationList)],
                'confidence' => random_0_1(),
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'created_at' => $faker->dateTime,
	        ]);
        }


        foreach (range(1,1000) as $index) {
	        \DB::table('likes')->insert([
                'post_id' => $faker->numberBetween($min = 1, $max = 200),
                'user_id' => $faker->numberBetween($min = 1, $max = 20),
                'created_at' => $faker->dateTime,
	        ]);
        }




        foreach (range(1,1000) as $index) {
	        \DB::table('comments')->insert([
                'post_id' => $faker->numberBetween($min = 1, $max = 200),
                'user_id' => $faker->numberBetween($min = 1, $max = 20),
                'comment' => $faker->sentence,
                'classification' => $classificationList[array_rand($classificationList)],
                'confidence' => random_0_1(),
                'created_at' => $faker->dateTime,
	        ]);
        }

    }
}
