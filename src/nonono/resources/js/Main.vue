<template>
<div id="app">

  <common-nav/>

  <div class="main">

    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>

    <common-footer/>
  </div>

</div>
</template>

<script>
import CommonNav    from './components/CommonNav';
import CommonFooter from './components/CommonFooter';

export default {
  components: {
    CommonNav,
    CommonFooter,
  },
};
</script>

<style>

</style>
