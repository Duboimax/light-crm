<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-lg w-full">
      <h2 class="text-xl font-bold mb-4">Ajouter une Vente</h2>
      <form @submit.prevent="handleSubmit">
        <!-- Customer -->
        <div class="mb-4">
          <label for="customer" class="block text-sm font-medium">Client</label>
          <input v-model="customer" id="customer" type="text" required class="input" />
        </div>
        <!-- Service -->
        <div class="mb-4">
          <label for="service" class="block text-sm font-medium">Service</label>
          <input v-model="service" id="service" type="text" required class="input" />
        </div>
        <!-- Total -->
        <div class="mb-4">
          <label for="total" class="block text-sm font-medium">Total (€)</label>
          <input v-model="total" id="total" type="number" required class="input" />
        </div>
        <!-- Buttons -->
        <div class="flex justify-end space-x-2">
          <button type="button" @click="close" class="btn-secondary">Annuler</button>
          <button type="submit" class="btn-primary">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ isOpen: boolean }>();
const emit = defineEmits(['close', 'add']);

const customer = ref('');
const service = ref('');
const total = ref<number | null>(null);

const handleSubmit = () => {
  emit('add', { customer, service, total });
  resetForm();
};

const resetForm = () => {
  customer.value = '';
  service.value = '';
  total.value = null;
};

const close = () => emit('close');
</script>
