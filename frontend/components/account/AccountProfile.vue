<template>
  <NuxtLink to="/settings/accounts">
    <div class="flex gap-4">
<!--      <n-avatar circle :size="60" :src="user.image" />-->
      <article>
        <h2 class="text-base">{{ user.username }} </h2>
        <p class="text-sm text-gray-500">
          <small>{{ user.email }}</small>
        </p>
      </article>
    </div>
  </NuxtLink>
</template>

<script setup lang="ts">
import {useAuthStore} from "~/stores/auth";

const authStore = useAuthStore()

const user: User = authStore.user
</script>
