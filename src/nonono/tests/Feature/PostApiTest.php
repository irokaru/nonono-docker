<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Models\Post;
use App\Models\PostCategory;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

use Tests\Util\TestTool;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_index_ok()
    {
        $category_count = 3;

        $posts = factory(Post::class, 3)->create(['release_flag' => true]);
        $post_categories = [];
        foreach ($posts as $post) {
            $post_categories[] = factory(PostCategory::class, $category_count)->create(['post_id' => $post->id]);
        }

        $posts_no_release = factory(Post::class, 2)->create(['release_flag' => false]);
        $post_no_release_categories = [];
        foreach ($posts_no_release as $post) {
            $post_no_release_categories[] = factory(PostCategory::class, $category_count)->create(['post_id' => $post->id]);
        }

        $expect_post = [];
        foreach ($posts as $index => $post) {
            $expect_post[] = static::model2array($post, $post_categories[$index]);
        }
        array_multisort(array_column($expect_post, 'date'), SORT_DESC, $expect_post);

        $content = $this->get(route('post.index'))->assertOk()->decodeResponseJson();
        $this->assertEquals($expect_post, $content['data']);
    }

    public function test_post_index_all_ok()
    {
        $category_count = 3;

        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $posts = factory(Post::class, 3)->create(['release_flag' => true]);
        $post_categories = [];
        foreach ($posts as $post) {
            $post_categories[] = factory(PostCategory::class, $category_count)->create(['post_id' => $post->id]);
        }

        $posts_no_release = factory(Post::class, 2)->create(['release_flag' => false]);
        $post_no_release_categories = [];
        foreach ($posts_no_release as $post) {
            $post_no_release_categories[] = factory(PostCategory::class, $category_count)->create(['post_id' => $post->id]);
        }

        $expect_post = [];
        $reject_keys = ['created_at', 'updated_at'];
        foreach ($posts as $index => $post) {
            $ret           = static::model2array($post, $post_categories[$index], $reject_keys);
            $ret['detail'] = '';
            $expect_post[] = $ret;
        }
        foreach ($posts_no_release as $index => $post) {
            $ret           = static::model2array($post, $post_no_release_categories[$index], $reject_keys);
            $ret['detail'] = '';
            $expect_post[] = $ret;
        }

        $content = $this->get(route('post.index.all'), $auth)->assertOk()->decodeResponseJson();
        for ($i = 0; $i < count($content); $i++) {
            $post = $content[$i];
            $post['detail'] = '';
            $content[$i] = $post;
        }
        $this->assertEquals($expect_post, $content);
    }

    public function test_post_index_all_ng_no_auth()
    {
        factory(Post::class, 3)->create(['release_flag' => true]);
        factory(Post::class, 2)->create(['release_flag' => false]);

        $this->get(route('post.index.all'))->assertStatus(401);
    }

    public function test_post_index_as_category_ok()
    {
        $category_name = 'dummytestcategory';

        $posts = factory(Post::class, 5)->create(['release_flag' => true]);
        $post_categories = [];
        foreach ($posts as $post) {
            $post_categories[] = new Collection([factory(PostCategory::class)->create(['post_id' => $post->id, 'category' => $category_name])]);
        }

        $another_posts = factory(Post::class, 5)->create(['release_flag' => true]);
        $another_post_categories = [];
        foreach ($another_posts as $post) {
            $another_post_categories[] = factory(PostCategory::class, 5)->create(['post_id' => $post->id]);
        }

        $expect_post = [];
        foreach ($posts as $index => $post) {
            $expect_post[] = static::model2array($post, $post_categories[$index]);
        }
        array_multisort(array_column($expect_post, 'date'), SORT_DESC, $expect_post);


        $content = $this->get(route('post.index.category', ['category' => $category_name]))->assertOk()->decodeResponseJson();
        $this->assertEquals($expect_post, $content['data']);
    }

    public function test_post_index_as_category_ng_validate()
    {
        $suites = [
            ' ',
            Str::random(33),
        ];

        foreach ($suites as $suite) {
            $response = $this->get(route('post.index.category', ['category' => $suite]));
            $response->assertStatus(422);
        }
    }

    public function test_post_show_ok()
    {
        $category_count = 3;
        $reject_keys = ['created_at', 'updated_at'];

        $released = factory(Post::class)->create(['release_flag' => true]);
        $released_categories = factory(PostCategory::class, $category_count)->create(['post_id' => $released->id]);
        $not_released = factory(Post::class)->create(['release_flag' => false]);
        factory(PostCategory::class, $category_count)->create(['post_id' => $not_released->id]);

        $expect = static::model2array($released, $released_categories, $reject_keys);
        $expect['detail'] = 'dummy';

        $content = $this->get(route('post.show', ['post_id' => $released->id]))->assertOk()->decodeResponseJson();
        $content['detail'] = 'dummy';

        $this->assertEquals($expect, $content);

        $this->get(route('post.show', ['post_id' => $not_released->id]))->assertStatus(404)->assertExactJson(['error' => 'not found']);
    }

    public function test_post_show_ng_validate()
    {
        $suites = [
            'aiueo',
            -1,
            1.01,
            0,
            ' ',
        ];

        foreach ($suites as $suite) {
            $response = $this->get(route('post.show', ['post_id' => $suite]));
            $response->assertStatus(422);
        }
    }

    // ==============================================================

    /**
     * postとcategoryを配列に変換する
     * @param \Illuminate\Database\Eloquent\Model $post
     * @param \Illuminate\Database\Eloquent\Collection $categories
     * @return array
     */
    protected static function model2array($post, $categories, $post_reject_keys=[], $categories_reject_keys=[]): array
    {
        if ($post_reject_keys === []) {
            $post_reject_keys = [
                'release_flag', 'created_at', 'updated_at',
            ];
        }

        if ($categories_reject_keys === []) {
            $categories_reject_keys = [
                'id',
            ];
        }

        $post_array               = TestTool::model2array($post, $post_reject_keys);
        $categories_array         = TestTool::collection2array($categories, $categories_reject_keys);
        $post_array['categories'] = $categories_array;

        return $post_array;
    }
}
