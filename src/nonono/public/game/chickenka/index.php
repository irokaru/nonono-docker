<?php
require("./api/ogImageCreater.php");
$score = isset($_GET['score']) ? htmlspecialchars($_GET['score']) : null;
$og_image_url = ogImageCreater($score);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="shortcut icon" href="./assets/favicon.ico" type="image/vnd.microsoft.icon">
  <link rel="icon" href="./assets/favicon.ico" type="image/vnd.microsoft.icon">
  <link rel="stylesheet" href="./css/default.css?201807">
  <link rel="stylesheet" href="./css/default-mobile.css">
  <script src="https://cdn.jsdelivr.net/npm/vue"></script>
  <title>集めて鶏金貨</title>
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="@IroKaru" />
  <meta property="og:url" content="http://nononotyaya.net/game/chickenka/" />
  <meta property="og:title" content="集めて鶏金貨" />
  <meta property="og:description" content="片手で遊べるゆる～いドライブゲームです" />
  <meta property="og:image" content="<?= $og_image_url ?>" />
</head>
<body>
<script>
  var url = location.href.replace(/\?.*$/,"");
  history.replaceState('','',url);
</script>
<div id="app">
<!-- ここからヘッダー -->
<header>
  <h1>集めて鶏金貨</h1>
</header>
<!-- ここまでヘッダー -->

<!-- ここからメニュー -->
<div class="menu-btn" @click="menuShow=!menuShow"><i class="fa fa-bars" aria-hidden="true"></i></div> <!-- (モバイル用)メニューボタン -->
<nav v-bind:class='{active:menuShow}'>
  <ul>
    <li class="nav-gray" @click="page=0">トップ<span class="sub">TOP PAGE</span></li>
    <li class="nav-gray" @click="page=1">ゲームについて<span class="sub">ABOUT</span></li>
    <li class="nav-gray" @click="page=3">ギャラリー<span class="sub">GALLERY</span></li>
    <li class="nav-gray" @click="page=4">ダウンロード<span class="sub">DOWNLOAD</span></li>
  </ul>
</nav>
<!-- ここまでメニュー -->

