<template>
<div class="detail">
  <h3 class="head">{{detail.title}}</h3>

  <div class="info">
    <div class="date"><v-fa :icon="['far', 'clock']"/> {{detail.date}}</div>
    <div class="category"><v-fa :icon="['fas', 'tag']"/>
      <router-link :to="'/blog/category/' + category.category" v-for="(category, index) in detail.categories"
                   :key="'category' + category.post_id + '-' + index">
                   {{category.category}}
      </router-link>
    </div>
  </div>

  <div class="detail" v-html="md2html()"></div>
</div>
</template>

<script>
import BlogUtil from '../lib/BlogUtil';

export default {
  props: {
    posts:  Array,
    detail: Object,
  },
  methods: {
    /**
     * mdをhtmlに変換するやつ
     * @returns {string}
     */
    md2html() {
      return BlogUtil.mdDetail2html(this.detail.detail);
    },
  }
}
</script>
