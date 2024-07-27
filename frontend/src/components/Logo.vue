<template>
  <div>
    <router-link to="/" style="text-decoration: none;font-size: 28px" class="w">
      <span class="font-weight-black " style="font-size: 30px" >$</span>
      <span class="font-weight-black " >tock</span>
      <span class="font-weight-black">W</span>
      <span class="font-weight-black ">atcher</span>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'Logo'
}
</script>

<style scoped>
 .w {
   background: linear-gradient(to right, #2196F3, #00B661 70%);
  -webkit-background-clip: text;
   -webkit-text-fill-color: transparent;

 }

</style>
