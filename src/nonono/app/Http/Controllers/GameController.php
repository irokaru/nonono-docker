<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Http\Resources\GameResource;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

use App\Lib\CommonUtil;

class GameController extends Controller
{
    protected static $_validate = [
        'title'          => 'required|string|min:1|max:65',
        'release_date'   => 'required|date',
        'release_flag'   => 'required|boolean',
        'thumbnail'      => 'required|image|mimes:jpeg,png,gif|max:2048',
        'thumbnail_name' => 'required|string|min:1|max:65',
        'category'       => 'required|string|min:1|max:65',
        'infomation'     => 'nullable|string|min:1|max:257',
        'url'            => 'required|string|min:1|max:257',
    ];

    protected static $_cache_expire_min = 60;

    public function __construct()
    {
        $this->middleware('auth:api')->only(['indexAll', 'store', 'update']);
    }

    public function index()
    {
        if (!CommonUtil::isTesting() && Cache::has(static::getCacheKey())) {
            return Cache::get(static::getCacheKey(), );
        }

        $games = GameResource::collection(Game::getReleasedGames());

        if (!CommonUtil::isTesting()) {
            Cache::put(static::getCacheKey(), $games, static::$_cache_expire_min);
        }

        return $games;
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

        $thumbnail     = $request->file('thumbnail');
        $thumbnail_ext = $thumbnail->extension();

        $thumbnail_save_dir = static::getThumbnailSaveDir();
        $thumbnail_path     = static::getThumbnailPath($request->thumbnail_name, $thumbnail_ext);
        if (getenv('APP_ENV') !== 'testing') {  // テストの時は出力しない
            $thumbnail->move($thumbnail_save_dir, $request->thumbnail_name.'.'.$thumbnail_ext);
        }

        Game::create([
            'title'          => $request->title,
            'release_date'   => $request->release_date,
            'release_flag'   => $request->release_flag,
            'thumbnail_path' => $thumbnail_path,
            'category'       => $request->category,
            'infomation'     => $request->infomation,
            'url'            => $request->url,
        ]);

        if (Cache::has(static::getCacheKey())) {
            Cache::forget(static::getCacheKey());
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), static::$_validate);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $game = Game::find($request->id);
        if (!$game) {
            return response()->json(['error' => 'not found.', 400]);
        }

        if (!CommonUtil::isTesting()) {  // テストのときは削除処理はしない
            unlink(public_path() . '/' . $game->thumbnail_path);
        }

        $thumbnail     = $request->file('thumbnail');
        $thumbnail_ext = $thumbnail->extension();

        $thumbnail_save_dir = static::getThumbnailSaveDir();
        $thumbnail_path     = static::getThumbnailPath($request->thumbnail_name, $thumbnail_ext);
        if (!CommonUtil::isTesting()) {  // テストの時は出力しない
            $thumbnail->move($thumbnail_save_dir, $request->thumbnail_name.'.'.$thumbnail_ext);
        }

        $game->title          = $request->title;
        $game->release_date   = $request->release_date;
        $game->release_flag   = $request->release_flag;
        $game->thumbnail_path = $thumbnail_path;
        $game->category       = $request->category;
        $game->infomation     = $request->infomation;
        $game->url            = $request->url;
        $game->save();

        if (Cache::has(static::getCacheKey())) {
            Cache::forget(static::getCacheKey());
        }

        return response()->json(['status' => 'success'], 200);
    }

    // ---------------------------------------------------------------------------

    protected static function getCacheKey(): string
    {
        return \md5('gamelist');
    }

    /**
     * ゲームのサムネイルを保存するパスを返す
     * @return string
     */
    protected static function getThumbnailSaveDir(): string
    {
        $path = public_path() . '/img/game';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * 実際に利用されるときのサムネイルのパスを返す
     * @param string $thumbnail_name
     * @param string $thumbnail_ext
     * @return string
     */
    protected static function getThumbnailPath($thumbnail_name, $thumbnail_ext): string
    {
        return "/img/game/$thumbnail_name.$thumbnail_ext";
    }
}
