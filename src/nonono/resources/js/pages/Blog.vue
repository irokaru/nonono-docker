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
     * ページを返す
     * @returns {string}
     */
    getPage() {
      return this.$route.params.page || '';
    },

    /**
     * 数値化する
     * @param {string} val
     * @returns {number}
     */
    toNumber(val) {
      return val.match(/^[0-9]+$/) ? Number(val) : -1;
    },

    /**
     * パスのアクセスが正しいか判断する
     * @returns {boolean}
     */
    validateRoute() {
      const mode = this.getMode();
      const key  = this.getKey();
      const page = this.getPage();

      if (mode === '' && key === '' && page === '') {
        return true;
      }

      // -------------------------------------------------------
      // 記事リスト
      if (mode && key === '' && page === '') {
        const modeNumber = this.toNumber(mode);

        if (Validator.isInteger(modeNumber) && Validator.minNumber(modeNumber, 1)) {
          return true;
        }
      }

      // -------------------------------------------------------
      // 記事詳細＆カテゴリリスト
      if (mode && key && page === '') {
        const keyNumber = this.toNumber(key);

        if (mode === 'post' && Validator.isInteger(keyNumber) && Validator.minNumber(keyNumber, 1)) {
          return true;
        }

        if (mode === 'category' && Validator.maxLength(key, 30)) {
          return true;
        }
      }

      // -------------------------------------------------------
      // カテゴリリスト
      if (mode && key && page) {
        const pageNumber = this.toNumber(page);

        console.log(mode, key, Validator.maxLength(key, 30), pageNumber);
        if (mode === 'category' && Validator.maxLength(key, 30) && Validator.isInteger(pageNumber) && Validator.minNumber(pageNumber, 1)) {
          return true;
        }
      }

      return false;
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
