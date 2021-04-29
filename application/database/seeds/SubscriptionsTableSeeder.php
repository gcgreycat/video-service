<?php

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        User::query()->doesntHave('subscriptions')->get()->each(function (User $user) use ($faker) {
            $ids = Package::query()->inRandomOrder()->limit(rand(1, 5))->pluck('id')->toArray();
            foreach ($ids as $id) {
                $user->packages()->attach($id, [
                    'time_start_at' => $faker->dateTimeBetween('-1 week'),
                ]);
            }
        });
    }
}
