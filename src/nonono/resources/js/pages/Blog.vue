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

      <div v-if="flags.isLoading" class="loading-wrapper-center">
        <Loading/>
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
import Loading          from '../components/Loading';

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
        isLoading:    false,
      },
    };
  },
  methods: {
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

    getPageLink(number) {
      return BlogUtil.getPageLink(this.$route, this.currentView, number);
    },

    /**
     * ページ読み込み時の処理
     */
    async mount() {
      if (!BlogUtil.validateRoute(this.$route)) {
        this.$router.push({path: '/blog'});
      }

      if (BlogUtil.checkRouteChange(this.$route, this.currentView, this.paginate)) {
        return;
      }

      [this.currentView, this.flags.showPaginate] = BlogUtil.mainComponentName(this.$route);

      this.paginate.current = BlogUtil.getPageNumber(this.$route, this.currentView);

      this.flags.isLoading = true;

      if (BlogUtil.isPostList(this.currentView)) {
        const api = PostApi.get(BlogUtil.getPageNumber(this.$route, this.currentView));
        api.then(res => {
          this.posts    = res.data.data;
          this.paginate = Paginate.setup(res.data.links, res.data.meta);
        }).catch(e => {
          alert('ブログ一覧の取得に失敗しました');
          this.posts = [];
        }).finally(() => {
          this.flags.isLoading = false;
        });
      } else if (BlogUtil.isCategoryList(this.currentView)) {
        const api = PostApi.getAsCategory(BlogUtil.getKey(this.$route), BlogUtil.getPageNumber(this.$route, this.currentView));
        api.then(res => {
          this.posts    = res.data.data;
          this.paginate = Paginate.setup(res.data.links, res.data.meta);
        }).catch(e => {
          alert('ブログ一覧の取得に失敗しました');
          this.posts = [];
        }).finally(() => {
          this.flags.isLoading = false;
        });
      } else if (BlogUtil.isPostDetail(this.currentView)) {
        const api = PostApi.show(BlogUtil.getKey(this.$route));
        api.then(res => {
          this.detail = res.data;
        }).catch(e => {
          this.$router.push({path: '/blog'});
        }).finally(() => {
          this.flags.isLoading = false;
        });
      }
    },
  },
  async mounted () {
    this.mount();
  },
  watch: {
    $route(to, from) {
      this.mount();
    },
  },
  components: {
    BlogPostList,
    BlogPostDetail,
    BlogCategoryList,
    BlogSideBar,
    Loading,
  },
}
</script>
