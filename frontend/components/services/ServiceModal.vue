<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-800 rounded-lg w-full max-w-lg p-6">
      <h2 class="text-2xl font-bold mb-4">{{ service ? 'Modifier' : 'Ajouter' }} un Service/Produit</h2>
      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label for="title" class="block text-gray-700 dark:text-gray-200">Titre</label>
          <input type="text" id="title" v-model="title" required class="input">
        </div>
        <div class="mb-4">
          <label for="description" class="block text-gray-700 dark:text-gray-200">Description</label>
          <textarea id="description" v-model="description" required class="input"></textarea>
        </div>
        <div class="mb-4">
          <label for="price" class="block text-gray-700 dark:text-gray-200">Prix</label>
          <input type="number" id="price" v-model="price" required class="input">
        </div>
        <div class="flex justify-end space-x-2">
          <button @click="close" type="button" class="btn-secondary">Annuler</button>
          <button type="submit" class="btn-primary">{{ service ? 'Modifier' : 'Ajouter' }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue';
import type {Service} from "~/interfaces/ServiceInterface";

const props = defineProps<{
  isOpen: boolean;
  service?: Service | null;
}>();

const emit = defineEmits(['close', 'add', 'update']);

// Champs réactifs
const title = ref('');
const description = ref('');
const price = ref(0);

// Fonction pour réinitialiser le formulaire
const resetForm = () => {
  title.value = '';
  description.value = '';
  price.value = 0;
};

// Remplir les champs si un service est fourni
watch(
    () => props.service,
    (newService) => {
      if (newService) {
        title.value = newService.title;
        description.value = newService.description;
        price.value = newService.price;
      } else {
        resetForm();
      }
    },
    { immediate: true } // Exécution immédiate au montage
);

const handleSubmit = () => {
  const newService = {
    id: props.service?.id || crypto.randomUUID(),
    title: title.value,
    description: description.value,
    price: price.value,
    image: props.service?.image || '/placeholder.png',
    createdAt: props.service?.createdAt || new Date().toISOString(),
  };

  // Émettre les événements correspondants
  props.service ? emit('update', newService) : emit('add', newService);
  resetForm();
  emit('close');
};

const close = () => {
  emit('close');
};
</script>
