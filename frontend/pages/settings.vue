<template>
  <div class="mx-auto h-full w-full  p-4">
    <div class="h-full rounded-md border-2 border-slate-100 ">
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
      <Loader v-else />
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { AppSettings } from '~/constants'
import Loader from "~/components/loader/Loader.vue";

const authStore = useAuthStore()


onMounted(() => {
  authStore.initialize()
})
</script>

  

  
  

  