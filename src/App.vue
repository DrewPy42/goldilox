<template>
  <header>
    <mainMenu />
  </header>

  <RouterView />
  <footer>
    <a href="https://depositphotos.com/vector-images/objects.html">Green and Yellow Printed Circuit Board - depositphotos.com</a>
  </footer>
</template>


<script>
  import { RouterView } from 'vue-router';
  import mainMenu from './components/menus/mainMenu.vue';
  export default {
  components: {
      mainMenu,
    },
    setup() {

    }
  }
</script>


<style scoped>

</style>
