<template>
  <div class="flex h-screen flex-col overflow-hidden">
    <AppBar />

    <section class="flex h-full w-full flex-grow">
      <aside
          v-if="isAuthenticated"
          class="group relative flex h-full flex-col items-center"
      >
        <div class="flex w-40 items-center pt-4">
          <AppSideBar />
        </div>
      </aside>
      <main
          :class="{'w-full': !isAuthenticated, 'flex-grow': true}"
          class="flex h-full w-full flex-grow flex-col overflow-y-auto"
      >
        <slot />
      </main>
    </section>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { computed, onMounted } from 'vue'

definePageMeta({
  middleware: 'auth',
});

const authStore = useAuthStore()
const isAuthenticated = computed(() => authStore.isAuthenticated)
const user = computed(() => authStore.user)

onMounted(() => {
  authStore.initialize()
})
</script>

<style scoped>
/* Ajoutez des styles spécifiques si nécessaire */
</style>
