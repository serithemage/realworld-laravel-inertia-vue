<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_belongs_to_article()
    {
        $article = Article::factory()->create();
        $comment = Comment::factory()->create(['article_id' => $article->id]);

        $this->assertInstanceOf(Article::class, $comment->article);
        $this->assertEquals($article->id, $comment->article->id);
    }

    public function test_comment_belongs_to_user()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'article_id' => $article->id,
        ]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    public function test_comment_created_at_formatted_attribute()
    {
        $date = now()->setDate(2024, 3, 15);
        $comment = Comment::factory()->create(['created_at' => $date]);

        $this->assertEquals('15 Mar 2024', $comment->created_at_formatted);
    }

    public function test_comment_appends_created_at_formatted()
    {
        $comment = Comment::factory()->create();
        $commentArray = $comment->toArray();

        $this->assertArrayHasKey('created_at_formatted', $commentArray);
    }

    public function test_comment_uses_has_factory_trait()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertNotNull($comment->id);
    }
}
