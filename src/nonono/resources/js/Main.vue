<template>
<div id="app">

  <common-nav/>

  <div class="main">

    <router-view v-slot="{ Component }">
      <transition name="fade" mode="out-in">
        <component :is="Component" />
      </transition>
    </router-view>

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
