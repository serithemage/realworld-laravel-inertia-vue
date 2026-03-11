<?php

namespace Tests\Unit\Policies;

use App\Models\Article;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticlePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected ArticlePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ArticlePolicy();
    }

    public function test_user_can_update_own_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $article));
    }

    public function test_user_cannot_update_other_users_article()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $article));
    }

    public function test_user_can_delete_own_article()
    {
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $article));
    }

    public function test_user_cannot_delete_other_users_article()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $article));
    }
}
