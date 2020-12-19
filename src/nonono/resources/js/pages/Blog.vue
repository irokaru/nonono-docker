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
import Post        from '../model/Post';

export default {
  data () {
    return {
      latests:    Array(4).fill(Post.dummy(-1)),
      categories: Array(4).fill(Post.category),
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
     * パスのアクセスが正しいか判断する
     * @returns {boolean}
     */
    validateRoute() {
      const mode = this.getMode();
      const key  = this.getKey();

      if (mode === '' && key === '') {
        return true;
      }

      if (!Validator.inArray(mode, ['post', 'category'])) {
        return false;
      }

      const keyNumber = Number(key);

      if (mode === 'post' && (!Validator.isInteger(keyNumber) || !Validator.minNumber(keyNumber, 1))) {
        return false;
      }

      return true;
    },
  },
  mounted () {
    if (!this.validateRoute()) {
      this.$router.push({path: '/blog'});
    }
  },
  components: {
    BlogSideBar,
  },
}
</script>

<style lang="scss" scoped>

</style>
