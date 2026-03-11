<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_articles_index(): void
    {
        Article::factory()->count(3)->create();

        $response = $this->get('/articles');

        $response->assertStatus(200);
    }

    public function test_anyone_can_view_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->get("/articles/{$article->slug}");

        $response->assertStatus(200);
    }

    public function test_guest_cannot_create_article(): void
    {
        $response = $this->get('/articles/create');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_article(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/articles', [
            'title' => 'Test Article',
            'content' => 'This is test content.',
            'excerpt' => 'Test excerpt',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'content' => 'This is test content.',
            'excerpt' => 'Test excerpt',
            'user_id' => $user->id,
        ]);
    }

    public function test_article_creation_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/articles', [
            'title' => '',
            'content' => 'This is test content.',
            'excerpt' => 'Test excerpt',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_article_creation_requires_content(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/articles', [
            'title' => 'Test Article',
            'content' => '',
            'excerpt' => 'Test excerpt',
        ]);

        $response->assertSessionHasErrors('content');
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_author_can_edit_own_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("/articles/{$article->id}/edit");

        $response->assertStatus(200);
    }

    public function test_user_cannot_edit_others_article(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($otherUser)->get("/articles/{$article->id}/edit");

        $response->assertStatus(403);
    }

    public function test_author_can_update_own_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/articles/{$article->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'excerpt' => 'Updated excerpt',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
            'content' => 'Updated content.',
            'excerpt' => 'Updated excerpt',
        ]);
    }

    public function test_author_can_delete_own_article(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete("/articles/{$article->id}");

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

    public function test_user_cannot_delete_others_article(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($otherUser)->delete("/articles/{$article->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
        ]);
    }
}
