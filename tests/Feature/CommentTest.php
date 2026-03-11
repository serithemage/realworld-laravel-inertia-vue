<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_add_comment(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->post("/articles/{$article->id}/comments", [
            'content' => 'This is a test comment.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'article_id' => $article->id,
            'user_id' => $user->id,
            'content' => 'This is a test comment.',
        ]);
    }

    public function test_guest_cannot_add_comment(): void
    {
        $article = Article::factory()->create();

        $response = $this->post("/articles/{$article->id}/comments", [
            'content' => 'This is a test comment.',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('comments', 0);
    }

    public function test_comment_requires_content(): void
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();

        $response = $this->actingAs($user)->post("/articles/{$article->id}/comments", [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
        $this->assertDatabaseCount('comments', 0);
    }
}
