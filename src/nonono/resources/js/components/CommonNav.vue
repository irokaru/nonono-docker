<template>
<nav :class="{hidden: isAdminPage(), pc: !isSmartPhone, sp: isSmartPhone}">
  <template v-if="!isSmartPhone">
    <h1 title="トップへ">
      <router-link to="/"><img src="/img/logo.min.png" alt="ののの茶屋"></router-link>
    </h1>

    <ul>
      <li v-for="item in list" :key="item.to">
        <router-link :to="item.to">
          <span class="title">{{item.title}}</span>
          <span class="sub"><v-fa :icon="item.icon"/>{{item.en}}</span>
        </router-link>
      </li>
    </ul>
  </template>
  <template v-else>
    <ul>
      <li v-for="item in list" :key="item.to">
        <router-link :to="item.to">
          <span class="icon"><v-fa :icon="item.icon"/></span>
          <span class="title">{{item.title}}</span>
        </router-link>
      </li>
    </ul>
  </template>
</nav>
</template>

<script>
export default {
  data () {
    return {
      list: [
        {to: '/',         title: 'とっぷ',       en: 'TOP',      icon: ['fas', 'desktop']},
        {to: '/products', title: 'ぷろだくと',   en: 'PRODUCTS', icon: ['fas', 'box']},
        {to: '/material', title: 'そざい',       en: 'MATERIAL', icon: ['fas', 'folder-open']},
        {to: '/blog',     title: 'にっき',       en: 'BLOG',     icon: ['fas', 'book']},
        {to: '/link',     title: 'りんく',       en: 'LINK',     icon: ['fas', 'box']},
        {to: '/contact',  title: 'おといあわせ', en: 'CONTACT',  icon: ['fas', 'envelope']},
      ],
      isSmartPhone: Vue.$isSmartPhone(),
    };
  },
  methods: {
    /**
     * 管理者ページかどうかを返す
     * @returns {boolean}
     */
    isAdminPage() {
      return this.$route.path.match(/^\/admin/) !== null;
    },
    /**
     * 画面リサイズ時のやつ
     * @returns {void}
     */
    handleResize() {
      this.isSmartPhone = Vue.$isSmartPhone();
    },
  },
  mounted() {
    window.addEventListener('resize', _.throttle(e => {
      this.handleResize();
    }, 100));
  },
}
</script>
