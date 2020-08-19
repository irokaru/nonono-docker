<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\PostCategory;

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
        $category = $request->category;
        if (!$category) {
            return $this->index();
        }

        return PostResource::collection(Post::findAllReleasedPostAsCategory($category, static::PAGINATION));
    }

    public function show(Request $request)
    {
        return PostResource::make(Post::findOne($request->post_id));
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
