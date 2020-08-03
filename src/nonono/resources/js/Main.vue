<template>
<div id="app">

  <common-nav/>

  <div class="main">
    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>
    <MyFooter/>
  </div>

</div>
</template>

<script>
import CommonNav from './components/CommonNav';

export default {
  components: {
    CommonNav,
  },
};
</script>

<style>

</style>
