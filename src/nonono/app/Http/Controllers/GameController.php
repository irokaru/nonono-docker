<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;

class GameController extends Controller
{
    protected static $_validate = [
        'title'          => 'required|string|min:1|max:65',
        'release_date'   => 'required|date',
        'release_flag'   => 'required|boolean',
        'thumbnail'      => 'required|image|mimes:jpeg,png,gif|max:2048',
        'thumbnail_name' => 'required|string|min:1|max:65',
        'category'       => 'required|string|min:1|max:65',
        'infomation'     => 'required|string|min:1|max:257',
        'url'            => 'required|string',
    ];

    public function __construct()
    {
        $this->middleware('auth:api')->only(['indexAll', 'store']);
    }

    public function index()
    {
        return GameResource::collection(Game::getReleasedGames());
    }

    public function indexAll()
    {
        return GameResource::collection(Game::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), static::$_validate);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $thumbnail_ext      = $request->thumbnail->extension();
        $thumbnail_save_dir = static::getThumbnailDir();
        $thumbnail_path     = "$thumbnail_save_dir/$thumbnail_name.$thumbnail_ext";
        if (getenv('APP_ENV') !== 'testing') {  // テストの時は出力しない
            $request->file('thumbnail')->move($thumbnail_save_dir, $request->thumbnail_name);
        }

        Game::create([
            'title'          => $request->title,
            'release_date'   => $request->release_date,
            'release_flag'   => $request->release_flag,
            'thumbnail_path' => $request->thumbnail_path,
            'category'       => $request->category,
            'infomation'     => $request->infomation,
            'url'            => $request->url,
        ]);

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * ゲームのサムネイルを保存するパスを返す
     * @return string
     */
    protected static function getThumbnailDir()
    {
        $path = public_path() . '/img/game';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }
}
