<template>
<div class="post-list">
  <h3 class="head"><i class="fas fa-book"></i> にっき一覧 ({{getPageNumber()}}ページめ)</h3>

  <BlogPostBox :id="post.id" :title="post.title" :date="post.date" :categories="post.categories"
               v-for="post of posts" :key="post.id"/>

</div>
</template>

<script>
import BlogPostBox from './BlogPostBox';

export default {
  props: {
    posts: Array,
    detail: Object,
  },
  methods: {
    /**
     * モードを返す
     * @returns {string}
     */
    getMode() {
      return this.$route.params.mode || '';
    },

    /**
     * 現在のページを返す
     */
    getPageNumber() {
      return this.getMode() || '1';
    },
  },
  components: {
    BlogPostBox,
  }
}
</script>
