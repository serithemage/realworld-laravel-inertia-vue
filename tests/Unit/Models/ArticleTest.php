<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_belongs_to_user()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $article->user);
        $this->assertEquals($user->id, $article->user->id);
    }

    public function test_article_belongs_to_many_tags()
    {
        $article = Article::factory()->create();
        $tags = Tag::factory()->count(3)->create();

        $article->tags()->attach($tags->pluck('id'));

        $this->assertCount(3, $article->tags);
        $this->assertInstanceOf(Tag::class, $article->tags->first());
    }

    public function test_article_has_many_comments()
    {
        $article = Article::factory()->create();
        $comments = Comment::factory()->count(3)->create(['article_id' => $article->id]);

        $this->assertCount(3, $article->comments);
        $this->assertInstanceOf(Comment::class, $article->comments->first());
        $this->assertTrue($article->comments->contains($comments->first()));
    }

    public function test_article_belongs_to_many_users_via_favorites()
    {
        $article = Article::factory()->create();
        $users = User::factory()->count(2)->create();

        $article->users()->attach($users->pluck('id'));

        $this->assertCount(2, $article->users);
        $this->assertInstanceOf(User::class, $article->users->first());
    }

    public function test_article_generates_slug_from_title()
    {
        $article = Article::factory()->create(['title' => 'Test Article Title']);

        $this->assertNotNull($article->slug);
        $this->assertEquals('test-article-title', $article->slug);
    }

    public function test_article_slug_is_unique()
    {
        $article1 = Article::factory()->create(['title' => 'Same Title']);
        $article2 = Article::factory()->create(['title' => 'Same Title']);

        $this->assertNotEquals($article1->slug, $article2->slug);
        $this->assertStringStartsWith('same-title', $article1->slug);
        $this->assertStringStartsWith('same-title', $article2->slug);
    }

    public function test_article_created_at_formatted_attribute()
    {
        $date = now()->setDate(2024, 3, 15);
        $article = Article::factory()->create(['created_at' => $date]);

        $this->assertEquals('15 Mar 2024', $article->created_at_formatted);
    }

    public function test_article_appends_created_at_formatted()
    {
        $article = Article::factory()->create();
        $articleArray = $article->toArray();

        $this->assertArrayHasKey('created_at_formatted', $articleArray);
    }

    public function test_article_uses_has_factory_trait()
    {
        $article = Article::factory()->create();

        $this->assertInstanceOf(Article::class, $article);
        $this->assertNotNull($article->id);
    }
}
