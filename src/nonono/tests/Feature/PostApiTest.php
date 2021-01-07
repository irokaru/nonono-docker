<?php

namespace Tests\Feature;

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

        $content = $this->get(route('post.index'))->assertOk()->json();
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

        $content = $this->get(route('post.index.all'), $auth)->assertOk()->json();
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


        $content = $this->get(route('post.index.category', ['category' => $category_name]))->assertOk()->json();
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

        $content = $this->get(route('post.show', ['post_id' => $released->id]))->assertOk()->json();
        $content['detail'] = 'dummy';

        $this->assertEquals($expect, $content);

        $this->get(route('post.show', ['post_id' => $not_released->id]))->assertStatus(404)->assertSimilarJson(['error' => 'not found']);
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

    public function test_post_count_categories_ok()
    {
        $suites = [
            // category, count
            [Str::random(10), 4],
            [Str::random(8),  6],
            [Str::random(12), 10],
        ];

        $expect = [];
        foreach($suites as $suite) {
            factory(PostCategory::class, $suite[1])->create(['category' => $suite[0]]);

            // count は文字列で返ってくるのでcastしておく
            $expect[] = ['category' => $suite[0], 'count' => "$suite[1]"];
            array_multisort(array_column($expect, 'category'), SORT_ASC, $expect);

            $response = $this->get(route('post.index.categories'));
            $response->assertOk()->assertSimilarJson($expect);
        }
    }

    public function test_post_store_ok()
    {
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $post = factory(Post::class)->make(['release_flag' => true]);
        $categories = factory(PostCategory::class, 2)->make(['post_id' => 1]);
        for ($i = 0; $i < count($categories); $i++) {
            $categories[$i]['id'] = $i + 1;
        }

        $data = static::model2postArray($post, $categories, [], ['post_id']);
        $res_store = $this->json('POST', route('post.store'), $data, $auth);
        $res_store->assertOk()->assertSimilarJson(['status' => 'success', 'id' => 1]);

        $reject_keys = ['created_at', 'updated_at'];
        $expect = static::model2array($post, $categories, $reject_keys);
        $expect['id'] = 1;
        $expect['detail'] = '';

        $content = $this->get(route('post.show', ['post_id' => '1']))->assertOk()->json();
        $content['detail'] = '';

        $this->assertEquals($expect, $content);
    }

    public function test_post_store_ng_validate()
    {
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $post = factory(Post::class)->make(['release_flag' => true]);
        $categories = factory(PostCategory::class, 2)->make(['post_id' => 1]);

        $data = static::model2postArray($post, $categories, [], ['post_id']);

        $suites = [
            ['title',  Str::random(65)],
            ['title',  ''],
            ['date',   'hogehoge'],
            ['detail', ''],
            ['detail', Str::random(12801)],
        ];

        foreach ($suites as $suite) {
            $data_ng = $data;
            $data_ng[$suite[0]] = $suite[1];

            $response = $this->json('POST', route('post.store'), $data_ng, $auth);
            $response->assertStatus(422);
        }
    }

    public function test_post_store_ng_auth()
    {
        $post = factory(Post::class)->make(['release_flag' => true]);
        $categories = factory(PostCategory::class, 2)->make(['post_id' => 1]);

        $data = static::model2postArray($post, $categories, [], ['post_id']);

        $response = $this->json('POST', route('post.store'), $data);
        $response->assertStatus(401);
    }

    public function test_post_update_ok_post()
    {
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $category_count = 3;

        $post_insert = factory(Post::class)->create(['release_flag' => true]);

        $categories          = factory(PostCategory::class, $category_count)->create(['post_id' => $post_insert->id]);
        $post_data           = static::model2array($post_insert, $categories,['created_at', 'updated_at']);
        $post_data['detail'] = 'dummy';

        $suites = [
            ['title', Str::random(24)],
            ['date',  date('Y-m-d')],
        ];

        foreach ($suites as $suite) {
            $update_data = $post_data;
            $update_data[$suite[0]] = $suite[1];
            $update_res = $this->json('PUT', route('post.update'), $update_data, $auth);
            $update_res->assertOk();

            $content = $this->get(route('post.show', ['post_id' => '1']))->assertOk()->json();
            $content['detail'] = 'dummy';

            $this->assertEquals($update_data, $content);
        }
    }

    public function test_post_update_ok_categories()
    {
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $category_count = 3;

        $post_insert = factory(Post::class)->create(['release_flag' => true]);

        $categories          = factory(PostCategory::class, $category_count)->create(['post_id' => $post_insert->id]);
        $post_data           = static::model2array($post_insert, $categories,['created_at', 'updated_at']);
        $post_data['detail'] = 'dummy';

        foreach (range(0, 1) as $index) {
            array_pop($post_data['categories']);
            $update_res = $this->json('PUT', route('post.update'), $post_data, $auth);
            $update_res->assertOk();

            $content = $this->get(route('post.show', ['post_id' => '1']))->assertOk()->json();
            $content['detail'] = 'dummy';

            $this->assertEquals($post_data, $content);
        }

        $insert = [
            'hogehoge', 'fugafuga', 'piyopiyo',
        ];

        foreach ($insert as $value) {
            $post_data['categories'][] = [
                'post_id'  => $post_data['id'],
                'category' => $value,
            ];

            $update_res = $this->json('PUT', route('post.update'), $post_data, $auth);
            $update_res->assertOk();

            $content = $this->get(route('post.show', ['post_id' => '1']))->assertOk()->json();
            $content['detail'] = 'dummy';

            $this->assertEquals($post_data, $content);
        }
    }

    public function test_post_update_ng_validate()
    {
        $admin = factory(Admin::class)->create();
        $auth  = TestTool::getJwtAuthorization($admin);

        $category_count = 3;

        $post_insert = factory(Post::class)->create(['release_flag' => true]);

        $categories          = factory(PostCategory::class, $category_count)->create(['post_id' => $post_insert->id]);
        $post_data           = static::model2array($post_insert, $categories,['created_at', 'updated_at']);
        $post_data['detail'] = 'dummy';

        $suites = [
            ['title',        ''],
            ['title',        Str::random(65)],
            ['date',         'hogehoge'],
            ['release_flag', 'hogehoge'],
            ['detail',       ''],
            ['categories',   ''],
            ['categories',   []],
        ];

        foreach ($suites as $suite) {
            $update_data = $post_data;
            $update_data[$suite[0]] = $suite[1];
            $update_res = $this->json('PUT', route('post.update'), $update_data, $auth);
            $update_res->assertStatus(422);
        }
    }
    public function test_post_update_ng_auth()
    {
        $category_count = 3;

        $post_insert = factory(Post::class)->create(['release_flag' => true]);

        $categories          = factory(PostCategory::class, $category_count)->create(['post_id' => $post_insert->id]);
        $post_data           = static::model2array($post_insert, $categories, ['created_at', 'updated_at']);
        $post_data['detail'] = 'dummy';

        $update_res = $this->json('PUT', route('post.update'), $post_data);
        $update_res->assertStatus(401);

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
                'id'
            ];
        }

        $post_array               = TestTool::model2array($post, $post_reject_keys);
        $categories_array         = TestTool::collection2array($categories, $categories_reject_keys);
        $post_array['categories'] = $categories_array;

        return $post_array;
    }

    /**
     * postとcategoryをポスト用の配列に変換する
     * @param \Illuminate\Database\Eloquent\Model $post
     * @param \Illuminate\Database\Eloquent\Collection $categories
     * @return array
     */
    protected static function model2postArray($post, $categories): array
    {
        $array = static::model2array($post, $categories);

        $copy_key = ['title', 'date', 'release_flag'];

        $store = [];
        foreach ($copy_key as $key) {
            $store[$key] = $post[$key];
        }

        $store['categories'] = [];
        foreach($array['categories'] as $category) {
            $store['categories'][] = $category['category'];
        }
        $store['detail'] = Str::random(100);

        return $store;
    }
}
