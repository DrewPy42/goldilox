<template>
  <header>
    <mainMenu />
  </header>
  <h1>{{ message }}</h1>
  <RouterView />
  <footer class="bg-primary bg-gradient fixed-bottom p-3 mb-5">
    <div class="col-md-12 text-end">
      <div class="copyright fs-6 text-white">
        Copyright ©{{ currentYear }} by M. Andrew Patterson. All rights reserved.
      </div>
    </div>
  </footer>
</template>

<script>
import { RouterView } from 'vue-router'
import mainMenu from './components/menus/mainMenu.vue'
import { computed, ref, onMounted } from 'vue'

export default {
  components: {
    mainMenu,
    RouterView
  },
  setup() {
    const message = ref('')
    const currentYear = computed(() => {
      return new Date().getFullYear()
    })

    onMounted(async () => {
      const response = await fetch('http://localhost:3000')
      const data = await response.json()
      message.value = data.message
      console.log(data.message)
    })

    return { currentYear, message }
  }
}
</script>

<style lang="scss" scoped></style>
