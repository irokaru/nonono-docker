<template>
<div id="blog">
  <h2>にっき</h2>

  <div class="content">

    <div class="blog-main">
      <component :is="currentView" :posts="posts" :detail="detail"/>

      <div class="paginate margin-tb-1" v-if="flags.showPaginate">
        <router-link class="link prev" :class="{current: paginate.prev === null}"
                     :to="getPageLink(paginate.prev)"><i class="fas fa-chevron-left"></i></router-link>
        <router-link class="link" :class="{current: paginate.current === n}"
                     :to="getPageLink(n)" v-for="n of paginateList()" :key="n.id">{{n}}</router-link>
        <router-link class="link next" :class="{current: paginate.next === null}"
                     :to="getPageLink(paginate.next)"><i class="fas fa-chevron-right"></i></router-link>
      </div>
    </div>

    <BlogSideBar :latests="latests" :categories="categories"/>
  </div>
</div>
</template>

<script>
import BlogPostList     from '../components/BlogPostList';
import BlogPostDetail   from '../components/BlogPostDetail';
import BlogCategoryList from '../components/BlogCategoryList';
import BlogSideBar      from '../components/BlogSideBar';

import BlogUtil from '../lib/BlogUtil';
import Paginate from '../model/Paginate';
import Post     from '../model/Post';
import PostApi  from '../api/PostApi';

export default {
  data () {
    return {
      posts:       Post.dummysArray(10),
      detail:      Post.model,
      currentView: 'BlogPostList',
      latests:     Post.dummysArray(4),
      categories:  Array(4).fill(Post.category),
      paginate:    Paginate.model(),
      flags: {
        showPaginate: true,
      },
    };
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
     * ページネーションを初期化する
     * @returns {void}
     */
    resetPaginate() {
      this.paginate = Paginate.model();
    },

    /**
     * ページネーションのリストを返す
     * @returns {array}
     */
    paginateList() {
      return Paginate.list(this.paginate, 5);
    },

    /**
     * ルートを基に現在のページを返す
     * @returns {number}
     */
    getPageNumber() {
      if (this.currentView === 'BlogPostList') {
        return this.getMode() ? parseInt(this.getMode()) : 1;
      }

      if (this.currentView === 'BlogCategoryList') {
        return this.getPage() ? parseInt(this.getPage()) : 1;
      }

      return null;
    },

    getPageLink(number) {
      if (this.currentView === 'BlogPostList') {
        return `/blog/${number}`;
      }

      if (this.currentView === 'BlogCategoryList') {
        return `/blog/category/${this.getKey()}/${number}`;
      }

      return '/blog';
    }
  },
  async mounted () {
    if (!BlogUtil.validateRoute(this.getMode(), this.getKey(), this.getPage())) {
      this.$router.push({path: '/blog'});
    }

    [this.currentView, this.flags.showPaginate] = BlogUtil.mainComponentName(this.getMode());

    this.paginate.current = this.getMode() || 1;

    if (this.currentView === 'BlogPostList') {
      const api = PostApi.get(this.getPageNumber());
      api.then(res => {
        this.posts    = res.data.data;
        this.paginate = Paginate.setup(res.data.links, res.data.meta);
      }).catch(e => {
        alert('ブログ一覧の取得に失敗しました');
        this.posts = [];
      });
    }
  },
  components: {
    BlogPostList,
    BlogPostDetail,
    BlogCategoryList,
    BlogSideBar,
  },
}
</script>

<style lang="scss" scoped>

</style>
