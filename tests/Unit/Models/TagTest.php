<?php

namespace Tests\Unit\Models;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_tag_has_many_articles()
    {
        $tag = Tag::factory()->create();
        $articles = Article::factory()->count(3)->create();

        $tag->articles()->attach($articles->pluck('id'));

        $this->assertCount(3, $tag->articles);
        $this->assertInstanceOf(Article::class, $tag->articles->first());
    }

    public function test_tag_generates_slug_from_name()
    {
        $tag = Tag::factory()->create(['name' => 'Test Tag Name']);

        $this->assertNotNull($tag->slug);
        $this->assertEquals('test-tag-name', $tag->slug);
    }

    public function test_tag_slug_is_unique()
    {
        $tag1 = Tag::factory()->create(['name' => 'Same Name']);
        $tag2 = Tag::factory()->create(['name' => 'Same Name']);

        $this->assertNotEquals($tag1->slug, $tag2->slug);
        $this->assertStringStartsWith('same-name', $tag1->slug);
        $this->assertStringStartsWith('same-name', $tag2->slug);
    }

    public function test_tag_does_not_use_timestamps()
    {
        $tag = Tag::factory()->create();

        $this->assertNull($tag->created_at);
        $this->assertNull($tag->updated_at);
    }

    public function test_tag_uses_has_factory_trait()
    {
        $tag = Tag::factory()->create();

        $this->assertInstanceOf(Tag::class, $tag);
        $this->assertNotNull($tag->id);
    }
}
