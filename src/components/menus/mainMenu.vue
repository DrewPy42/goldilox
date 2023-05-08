<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <img class="navbar-brand fit-image" src="../../../public/logo-lg.png" alt="DataMAP Logo">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <router-link
            class="nav-link"
            to="/"
          >
          Home
         </router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
 import { RouterLink } from 'vue-router'

</script>

<style scoped lang="scss">
  @import "../../styles/menus.scss";
</style>