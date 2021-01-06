<template>
<div id="products">
  <h2>ぷろだくと</h2>

  <div class="content">

    <h3><v-fa :icon="['fas', 'gamepad']"/> げえむ</h3>

    <div class="game-list">
      <GameBox :title="game.title" :release_date="game.release_date"
               :thumbnail_path="game.thumbnail_path" :category="game.category"
               :infomation="game.infomation" :url="game.url"
               v-for="(game, index) of games" :key="`games-${index}`"/>
    </div>

  </div>

</div>
</template>

<script>
import GameApi from '../api/GameApi';
import GameBox from '../components/GameBox';

import Dummy from '../lib/Dummy';

export default {
  data() {
    return {
      games:     Dummy.game(3),
      isLoading: true,
    };
  },
  methods: {
  },
  async mounted() {
    const api = [
      GameApi.get(),
    ];

    await axios.all(api).then(([games]) => {
      this.games = games.data;
      Vue.$setStore('$games', games.data);
    }).catch(e => {
      console.log(e);
    });
  },
  components: {
    GameBox,
  },
}
</script>
