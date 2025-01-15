<template>
  <div class="rounded-lg shadow-md overflow-hidden flex flex-col">
    <div class="p-4">
      <h3 class="text-lg font-bold truncate">{{ service.name }}</h3>
      <p class="text-sm mb-2 truncate">{{ service.description }}</p>
      <div class="mt-4">
        <p class="font-semibold">Taux horaire : {{ service.hourlyRate }} €/h</p>
        <p class="text-gray-500 dark:text-gray-400 text-sm">Durée : {{ service.duration }} min</p>
      </div>
    </div>
    <div class="flex justify-between p-4">
      <button
          @click="$emit('edit', service)"
          class="text-blue-600 dark:text-blue-400 hover:underline"
      >
        Modifier
      </button>
      <button
          @click="$emit('delete', service.id)"
          class="text-red-600 dark:text-red-400 hover:underline"
      >
        Supprimer
      </button>
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
