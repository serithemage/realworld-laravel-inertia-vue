<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_articles()
    {
        $user = User::factory()->create();
        $articles = Article::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->articles);
        $this->assertInstanceOf(Article::class, $user->articles->first());
        $this->assertTrue($user->articles->contains($articles->first()));
    }

    public function test_user_has_many_article_favorites()
    {
        $user = User::factory()->create();
        $articles = Article::factory()->count(2)->create();

        $user->articleFavorites()->attach($articles->pluck('id'));

        $this->assertCount(2, $user->articleFavorites);
        $this->assertInstanceOf(Article::class, $user->articleFavorites->first());
    }

    public function test_user_has_many_followers()
    {
        $user = User::factory()->create();
        $followers = User::factory()->count(2)->create();

        foreach ($followers as $follower) {
            $user->followers()->attach($follower->id);
        }

        $this->assertCount(2, $user->followers);
        $this->assertInstanceOf(User::class, $user->followers->first());
    }

    public function test_user_has_many_users_following()
    {
        $user = User::factory()->create();
        $usersToFollow = User::factory()->count(2)->create();

        foreach ($usersToFollow as $userToFollow) {
            $user->users()->attach($userToFollow->id);
        }

        $this->assertCount(2, $user->users);
        $this->assertInstanceOf(User::class, $user->users->first());
    }

    public function test_user_has_many_comments()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comments = Comment::factory()->count(3)->create([
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);

        $this->assertCount(3, $user->comments);
        $this->assertInstanceOf(Comment::class, $user->comments->first());
        $this->assertTrue($user->comments->contains($comments->first()));
    }

    public function test_user_fillable_attributes()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $user = User::create($userData);

        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertNotNull($user->password);
    }

    public function test_user_hidden_attributes()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret'),
            'remember_token' => 'test_token',
        ]);

        $userArray = $user->toArray();

        $this->assertArrayNotHasKey('password', $userArray);
        $this->assertArrayNotHasKey('remember_token', $userArray);
    }

    public function test_user_email_verified_at_cast_to_datetime()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    public function test_user_uses_has_factory_trait()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotNull($user->id);
    }
}
