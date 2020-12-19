<template>
<div id="blog">
  <h2>にっき</h2>

  <div class="content">

    <div class="blog-main">
      <!-- <component :is="currentView"></component> -->
    </div>

    <BlogSideBar :latests="latests" :categories="categories"/>
  </div>
</div>
</template>

<script>
import BlogSideBar from '../components/BlogSideBar';

import BlogUtil from '../lib/BlogUtil';
import Post     from '../model/Post';

export default {
  data () {
    return {
      currentView: 'BlogPostList',
      latests:     Array(4).fill(Post.dummy(-1)),
      categories:  Array(4).fill(Post.category),
    }
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
     * キーを返す
     * @returns {string}
     */
    getKey() {
      return this.$route.params.key || '';
    },

    /**
     * ページを返す
     * @returns {string}
     */
    getPage() {
      return this.$route.params.page || '';
    },
  },
  mounted () {
    if (!BlogUtil.validateRoute(this.getMode(), this.getKey(), this.getPage())) {
      this.$router.push({path: '/blog'});
    }
    this.currentView = BlogUtil.mainComponentName(this.getMode());
  },
  components: {
    BlogSideBar,
  },
}
</script>

<style lang="scss" scoped>

</style>
