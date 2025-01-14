<template>
    <div class="mx-auto h-full w-full max-w-3xl p-4">
      <n-card :content-style="{ padding: '0' }" class="h-full">
        <div class="flex h-full gap-4 divide-x divide-slate-100">
          <main class="flex-grow p-4">
            <div v-if="authStore.isLoading" class="flex justify-center items-center my-8">
              <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
            </div>
  
            <div
              v-else-if="authStore.error"
              class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
            >
              <strong class="font-bold">Erreur !</strong>
              <span class="block sm:inline">{{ authStore.error }}</span>
            </div>
  
            <div v-else-if="authStore.user">
              <section class="flex flex-col items-center justify-center gap-4 text-center">
                <n-avatar circle :size="90" :src="authStore.user.image" />
                <article>
                  <h2 class="text-base">
                    {{ authStore.user.firstname + ' ' + authStore.user.lastname }}
                  </h2>
                  <p class="text-sm text-gray-500">
                    <small>{{ authStore.user.email }}</small>
                  </p>
                </article>
              </section>
  
              <article class="my-4">
                <n-card>
                  <ul class="flex flex-col divide-y divide-slate-100">
                    <li v-for="(item, index) in AppSettings" :key="index">
                      <NuxtLink
                        :to="item.path"
                        class="flex items-center justify-between gap-2 py-1"
                      >
                        <div class="flex items-center gap-2">
                          <Icon :name="item.icon" />
                          <span>{{ item.title }}</span>
                        </div>
                        <Icon name="solar:alt-arrow-right-line-duotone" />
                      </NuxtLink>
                    </li>
                  </ul>
                </n-card>
              </article>
            </div>
          </main>
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
  
  