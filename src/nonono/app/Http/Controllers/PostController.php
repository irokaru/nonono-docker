<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostCategory;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class PostController extends Controller
{
    const PAGINATION = 20;

    public function __construct()
    {
        $this->middleware('auth:api')->only(['indexAll']);
    }

    public function index()
    {
        return PostResource::collection(Post::findAllReleasedPosts(static::PAGINATION));
    }

    public function indexAll()
    {
        return PostResource::collection(Post::findAll(static::PAGINATION));
    }

    public function indexAsCategory(Request $request)
    {
        $category = trim($request->category);

        $validate_category = ['category' => 'required|string|min:1|max:32',];
        $validator = Validator::make(['category' => $category], $validate_category);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        return PostResource::collection(Post::findAllReleasedPostAsCategory($category, static::PAGINATION));
    }

    public function show(Request $request)
    {
        $validate_show = [
            'post_id' => 'required|integer|min:1',
        ];

        $validator = Validator::make(['post_id' => $request->post_id], $validate_show);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $post = Post::findOne($request->post_id);

        if (!$post) {
            return response()->json(['error' => 'not found'], 404);
        }

        return PostResource::make($post);
    }

    public function indexOfCategories()
    {
        return PostCategory::countCategories();
    }

    public function store(Request $request)
    {
        // TODO
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        // TODO
        return response()->json(['status' => 'success'], 200);
    }
}
