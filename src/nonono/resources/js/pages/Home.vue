<template>
<div id="home" class="content">

  <div class="title"></div>

  <h3>ごあいさつ</h3>

  <p>
    ののの茶屋 は１人の一般人が趣味でフリーゲームや素材、その他もろもろを垂れ流す場所でございます。夏場は冷えた麦茶、冬場は温かい緑茶を片手にのんびりと御覧ください。
  </p>
  <p>
    当サイトの閲覧ブラウザは Google Chrome / FireFox を推奨しています。特に Internet Explorer ではページが正常に表示されない場合がございます。
  </p>
  <p>
    当サイトのコンテンツはののの茶屋の権利物を含んでおりますが、無断での転載を禁じているわけではありません。
  </p>

  <h3><i class="far fa-file-alt"></i>こうしんりれき</h3>

  <dl class="history">
    <template v-for="(hist, index) of history">
      <dt :key="`history-date-${index}`">{{hist.date}}</dt>
      <dd :key="`history-discription-${index}`">{{hist.discription}}</dd>
    </template>
  </dl>

  <h3><i class="fas fa-user-alt"></i>なかのひと</h3>

  <div class="bio">

    <div class="image"><img src="/img/bio.png" alt="カル太"></div>

    <div class="info">

      <div class="strong">カル太（かるた）</div>
      <div class="small"><i class="fas fa-briefcase"></i> Web Developer / Composer</div>
      <div class="small"><i class="fas fa-code"></i> JavaScript / PHP / Perl / C++ / C# / Java / Processing</div>

      <div class="comment">
        同人サークル”ののの茶屋”主宰。 1996 年生まれ。山に登るのが好きなアウトドア派のインドアにんげん。<a href="//teamark.starfree.jp/" target="_blank">チーム・あーく</a>と<a href="//www.hinezumi.net" target="_blank">ウディフェス実行委員</a>に所属。
      </div>

      <div class="social">
        <a href="https://twitter.com/IroKaru" target="_blank"><i class="fab fa-twitter"></i> Twitter</a>
        <a href="https://github.com/irokaru" target="_blank"><i class="fab fa-github"></i> GitHub</a>
        <a href="http://amzn.asia/aqypTqg" target="_blank"><i class="fab fa-amazon"></i> Amazon</a>
      </div>

    </div>

  </div>

</div>
</template>

<script>
import Dummy from '../lib/Dummy';

import HistoryApi from '../api/HistoryApi';

export default {
  data() {
    return {
      history: Dummy.history(5),
    };
  },
  async mounted() {
    const api = [
      HistoryApi.get(),
    ];

    await axios.all(api).then(([history]) => {
      this.history = history.data;
      Vue.$setStore('$history', history.data);
    }).catch(e => {
      console.log(e);
    });
  },
}
</script>
