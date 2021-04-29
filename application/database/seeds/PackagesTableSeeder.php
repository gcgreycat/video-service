<?php

use App\Models\Package;
use App\Models\Video;
use Illuminate\Database\Seeder;

class PackagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Package::class, 5)->create()->each(function (Package $package) {
            $package->videos()->attach($this->getRandomVideoIds(rand(1, 5), true));
        });

        factory(Package::class, 5)->create()->each(function (Package $package) {
            $package->videos()->attach($this->getRandomVideoIds(rand(1, 5), false));
        });

        factory(Package::class, 5)->create()->each(function (Package $package) {
            $package->videos()->attach(array_merge(
                $this->getRandomVideoIds(rand(1, 3), true),
                $this->getRandomVideoIds(rand(1, 3), false)
            ));
        });
    }

    private function getRandomVideoIds(int $limit, bool $free): array
    {
        return Video::query()
            ->where('is_free', $free)
            ->inRandomOrder()
            ->limit($limit)
            ->pluck('id')
            ->toArray();
    }
}
