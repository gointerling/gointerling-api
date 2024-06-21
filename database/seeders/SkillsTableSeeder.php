<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $skills = [
            // Translator - Main Skills
            ['name' => 'Closed Captioning', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Copywriting', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Desktop Publishing', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Editor Penerjemah', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Lokalistor', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Proofreading', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Research', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Subtitling', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Technical Review', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Terminology Research', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Transcription', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Translation', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Typesetting', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],
            ['name' => 'Penerjemah Tersumpah', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'main'],

            // Translator - Additional Skills
            ['name' => 'Penerjemah Umum', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Teknis', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Sastra Fiksi', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Non Sastra Fiksi', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Sains dan Teknologi', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Hukum dan UU', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Ekonomi dan Keuangan', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Pemasaran dan Periklanan', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Media dan Hiburan', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Terminology Research', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Pendidikan dan Pelatihan', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Medis dan Farmasi', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],
            ['name' => 'Alur Website', 'description' => null, 'merchant_type' => 'translator', 'skill_type' => 'additional'],

            // Interpreter - Main Skills
            ['name' => 'Interpreting', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Interpreter Konsekutif', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Interpreter Simultan', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Interpreter Bahasa Isyarat', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Interpreter Telepon (VRI)', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Interpreter Multimedia', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Interpreter Lokalisator', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Voice-over', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Dubbing', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],
            ['name' => 'Layanan Darurat', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'main'],

            // Interpreter - Additional Skills
            ['name' => 'Pemahaman Maritim', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Medis', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Hukum', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Bisnis', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Penerbangan', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Militer', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Keamanan', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Lingkungan', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Budaya', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Olahraga', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Terapi', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Edukasi', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Peradilan Pidana', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Riset', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
            ['name' => 'Pemahaman Penerbitan', 'description' => null, 'merchant_type' => 'interpreter', 'skill_type' => 'additional'],
        ];

        foreach ($skills as $skill) {
            DB::table('skills')->insert([
                'id' => Str::uuid(),
                'name' => $skill['name'],
                'description' => $skill['description'],
                'merchant_type' => $skill['merchant_type'],
                'skill_type' => $skill['skill_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
