<template>
<div class="game-box">
  <router-link :to="url" :target="url ? '_blank':''">

    <div class="release">{{release_date}} 公開</div>

    <div class="title">
      <div class="dummy" v-if="title==''"></div>
      {{title}}
    </div>

    <img :src="thumbnail_path" :alt="title">

    <div class="detail">

      <div class="category">
        <span>{{category}}</span>
      </div>

      <div class="info" v-html="infomation"></div>

    </div>
  </router-link>
</div>
</template>

<script>
export default {
  props: {
    title:          String,
    release_date:   String,
    thumbnail_path: String,
    category:       String,
    infomation:     String,
    url:            String,
  }
}
</script>

<style lang="scss" scoped>
@import '../../sass/variables';
.game-box {
  background: rgba(255, 255, 255, .6);
  transition: .3s box-shadow;

  >a {
    padding: .75em;
    display: block;

    .release {
      color: #777;
      font-size: .9em;
    }

    .title {
      color: #ffb366;
      font-size: 1.4em;
      font-weight: bold;

      .dummy {
        display: block;
        background: #ffb366;
        border-radius: 8px;
        width: 8em;
        margin: .1em;
        height: 1.2em;
      }
    }

    .detail {
      .category {
        margin: .4em 0;

        span {
          padding: .2em;
          color: #fff;
          background: #ffb366;
          border-radius: 4px;
        }
      }

      .info {
        color: #777;
        min-height: 1.5em;
      }
    }
  }

  &:hover {
    box-shadow:3px 3px 6px 0px rgba(0, 0, 0, .5);
  }
}

@media (max-width: 980px) {
}

@media (max-width: $mobile-break) {
}
</style>
