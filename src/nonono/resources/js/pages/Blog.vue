<template>
<div id="blog">
  <h2>にっき</h2>

  <div class="content">
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
  components: {
    BlogSideBar,
  }
}
</script>

<style lang="scss" scoped>

</style>
