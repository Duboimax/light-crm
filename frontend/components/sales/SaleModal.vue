<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-lg w-full shadow-lg">
      <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">Ajouter une Vente</h2>
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <!-- Client -->
        <div>
          <label for="customer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Client
          </label>
          <select
              v-model="customer"
              id="customer"
              required
              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="" disabled>Choisir un client</option>
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">
              {{ customer.firstname }} {{ customer.lastname }}
            </option>
          </select>
        </div>

        <!-- Service -->
        <div>
          <label for="service" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Service
          </label>
          <select
              v-model="service"
              id="service"
              required
              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="" disabled>Choisir un service</option>
            <option v-for="service in services" :key="service.id" :value="service.id">
              {{ service.name }}
            </option>
          </select>
        </div>

        <!-- Total -->
        <div>
          <label for="total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Total (€)
          </label>
          <input
              v-model="total"
              id="total"
              type="number"
              min="0"
              required
              placeholder="Montant total"
              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>

        <!-- Discount -->
        <div>
          <label for="discount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Réduction (€) (Optionnel)
          </label>
          <input
              v-model="discount"
              id="discount"
              type="number"
              min="0"
              placeholder="Réduction appliquée"
              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>

        <!-- Commentaire -->
        <div>
          <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Commentaire (Optionnel)
          </label>
          <textarea
              v-model="comment"
              id="comment"
              rows="3"
              placeholder="Ajouter un commentaire (optionnel)"
              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
          ></textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
          <button
              type="button"
              @click="close"
              class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition"
          >
            Annuler
          </button>
          <button
              type="submit"
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
          >
            Ajouter
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useCustomerStore } from '~/stores/customers';
import { useServiceStore } from '~/stores/services';
import type { Service } from '~/interfaces/ServiceInterface';

const props = defineProps<{ isOpen: boolean }>();
const emit = defineEmits(['close', 'add']);

// Stores
const customerStore = useCustomerStore();
const serviceStore = useServiceStore();

// Références
const customers = ref<Customer[]>([]);
const services = ref<Service[]>([]);
const customer = ref<string | null>(null);
const service = ref<string | null>(null);
const total = ref<number | null>(null);
const discount = ref<number | null>(null);
const comment = ref<string | null>(null);

const resetForm = () => {
  customer.value = null;
  service.value = null;
  total.value = null;
  discount.value = null;
  comment.value = null;
};

const close = () => {
  resetForm();
  emit('close');
};

const handleSubmit = () => {
  emit('add', {
    customer: customer.value,
    service: service.value,
    total: total.value,
    discount: discount.value,
    comment: comment.value,
  });
  resetForm();
};

// Charger les données clients et services
onMounted(() => {
  customerStore.fetchCustomers().then(() => {
    customers.value = customerStore.allCustomers;
  });
  serviceStore.fetchServices().then(() => {
    services.value = serviceStore.allServices;
  });
});
</script>

<style scoped>
/* Ajoutez des styles personnalisés si nécessaire */
</style>
