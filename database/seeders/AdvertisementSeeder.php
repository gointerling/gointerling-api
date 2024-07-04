<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Advertisement;
use App\Models\AdvertisementPackage;
use Illuminate\Support\Str;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch packages and sort by ID
        $packages = AdvertisementPackage::orderBy('id')->get();

        foreach ($packages as $package) {
            Advertisement::create([
                'name' => 'Ad for Package ' . $package->id,
                'tagline' => 'Tagline for Package ' . $package->id,
                'description' => 'Description for Package ' . $package->id,
                'package_id' => $package->id,
                'image_url' => 'https://example.com/image.jpg',
                'cta_link' => 'https://example.com',
                'valid_until_date' => now()->addMonths($package->duration),
            ]);
        }

        $this->command->info('Advertisements seeded successfully.');
    }
}
