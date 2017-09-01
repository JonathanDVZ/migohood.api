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

        DB::table('note_emergency')->insert([
            'id' => '21',
            'type' => 'Traer consigo o encargarse el mismo.',
            'code' => '11',
            'languaje' => 'ES',
        ]);
        DB::table('note_emergency')->insert([
            'id' => '22',
            'type' => 'Experiencia incluye alcohol invitados deben tener edad legal',
            'code' => '12',
            'languaje' => 'ES',
        ]);

        DB::table('note_emergency')->insert([
            'id' => '23',
            'type' => 'Necesitan certificacion especial',
            'code' => '23',
            'languaje' => 'ES',
        ]);

        DB::table('note_emergency')->insert([
            'id' => '24',
            'type' => '¿Tienes algun requerimiento para tus inivitados?',
            'code' => '14',
            'languaje' => 'ES',
        ]);
        
        DB::table('note_emergency')->insert([
            'id' => '25',
            'type' => 'Requerimientos adicionales',
            'code' => '15',
            'languaje' => 'ES',
        ]);

        DB::table('note_emergency')->insert([
            'id' => '26',
            'type' => '¿No tienes requerimientos?',
            'code' => '16',
            'languaje' => 'ES',
        ]);
        DB::table('note_emergency')->insert([
            'id' => '27',
            'type' => 'Comida',
            'code' => '17',
            'languaje' => 'ES',
        ]);
        DB::table('note_emergency')->insert([
            'id' => '28',
            'type' => 'Bocadillos',
            'code' => '18',
            'languaje' => 'ES',
        ]);

        DB::table('note_emergency')->insert([
            'id' => '29',
            'type' => 'Bebidas',
            'code' => '19',
            'languaje' => 'ES',
        ]);

        DB::table('note_emergency')->insert([
            'id' => '30',
            'type' => 'Transporte',
            'code' => '20',
            'languaje' => 'ES',
        ]);
        DB::table('note_emergency')->insert([
            'id' => '31',
            'type' => 'Alojamiento',
            'code' => '21',
            'languaje' => 'ES',
        ]);
        DB::table('note_emergency')->insert([
            'id' => '32',
            'type' => 'otro',
            'code' => '22',
            'languaje' => 'ES',
        ]);
        DB::table('note_emergency')->insert([
            'id' => '33',
            'type' => '¿No tienes requerimientos?',
            'code' => '23',
            'languaje' => 'ES',
        ]);

    }
}
