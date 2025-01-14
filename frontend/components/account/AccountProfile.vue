<template>
  <NuxtLink to="/settings">
    <div class="flex gap-4">
      <article>
        <!-- <h2 class="text-base">{{ user }} </h2> -->
        <p class="text-sm text-gray-500">
          <small>{{ user.firstname }} {{ user.lastname }}</small>
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
