<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
       
        DB::table('users')->insert([
            'username' => 'test',
            'email' => 'test' . '@gmail.com',
            'password' => app('hash')->make('test'),
        ]);
        
    }

}
