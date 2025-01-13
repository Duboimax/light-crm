<template>
  <div class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden">
    <img :src="service.image" alt="Service Image" class="w-full h-48 object-cover">
    <div class="p-4">
      <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ service.title }}</h3>
      <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">{{ service.description }}</p>
      <p class="text-indigo-600 dark:text-indigo-400 font-semibold">{{ service.price }} €</p>
    </div>
    <div class="p-4 flex justify-between">
      <button @click="$emit('edit', service)" class="text-blue-600 hover:underline">Modifier</button>
      <button @click="$emit('delete', service.id)" class="text-red-600 hover:underline">Supprimer</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import type {Service} from "~/interfaces/ServiceInterface";

const props = defineProps<{
  service: Service;
}>();

const emit = defineEmits(['edit', 'delete']);
</script>
