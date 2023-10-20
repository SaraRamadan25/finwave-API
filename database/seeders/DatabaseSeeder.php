<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Charity;
use App\Models\Community;
use App\Models\Goal;
use App\Models\Income;
use App\Models\Investment;
use App\Models\Purchase;
use App\Models\Report;
use App\Models\Statistic;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(2)->create();
        Investment::factory(2)->create();
        Income::factory(2)->create();
        Category::factory(2)->create();
       Purchase::factory(2)->create();
       Community::factory(2)->create();
       Goal::factory(2)->create();
       Report::factory(2)->create();
       Statistic::factory(2)->create();
       Transaction::factory(2)->create();

        Charity::factory()->create([
            'name' => 'Heart Foundation of Magdy Yacoub',
            'phones' => ['+20227365166', '+20227365168'],
            'address' => '7 Aziz Abaza St. off 26 July St., Zamalek, Cairo, Cairo Governorate',
            'website' => 'https://www.myf-egypt.org/donation/?utm_source=google&utm_medium=cpc&utm_campaign=Ebranded&gad=1&gclid=Cj0KCQjwusunBhCYARIsAFBsUP9H0F3ifCUizxxFCNyUlab84H3wB9LDofLG-s8QVG4bjSvVyOUR0UsaAv8rEALw_wcB',
        ]);

        Charity::factory()->create([
            'name' => 'Organizing for Orman’s Charity',
            'phones' => ['+20233831185', '+20233831189', '+2033847205', '+203387885'],
            'address' => 'Al-Haram - 7 Haj Musa Street, a branch of Yahya Shahin Street from the main Haram Street - in front of Teka Al-Taawon Restaurant.',
            'website' => 'https://www.dar-alorman.com/',
        ]);


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
