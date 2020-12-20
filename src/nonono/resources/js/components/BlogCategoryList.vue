<template>
<div class="post-list">
  <h3 class="head"><i class="fas fa-book"></i> {{getCategory()}}のにっき一覧 ({{getPageNumber()}}ページめ)</h3>

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
     * 現在のページを返す
     * @returns {string}
     */
    getPageNumber() {
      return this.$route.params.page || '1';
    },

    /**
     * カテゴリ名を返す
     * @returns {string}
     */
    getCategory() {
      return this.$route.params.key || 'ーーー';
    },
  },
  components: {
    BlogPostBox,
  }
}
</script>
