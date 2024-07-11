<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchant;
use Carbon\Carbon;

class MerchantSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packageId = '5d93075a-1c43-40a6-a016-24a13f21adc0';
        $merchants = Merchant::all();

        foreach ($merchants as $merchant) {
            $merchant->subscriptionPackages()->attach($packageId, [
                'subscribe_at' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(1), // Assuming trial is for 1 month
                'is_trial' => true,
                'payment_file_url' => NULL
            ]);
        }
    }
}
