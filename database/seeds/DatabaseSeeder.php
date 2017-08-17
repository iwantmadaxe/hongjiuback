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
        // $this->call(UsersTableSeeder::class);
    }
}

class UserTableSeeder extends Seeder
{
    public function run(){
        App\Models\User::truncate();
        factory(App\Models\User::class,10)->create();
    }
}

class PostTableSeeder extends Seeder
{
    public function run(){
        App\Models\Post::truncate();
        factory(App\Models\Post::class,10)->create();
    }
}

class ProductTableSeeder extends Seeder
{
    public function run(){
        App\Models\Product::truncate();
        factory(App\Models\Product::class,10)->create();
    }
}

class GrapeTableSeeder extends Seeder
{
    public function run(){
        App\Models\Grape::truncate();
        factory(App\Models\Grape::class,10)->create();
    }
}

class CountryTableSeeder extends Seeder
{
    public function run(){
        App\Models\Country::truncate();
        factory(App\Models\Country::class,10)->create();
    }
}

class DistrictTableSeeder extends Seeder
{
    public function run(){
        App\Models\District::truncate();
        factory(App\Models\District::class,10)->create();
    }
}

class CoverTableSeeder extends Seeder
{
    public function run(){
        App\Models\Cover::truncate();
        factory(App\Models\Cover::class,10)->create();
    }
}

class AlbumTableSeeder extends Seeder
{
    public function run(){
        App\Models\Album::truncate();
        factory(App\Models\Album::class,10)->create();
    }
}