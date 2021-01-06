<template>
<div class="post-box">
  <div class="title">
    <router-link :to="'/blog/post/' + id">{{title}}</router-link>
  </div>

  <div class="info">
    <div class="date"><v-fa :icon="['far', 'clock']"/> {{date}}</div>
    <div class="categories">
      <v-fa :icon="['fas', 'tag']"/>
      <router-link :to="'/blog/category/' + category.category" v-for="(category, index) in categories"
                   :key="'category' + category.post_id + '-' + index">
        {{category.category}}
      </router-link>
    </div>
  </div>

</div>
</template>

<script>
export default {
  props: {
    id:         Number,
    title:      String,
    date:       String,
    categories: Array,
  }
}
</script>
