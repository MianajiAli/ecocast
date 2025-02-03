<?php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_post_as_author()
    {
        $user = User::factory()->author()->create(); // Author user
        $category = Category::factory()->create();

        $postData = [
            'title' => 'Test Post Title',
            'body' => 'Test post body content.',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ];

        // Make a POST request to create a post
        $response = $this->actingAs($user)->postJson('/api/posts', $postData);

        $response->assertStatus(201); // Assert that the post was created
        $response->assertJson(['title' => 'Test Post Title']); // Assert that the response contains the title
        $this->assertDatabaseHas('posts', $postData); // Assert that the post is stored in the database
    }

    /** @test */
    public function it_can_create_a_post_as_admin()
    {
        $admin = User::factory()->admin()->create(); // Admin user
        $category = Category::factory()->create();

        $postData = [
            'title' => 'Test Post Title',
            'body' => 'Test post body content.',
            'user_id' => $admin->id,
            'category_id' => $category->id,
        ];

        // Make a POST request to create a post
        $response = $this->actingAs($admin)->postJson('/api/posts', $postData);

        $response->assertStatus(201); // Assert that the post was created
        $response->assertJson(['title' => 'Test Post Title']); // Assert that the response contains the title
        $this->assertDatabaseHas('posts', $postData); // Assert that the post is stored in the database
    }

    /** @test */
    public function it_cannot_create_a_post_as_regular_user()
    {
        $user = User::factory()->user()->create(); // Regular user
        $category = Category::factory()->create();

        $postData = [
            'title' => 'Test Post Title',
            'body' => 'Test post body content.',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ];

        // Make a POST request to create a post
        $response = $this->actingAs($user)->postJson('/api/posts', $postData);

        $response->assertStatus(403); // Assert forbidden, as regular users shouldn't create posts
    }

    /** @test */
    public function it_can_update_a_post_as_author()
    {
        $user = User::factory()->author()->create(); // Author user
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $newData = [
            'title' => 'Updated Post Title',
            'body' => 'Updated post body content.',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ];

        // Make a PUT request to update a post
        $response = $this->actingAs($user)->putJson("/api/posts/{$post->id}", $newData);

        $response->assertStatus(200); // Assert that the post is updated
        $response->assertJson(['title' => 'Updated Post Title']); // Assert that the updated title is returned
        $this->assertDatabaseHas('posts', $newData); // Assert that the post is updated in the database
    }

    /** @test */
    public function it_can_update_a_post_as_admin()
    {
        $admin = User::factory()->admin()->create(); // Admin user
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
        ]);

        $newData = [
            'title' => 'Updated Post Title',
            'body' => 'Updated post body content.',
            'user_id' => $admin->id,
            'category_id' => $category->id,
        ];

        // Make a PUT request to update a post
        $response = $this->actingAs($admin)->putJson("/api/posts/{$post->id}", $newData);

        $response->assertStatus(200); // Assert that the post is updated
        $response->assertJson(['title' => 'Updated Post Title']); // Assert that the updated title is returned
        $this->assertDatabaseHas('posts', $newData); // Assert that the post is updated in the database
    }

    /** @test */
    public function it_cannot_update_a_post_as_regular_user()
    {
        $user = User::factory()->user()->create(); // Regular user
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $newData = [
            'title' => 'Updated Post Title',
            'body' => 'Updated post body content.',
        ];

        // Make a PUT request to update a post
        $response = $this->actingAs($user)->putJson("/api/posts/{$post->id}", $newData);

        $response->assertStatus(403); // Assert forbidden, as regular users shouldn't update others' posts
    }

    /** @test */
    public function it_can_delete_a_post_as_author()
    {
        $user = User::factory()->author()->create(); // Author user
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        // Make a DELETE request to delete a post
        $response = $this->actingAs($user)->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200); // Assert that the post is deleted
        $this->assertDatabaseMissing('posts', ['id' => $post->id]); // Assert that the post is no longer in the database
    }

    /** @test */
    public function it_can_delete_a_post_as_admin()
    {
        $admin = User::factory()->admin()->create(); // Admin user
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
        ]);

        // Make a DELETE request to delete a post
        $response = $this->actingAs($admin)->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200); // Assert that the post is deleted
        $this->assertDatabaseMissing('posts', ['id' => $post->id]); // Assert that the post is no longer in the database
    }

    /** @test */
    public function it_cannot_delete_a_post_as_regular_user()
    {
        $user = User::factory()->user()->create(); // Regular user
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        // Make a DELETE request to delete a post
        $response = $this->actingAs($user)->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(403); // Assert forbidden, as regular users shouldn't delete posts
    }

    /** @test */
    public function a_post_belongs_to_a_user_and_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        // Test user relationship
        $this->assertEquals($user->id, $post->user->id);

        // Test category relationship
        $this->assertEquals($category->id, $post->category->id);
    }

    /** @test */
    public function it_can_add_and_remove_likes()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        // User likes the post
        $response = $this->actingAs($user)->postJson("/api/posts/{$post->id}/like");

        $response->assertStatus(200); // Assert that the post is liked
        $this->assertTrue($post->likes->contains($user)); // Assert that the post is liked by the user

        // User removes the like
        $response = $this->actingAs($user)->deleteJson("/api/posts/{$post->id}/like");

        $response->assertStatus(200); // Assert that the like is removed
        $this->assertFalse($post->likes->contains($user)); // Assert that the post is no longer liked by the user
    }
}
