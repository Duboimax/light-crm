<template>
  <Card class="rounded-lg shadow-md">
    <template #header>
      <img
          alt="service header"
          :src="service.image || 'https://primefaces.org/cdn/primevue/images/usercard.png'"
          class="w-full object-cover h-40"
      />
    </template>
    <template #title>{{ service.name }}</template>
    <template #subtitle>Taux horaire : {{ service.hourlyRate }} €/h</template>
    <template #content>
      <p class="m-0 text-gray-700 dark:text-gray-300 text-sm">
        {{ service.description || 'Aucune description disponible.' }}
      </p>
      <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
        Durée : {{ service.duration }} minutes
      </p>
    </template>
    <template #footer>
      <div class="flex gap-4 mt-4">
        <Button
            label="Modifier"
            icon="pi pi-pencil"
            severity="info"
            outlined
            class="w-full"
            @click="$emit('edit', service)"
        />
        <Button
            label="Supprimer"
            icon="pi pi-trash"
            severity="danger"
            outlined
            class="w-full"
            @click="$emit('delete', service.id)"
        />
      </div>
    </template>
  </Card>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';
import type { Service } from '~/interfaces/ServiceInterface';

const props = defineProps<{
  service: Service;
}>();

const emit = defineEmits(['edit', 'delete']);
</script>
