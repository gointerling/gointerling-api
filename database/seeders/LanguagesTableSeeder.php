<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Chinese', 'code' => 'cn'],
            ['name' => 'German', 'code' => 'de'],
            ['name' => 'Arabic', 'code' => 'ar'],
            ['name' => 'Japanese', 'code' => 'ja'],
            ['name' => 'Turkish', 'code' => 'tr'],
            ['name' => 'Korean', 'code' => 'ko'],
            ['name' => 'French', 'code' => 'fr'],
            ['name' => 'Dutch', 'code' => 'nl'],
            ['name' => 'Spanish', 'code' => 'es'],
            ['name' => 'Portuguese', 'code' => 'pt'],
            ['name' => 'Russian', 'code' => 'ru'],
            ['name' => 'Italian', 'code' => 'it'],
            ['name' => 'Hindi', 'code' => 'hi'],
            ['name' => 'Swedish', 'code' => 'sv'],
            ['name' => 'Norwegian', 'code' => 'no'],
            ['name' => 'Finnish', 'code' => 'fi'],
            ['name' => 'Danish', 'code' => 'da'],
            ['name' => 'Greek', 'code' => 'el'],
            ['name' => 'Hebrew', 'code' => 'he'],
            ['name' => 'Thai', 'code' => 'th'],
            ['name' => 'Vietnamese', 'code' => 'vi'],
            ['name' => 'Indonesian', 'code' => 'id'],
            ['name' => 'Malay', 'code' => 'my'],
            ['name' => 'Polish', 'code' => 'pl'],
        ];

        foreach ($languages as $language) {
            DB::table('languages')->insert([
                'id' => Str::uuid(),
            'name' => $language['name'],
            'code' => $language['code'],
            'created_at' => now(),
            'updated_at' => now(),
            ]);
        }
    }
}
