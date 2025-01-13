<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg p-6 relative">
      <!-- Bouton de fermeture -->
      <button @click="close" class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
        <Icon name="close" class="text-2xl" />
      </button>

      <!-- Titre -->
      <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6 text-center">
        {{ service ? 'Modifier' : 'Ajouter' }} un Service
      </h2>

      <!-- Formulaire -->
      <form @submit.prevent="handleSubmit" class="space-y-5">
        <!-- Champ Nom -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
          <input
              type="text"
              id="name"
              v-model="name"
              required
              placeholder="Entrez le nom du service"
              class="input"
          />
        </div>

        <!-- Champ Description -->
        <div>
          <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
          <textarea
              id="description"
              v-model="description"
              required
              placeholder="Entrez une description"
              rows="4"
              class="input"
          ></textarea>
        </div>

        <!-- Champ Taux horaire -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="hourlyRate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Taux horaire (€)</label>
            <input
                type="number"
                id="hourlyRate"
                v-model="hourlyRate"
                required
                placeholder="Exemple : 50"
                class="input"
            />
          </div>

          <!-- Champ Durée -->
          <div>
            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Durée (min)</label>
            <input
                type="number"
                id="duration"
                v-model="duration"
                required
                placeholder="Exemple : 60"
                class="input"
            />
          </div>
        </div>

        <!-- Boutons -->
        <div class="flex justify-end space-x-3 mt-6">
          <button @click="close" type="button" class="btn-secondary">Annuler</button>
          <button type="submit" class="btn-primary">
            {{ service ? 'Modifier' : 'Ajouter' }}
          </button>
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

const name = ref('');
const description = ref('');
const hourlyRate = ref(0);
const duration = ref(0);

const resetForm = () => {
  name.value = '';
  description.value = '';
  hourlyRate.value = 0;
  duration.value = 0;
};

watch(
    () => props.service,
    (newService) => {
      if (newService) {
        name.value = newService.name;
        description.value = newService.description;
        hourlyRate.value = newService.hourlyRate;
        duration.value = newService.duration;
      } else {
        resetForm();
      }
    },
    { immediate: true }
);

const handleSubmit = () => {
  const newService = {
    id: props.service?.id || crypto.randomUUID(),
    name: name.value,
    description: description.value,
    hourlyRate: hourlyRate.value,
    duration: duration.value,
  };

  props.service ? emit('update', newService) : emit('add', newService);
  resetForm();
  emit('close');
};



const close = () => {
  emit('close');
};
</script>
