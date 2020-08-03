<template>
<div id="app">

  <MyNav/>

  <div class="main">
    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>
    <MyFooter/>
  </div>

</div>
</template>

<script>
export default {

}
</script>

<style>

</style>
