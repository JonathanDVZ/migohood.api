<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UserTableSeeder');

        DB::table('type_categories')->insert([
            'name' => 'Recreational',
            'languaje' => 'EN',
            'code' => '1',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Family',
            'languaje' => 'EN',
            'code' => '2',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Sporty',
            'languaje' => 'EN',
            'code' => '3',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Category',
            'languaje' => 'EN',
            'code' => '4',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Category',
            'languaje' => 'EN',
            'code' => '5',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Recreacional',
            'languaje' => 'ES',
            'code' => '1',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Familiar',
            'languaje' => 'ES',
            'code' => '2',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Deportiva',
            'languaje' => 'ES',
            'code' => '3',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Categoria',
            'languaje' => 'ES',
            'code' => '4',
        ]);

        DB::table('type_categories')->insert([
            'name' => 'Categoria',
            'languaje' => 'ES',
            'code' => '5',
        ]);

    }
}
