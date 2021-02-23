<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(ServicesSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ServicesSeeder::class);
        $this->call(SecurityQuestionSeeder::class);
        $this->call(CountryListSeeder::class);


    }
}
