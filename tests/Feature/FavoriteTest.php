<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_favorite_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->post("/articles/{$article->id}/favorite");

        $response->assertRedirect();
        $this->assertDatabaseHas('article_user', [
            'article_id' => $article->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_unfavorite_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        // First, favorite the article
        $article->users()->attach($user);

        $response = $this->actingAs($user)->delete("/articles/{$article->id}/favorite");

        $response->assertRedirect();
        $this->assertDatabaseMissing('article_user', [
            'article_id' => $article->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_favorite_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->post("/articles/{$article->id}/favorite");

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('article_user', 0);
    }
}
