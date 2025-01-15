<template>
  <div class="mx-auto h-full w-full max-w-3xl p-4">
    <n-card :content-style="{ padding: '0' }" class="h-full">
      <div v-if="authStore.user" class="flex h-full gap-4 divide-x divide-slate-100">
        <!-- Barre latérale -->
        <nav class="w-52 p-4">
          <p class="font-bold">{{authStore.user.firstname}} {{authStore.user.lastname}}</p>
          <nav class="mt-4 flex flex-col space-y-1">
            <nuxt-link
              v-for="(item, index) in AppSettings"
              :key="index"
              :to="item.path"
              class="flex items-center gap-2"
            >
              <Icon :name="item.icon" />
              <span>{{ item.title }}</span>
            </nuxt-link>
          </nav>
        </nav>
        
        <div class="flex-grow p-4">
          <NuxtPage :user="authStore.user" />
        </div>
      </div>
      <div v-else class="flex justify-center items-center p-4">
        <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
      </div>
    </n-card>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { AppSettings } from '~/constants'

const authStore = useAuthStore()


onMounted(() => {
  authStore.initialize()
})
</script>

  
  <style scoped>
  .loader {
    border-top-color: #3490dc;
    animation: spin 1s ease-in-out infinite;
  }
  
  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }
  </style>
  
  

  