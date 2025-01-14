<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Mes Ventes</h1>
      <button
          @click="openAddModal"
          class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-200"
      >
        Ajouter une Vente
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="saleStore.isLoading" class="flex justify-center items-center">
      <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
    </div>

    <!-- Error State -->
    <div v-if="saleStore.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
      <strong class="font-bold">Erreur !</strong>
      <span class="block sm:inline">{{ saleStore.error }}</span>
    </div>

    <!-- Sales Table -->
    <div v-if="!saleStore.isLoading && !saleStore.error">
      <div v-if="saleStore.sales.length === 0" class="text-gray-500">
        Aucune vente trouvée. Commencez à ajouter vos ventes !
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 rounded-lg">
          <thead class="bg-gray-200 dark:bg-gray-700">
          <tr>
            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Client</th>
            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Service</th>
            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Date</th>
            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Total (€)</th>
            <th class="py-2 px-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Statut</th>
            <th class="py-2 px-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">Actions</th>
          </tr>
          </thead>
          <tbody>
          <tr
              v-for="sale in saleStore.sales"
              :key="sale.id"
              class="border-t border-gray-300 dark:border-gray-600"
          >
            <td class="py-2 px-4 text-gray-800 dark:text-gray-200">
              {{ sale.customer.firstname }} {{ sale.customer.lastname }}
            </td>
            <td class="py-2 px-4 text-gray-800 dark:text-gray-200">
              {{ sale.service.name }}
            </td>
            <td class="py-2 px-4 text-gray-800 dark:text-gray-200">
              {{ formatDate(sale.saleDate) }}
            </td>
            <td class="py-2 px-4 text-gray-800 dark:text-gray-200">
              {{ sale.total.toFixed(2) }}
            </td>
            <td class="py-2 px-4 text-gray-800 dark:text-gray-200">
              <span :class="statusClass(sale.status)">{{ SaleStatuses[sale.status] }}</span>
            </td>
            <td class="py-2 px-4 text-center">
              <button
                  v-if="sale.status !== 'cancelled'"
                  @click="handleCancel(sale.id)"
                  class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
              >
                Annuler
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Sale Modal -->
    <SaleModal
        :isOpen="isAddModalOpen"
        @close="closeAddModal"
        @add="handleAddSale"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useSaleStore } from '~/stores/sales';
import SaleModal from '~/components/sales/SaleModal.vue';
import {type Sale, SaleStatuses} from '~/interfaces/SaleInterface';

definePageMeta({
  middleware: 'auth',
});

const saleStore = useSaleStore();
const isAddModalOpen = ref(false);

const openAddModal = () => {
  isAddModalOpen.value = true;
};

const closeAddModal = () => {
  isAddModalOpen.value = false;
};

const handleAddSale = async (newSaleData: Omit<Sale, 'id'>) => {
  try {
    await useNuxtApp().$axios.post('/sales', newSaleData);
    await saleStore.fetchSales()
    isAddModalOpen.value = false;
  } catch (err: any) {
    alert(err.response?.data?.message || 'Erreur lors de l\'ajout de la vente.');
  }
};

const handleCancel = async (id: string) => {
  if (confirm('Êtes-vous sûr de vouloir annuler cette vente ?')) {
    await saleStore.updateSaleStatus(id, 'cancelled');
  }
};

const formatDate = (date: string): string => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

const statusClass = (status: string): string => {
  switch (status) {
    case 'completed':
      return 'text-green-500 font-semibold';
    case 'pending':
      return 'text-yellow-500 font-semibold';
    case 'cancelled':
      return 'text-red-500 font-semibold';
    default:
      return '';
  }
};

onMounted(() => {
  saleStore.fetchSales();
});
</script>

<style scoped>
/* Loader Styles */
.loader {
  border-top-color: #3490dc;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
