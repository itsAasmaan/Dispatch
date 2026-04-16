<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Google',
                'website' => 'https://google.com',
                'industry' => 'Technology',
                'headquarters' => 'Mountain View, CA',
                'size' => '5001+',
                'founded_year' => 1998,
                'tagline' => 'Organize the world\'s information',
                'is_verified' => true,
            ],
            [
                'name' => 'Amazon',
                'website' => 'https://amazon.com',
                'industry' => 'Technology',
                'headquarters' => 'Seattle, WA',
                'size' => '5001+',
                'founded_year' => 1994,
                'tagline' => 'Work hard. Have fun. Make history.',
                'is_verified' => true,
            ],
            [
                'name' => 'Microsoft',
                'website' => 'https://microsoft.com',
                'industry' => 'Technology',
                'headquarters' => 'Redmond, WA',
                'size' => '5001+',
                'founded_year' => 1975,
                'tagline' => 'Empower every person on the planet',
                'is_verified' => true,
            ],
            [
                'name' => 'Meta',
                'website' => 'https://meta.com',
                'industry' => 'Technology',
                'headquarters' => 'Menlo Park, CA',
                'size' => '5001+',
                'founded_year' => 2004,
                'tagline' => 'Give people the power to build community',
                'is_verified' => true,
            ],
            [
                'name' => 'Apple',
                'website' => 'https://apple.com',
                'industry' => 'Technology',
                'headquarters' => 'Cupertino, CA',
                'size' => '5001+',
                'founded_year' => 1976,
                'tagline' => 'Think Different',
                'is_verified' => true,
            ],
        ];

        foreach ($companies as $company) {
            Company::firstOrCreate(['name' => $company['name']], $company);
        }

        $this->command->info('Companies seeded: Google, Amazon, Microsoft, Meta, Apple.');
    }
}
