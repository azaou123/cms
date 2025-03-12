<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $users = [];

        for ($i = 1; $i <= 47; $i++) {
            $users[] = [
                'name' => $faker->name, // Generate random name
                'email' => $faker->unique()->safeEmail, // Generate unique email
                'phone' => $faker->phoneNumber, // Generate random phone number
                'profile_picture' => 'profile-pictures/' . Str::random(40) . '.jpg', // Random file name for profile picture
                'bio' => $faker->sentence, // Generate random bio
                'join_date' => $faker->date, // Random join date
                'status' => 'pending', // Set status to pending by default
                'email_verified_at' => $faker->dateTimeThisYear, // Random email verified date
                'password' => bcrypt('password'), // Default password, hash it
                'remember_token' => Str::random(10), // Random remember token
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert 47 users into the users table
        DB::table('users')->insert($users);
    }
}
