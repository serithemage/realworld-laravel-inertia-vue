<?php

namespace Tests\Unit\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Policies\CommentPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected CommentPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new CommentPolicy();
    }

    public function test_user_can_update_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->update($user, $comment));
    }

    public function test_user_cannot_update_other_users_comment()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->update($user, $comment));
    }

    public function test_user_can_delete_own_comment()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($this->policy->delete($user, $comment));
    }

    public function test_user_cannot_delete_other_users_comment()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $otherUser->id]);

        $this->assertFalse($this->policy->delete($user, $comment));
    }
}
