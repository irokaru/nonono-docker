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

  <h3><v-fa :icon="['far', 'file-alt']"/>こうしんりれき</h3>

  <dl class="history">
    <template v-for="history of histories">
      <dt>{{history.date}}</dt>
      <dd v-html="history.discription"></dd>
    </template>
  </dl>

  <h3><v-fa :icon="['fas', 'user-alt']"/>なかのひと</h3>

  <div class="bio">

    <div class="image"><img src="/img/bio.png" alt="カル太"></div>

    <div class="info">

      <div class="strong">カル太（かるた）</div>
      <div class="small"><v-fa :icon="['fas', 'briefcase']"/> Web Developer / Composer</div>
      <div class="small"><v-fa :icon="['fas', 'code']"/> JavaScript / PHP / Perl / C++ / C# / Java / Processing</div>

      <div class="comment">
        同人サークル”ののの茶屋”主宰。 1996 年生まれ。山に登るのが好きなアウトドア派のインドアにんげん。<a href="//teamark.starfree.jp/" target="_blank">チーム・あーく</a>と<a href="//www.hinezumi.net" target="_blank">ウディフェス実行委員</a>に所属。
      </div>

      <div class="social">
        <a href="https://twitter.com/IroKaru" target="_blank"><v-fa :icon="['fab', 'twitter']"/> Twitter</a>
        <a href="https://github.com/irokaru" target="_blank"><v-fa :icon="['fab', 'github']"/> GitHub</a>
        <a href="http://amzn.asia/aqypTqg" target="_blank"><v-fa :icon="['fab', 'amazon']"/> Amazon</a>
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
      histories: Dummy.history(5),
    };
  },
  async mounted() {
    const api = [
      HistoryApi.get(),
    ];

    await axios.all(api).then(([histories]) => {
      this.histories = histories.data;
      Vue.$setStore('$history', histories.data);
    }).catch(e => {
      console.log(e);
    });
  },
}
</script>
