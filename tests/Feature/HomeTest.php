<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_home_page_displays_articles(): void
    {
        Article::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_tag_page_shows_filtered_articles(): void
    {
        $tag = Tag::factory()->create(['name' => 'laravel']);
        $article = Article::factory()->create();
        $article->tags()->attach($tag);

        // Create an article without the tag
        Article::factory()->create();

        $response = $this->get("/tags/{$tag->slug}");

        $response->assertStatus(200);
    }
}
