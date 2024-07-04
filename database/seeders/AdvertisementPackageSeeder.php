<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdvertisementPackage;
use Illuminate\Support\Str;

class AdvertisementPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
            [
                'name' => 'Economical Package',
                'duration' => 1,
                'price' => 10000,
                'size_x' => '300',
                'size_y' => '250',
                'priority' => 3,
                'route_json' => json_encode([
                    [
                        'name' => 'Menu Utama',
                        'url' => '/',
                    ],
                    [
                        'name' => 'Halaman Fasilitator',
                        'url' => '/facilitators',
                    ]
                ]),
            ],
            [
                'name' => 'Standard Package',
                'duration' => 2,
                'price' => 20000,
                'size_x' => '300',
                'size_y' => '250',
                'priority' => 2,
                'route_json' => json_encode([
                    [
                        'name' => 'Menu Utama',
                        'url' => '/',
                    ],
                    [
                        'name' => 'Halaman Fasilitator',
                        'url' => '/facilitators',
                    ]
                ]),
            ],
            [
                'name' => 'Premium Package',
                'duration' => 3,
                'price' => 20000,
                'size_x' => '160',
                'size_y' => '600',
                'priority' => 1,
                'route_json' => json_encode([
                    [
                        'name' => 'Menu Utama',
                        'url' => '/',
                    ],
                    [
                        'name' => 'Halaman Fasilitator',
                        'url' => '/facilitators',
                    ]
                ]),
            ]
        ];

        foreach ($packages as $package) {
            AdvertisementPackage::create([
                'id' => (string) Str::uuid(),
                'name' => $package['name'],
                'price' => $package['price'],
                'duration' => $package['duration'],
                'size_x' => $package['size_x'],
                'size_y' => $package['size_y'],
                'priority' => $package['priority'],
                'route_json' => $package['route_json']
            ]);
        }
    }
}