<!-- ここから本体 -->
<transition-group name="down-slide" class="main">
<article :key="page">
  <!-- ページ0（トップページ） -->
  <div v-if="page == 0">
    <img src="./assets/logo.png" alt="集めて鶏金貨" class="logo">
    <div class="box">
      <h2>集めて鶏金貨</h2>
      <p>
        片手でゆる～く遊べるドライブゲームです。<br>
        <a href="http://www.silversecond.com/WolfRPGEditor/">WOLF RPGエディター</a>10周年企画、<a href="http://hinezumi.net/wodifes/" target="_blank">ウディフェス外伝</a>のために作りました。
      </p>
      <p>
        作中のフォントの都合上、<strong>鶏金貨</strong>を<strong>鳥金貨</strong>と表記する場合もあります。書き方はどちらでも構いません。
      </p>
      <h3>作品の取り扱いについて</h3>
      <p>
        本作品を実況プレイ動画や配信に利用していただいても構いません。<br>
        報告も任意とさせていただきます。<br>
        しかし、作品の批判などを目的とした利用はご遠慮ください。
      </p>
      <p>
        その他質問等あれば<a href="https://twitter.com/IroKaru" target="_blank">作者</a>までお気軽にお問い合わせください。
      </p>
      <p>
        Twitter で感想を投稿する際は、ハッシュタグに<a href="https://twitter.com/search?f=tweets&q=%23集めて鶏金貨" target="_blank">#集めて鶏金貨</a>を付けてくださると幸いです。
      </p>
      <h3>更新履歴</h3>
      <dl class="list-box">
        <dt>2018/04/02</dt>
        <dd>バージョン1.0.1に更新しました<br>リザルト画面から結果をツイートできるようになりました</dd>
        <dt>2018/02/23</dt>
        <dd>バージョン1.0.0を公開しました</dd>
      </dl>
    </div>
  </div>
  <!-- ページ0ここまで -->

  <!-- ページ1（あらすじ） -->
  <div v-if="page == 1" class="box">

    <h2>ゲームについて<span class="sub">ABOUT</span></h2>
    <h3>あらすじ</h3>
    <p>
      鳥車(トリカート)。それはニワトリたちの車。<br>
      今、鳥車に乗って鳥金貨(チキンカ)を集めるのが流行っている。<br>
      早く、そして多くの鳥金貨を集めたものは、鳥王(チキング)と呼ばれ、たたえられると言う。<br>
      夕一(ゆういち)も鳥王にあこがれを持つニワトリの一羽であったーー
    </p>
    <h3>システム</h3>
    <p>
      画像準備中です。。。
    </p>
  </div>
  <!-- ページ1ここまで -->
  <!-- ページ3（ギャラリー） -->
  <div v-if="page == 3" class="box">
    <h2>ギャラリー<span class="sub">GALLERY</span></h2>
    <img-pv thumb="./img/title.png" @view="showImage('./img/title.png')"></img-pv>
    <img-pv thumb="./img/ss01.png" @view="showImage('./img/ss01.png')"></img-pv>
    <img-pv thumb="./img/ss02.png" @view="showImage('./img/ss02.png')"></img-pv>

    <h3>紹介動画</h3>
    <iframe width="312" height="176" src="https://ext.nicovideo.jp/thumb/sm32971611" scrolling="no" style="border:solid 1px #ccc; width: 100%;" frameborder="0"><a href="http://www.nicovideo.jp/watch/sm32971611">【ウディタ】集めて鶏金貨【CM風紹介動画】</a></iframe>
  </div>
  <!-- ページ3ここまで -->

  <!-- ページ4（ダウンロード） -->
  <div v-if="page == 4" class="box">

    <h2>ダウンロード<span class="sub">DOWNLOAD</span></h2>
    <h3>ゲームデータ</h3>
    <p>
      ダウンロードURLのみの転載はご遠慮ください。
    </p>
    <div class="btn-center">
      <a href="/api/download/game/chickenka" class="square-blue">
        <i class="fa fa-download" aria-hidden="true"></i>　DOWNLOAD
        <span class="sub">(バージョン1.0.1)</span>
      </a>
    </div>

    <h3>アップデートデータ</h3>
    <p>
      旧バージョン(ver1.0.0)のデータをお持ちの方はこちらのアップデート用のデータをダウンロードしてください。
    </p>
    <div class="btn-center">
      <a href="/api/download/game/chickenka-u" class="square-blue">
        <i class="fa fa-download" aria-hidden="true"></i>　DOWNLOAD
        <span class="sub">(1.0.1にアップデート)</span>
      </a>
    </div>

    <h3>オープンソース版</h3>
    <p>
      ver1.0.1 のオープンソース版です。 Editor.exe とか全部付いてきます。このソースコードを利用して何かしらの被害が出たとしても、製作者は責任を負いません。
    </p>
    <div class="btn-center">
      <a href="/api/download/game/chickenka-o" class="square-blue">
        <i class="fa fa-download" aria-hidden="true"></i>　DOWNLOAD
        <span class="sub">(バージョン1.0.1)</span>
      </a>
    </div>


  </div>
  <!-- ページ4ここまで -->

</transition-group>

<!-- ここから画像プレビュー用（分からない人はさわらないでください） -->
<transition name="preview">
<div class="pv-bg" v-if="imagePreview.frag" @click="imagePreview.frag=false">
  <div class="pv-wrapper"><img :src="imagePreview.path"></div>
</div>
</transition>
<!-- ここまで画像プレビュー用 -->

</div>
<!-- ここまで本体 -->

<!-- ここから著作権のやつ -->
<footer>(C) 2018 <a href="https://nononotyaya.net" target="_blank">ののの茶屋</a>.</footer>
<!-- ここまで著作権のやつ -->

<script>
var app = new Vue({
  el: '#app',
  data () {
    return {
      page: 0,         // 最初に表示するページ番号
      menuShow: false, // モバイル向けメニューの表示フラグ
      imagePreview: {  // 画像プレビュー関連
        frag: false,   // プレビューフラグ
        path: ''       // プレビューする画像のパス
      },
    }
  },
  methods: {
    showImage: function (img) {  // 画像プレビュー用処理
      this.imagePreview.frag = true;
      this.imagePreview.path = img;
    }
  },
  components: {
    'img-pv': {  // プレビュー用タグ
      props: ['thumb'],
      template: '<div class="img-cover" @click="view"><img :src="thumb"></div>',
      methods: {
        view: function() { this.$emit('view') }
      }
    }
  },
});

</script>
</body>
</html>
