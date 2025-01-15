<template>
  <Dialog
      :visible="isOpen"
      :modal="true"
      :header="service ? 'Modifier un Service' : 'Ajouter un Service'"
      :closable="true"
      :style="{ width: '50vw' }"
      @hide="close"
  >
    <form @submit.prevent="handleSubmit" class="p-fluid">
      <!-- Champ Nom -->
      <div class="field">
        <label for="name">Nom</label>
        <InputText
            id="name"
            v-model="name"
            required
            placeholder="Entrez le nom du service"
        />
      </div>

      <!-- Champ Description -->
      <div class="field">
        <label for="description">Description</label>
        <Textarea
            id="description"
            v-model="description"
            required
            placeholder="Entrez une description"
            rows="4"
            autoResize
        />
      </div>

      <!-- Champ Taux horaire et Durée -->
      <div class="formgrid grid">
        <div class="field col-6">
          <label for="hourlyRate">Taux horaire (€)</label>
          <InputNumber
              id="hourlyRate"
              v-model="hourlyRate"
              mode="decimal"
              :min="0"
              placeholder="Exemple : 50"
          />
        </div>

        <div class="field col-6">
          <label for="duration">Durée (min)</label>
          <InputNumber
              id="duration"
              v-model="duration"
              mode="decimal"
              :min="0"
              placeholder="Exemple : 60"
          />
        </div>
      </div>

      <!-- Boutons -->
      <div class="flex justify-end gap-3 mt-4">
        <Button
            label="Annuler"
            icon="pi pi-times"
            class="p-button-text"
            @click="close"
        />
        <Button
            :label="service ? 'Modifier' : 'Ajouter'"
            icon="pi pi-check"
            type="submit"
            class="p-button-primary"
        />
      </div>
    </form>
  </Dialog>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue';
import type { Service } from '~/interfaces/ServiceInterface';

const props = defineProps<{
  isOpen: boolean;
  service?: Service | null;
}>();

const emit = defineEmits(['close', 'add', 'update']);

const name = ref('');
const description = ref('');
const hourlyRate = ref<number | null>(null);
const duration = ref<number | null>(null);

const resetForm = () => {
  name.value = '';
  description.value = '';
  hourlyRate.value = null;
  duration.value = null;
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
    hourlyRate: hourlyRate.value ?? 0,
    duration: duration.value ?? 0,
  };

  props.service ? emit('update', newService) : emit('add', newService);
  resetForm();
  emit('close');
};

const close = () => {
  emit('close');
};
</script>
