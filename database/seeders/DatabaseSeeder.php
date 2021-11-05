<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws FileCannotBeAdded
     */
    public function run()
    {
        $faker = Faker::create();
        User::factory(10)->create()->each(function ($user) use ($faker) {
            $user->posts()->saveMany(Post::factory(3)->make()->each(function ($post) use ($faker) {
                $imageUrl = $faker->imageUrl(640,480, '', false);
                /** @var Post $post */
                $post->addMediaFromUrl($imageUrl)
                    ->toMediaCollection('posts', 'media');
            }));
        });
    }
}
