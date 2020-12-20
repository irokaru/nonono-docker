<template>
<div id="blog">
  <h2>にっき</h2>

  <div class="content">

    <div class="blog-main">
      <component :is="view" :posts="posts" :detail="detail"/>

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
      posts:      Post.dummysArray(10),
      detail:     Post.model(),
      view:       'BlogPostList',
      latests:    Post.dummysArray(4),
      categories: Array(4).fill(Post.category),
      paginate:   Paginate.model(),
      flags: {
        showPaginate: true,
        isLoading:    false,
      },
    };
  },
  methods: {
    /**
     * 記事一覧を初期化する
     * @returns {void}
     */
    resetPosts() {
      this.posts = Post.dummysArray(10);
    },

    /**
     * 記事詳細を初期化する
     * @returns {void}
     */
    resetDetail() {
      this.detail = Post.model();
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
     * 各種ページのリンクを返す
     * @returns {string}
     */
    getPageLink(number) {
      return BlogUtil.getPageLink(this.$route, this.view, number);
    },

    /**
     * 一覧系APIを実行するやつ
     * @returns {void}
     */
    execListApi() {
      const api = () => {
        if (BlogUtil.isPostList(this.view)) {
          return PostApi.get(BlogUtil.getPageNumber(this.$route, this.view));
        }
        return PostApi.getAsCategory(BlogUtil.getKey(this.$route), BlogUtil.getPageNumber(this.$route, this.view));
      }

      api().then(res => {
        this.posts    = res.data.data;
        this.paginate = Paginate.setup(res.data.links, res.data.meta);
      }).catch(e => {
        alert('ブログ一覧の取得に失敗しました');
        this.posts = [];
      }).finally(() => {
        this.flags.isLoading = false;
      });
    },

    /**
     * 詳細系APIを実行するやつ
     * @returns {void}
     */
    execDetailApi() {
      const api = PostApi.show(BlogUtil.getKey(this.$route));

      api.then(res => {
        this.detail = res.data;
      }).catch(e => {
        this.$router.push({path: '/blog'});
      }).finally(() => {
        this.flags.isLoading = false;
      });
    },

    /**
     * サイドバー系APIを実行するやつ
     * @returns {void}
     */
    execSideApi() {
      const apis = [
        PostApi.getLatests(),
        PostApi.getCategories(),
      ];

      axios.all(apis).then(([latests, categories]) => {
        this.latests    = latests.data;
        this.categories = categories.data;

        Vue.$setStore('$posts.latests',    latests.data);
        Vue.$setStore('$posts.categories', categories.data);
      }).catch(e => {
        console.log(e);
        alert('サイドバーのブログ情報が取得できませんでした')
      });
    },

    /**
     * ページ読み込み時の処理
     */
    async mount() {
      if (!BlogUtil.validateRoute(this.$route)) {
        this.$router.push({path: '/blog'});
      }

      if (BlogUtil.checkRouteChange(this.$route, this.view, this.paginate)) {
        return;
      }

      this.execSideApi();

      const nextView = BlogUtil.mainComponentName(this.$route);

      if (!BlogUtil.isPostDetail(this.view) && BlogUtil.isPostDetail(nextView[0])) {
        this.resetPosts();
      }

      if (BlogUtil.isPostDetail(this.view) && !BlogUtil.isPostDetail(nextView[0])) {
        this.resetDetail();
      }

      [this.view, this.flags.showPaginate] = nextView;

      this.paginate.current = BlogUtil.getPageNumber(this.$route, this.view);

      this.flags.isLoading = true;

      if (!BlogUtil.isPostDetail(this.view)) {
        this.execListApi();
      } else {
        this.execDetailApi();
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
