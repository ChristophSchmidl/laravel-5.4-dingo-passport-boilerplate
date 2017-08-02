<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $testUser = User::create([
        	'name' => 'John Doe',
        	'email' => 'test@example.com',
        	'password' => bcrypt("test1337")]);
        Model::reguard();
    }
}
