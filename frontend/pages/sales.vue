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
      <Button label="Verify" />

    </div>

    <!-- Loading State -->
    <Loader v-if="saleStore.isLoading" />

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
        <table class="min-w-full border rounded-lg">
          <thead>
          <tr>
            <th class="py-2 px-4 text-left text-sm font-semibold">Client</th>
            <th class="py-2 px-4 text-left text-sm font-semibold">Service</th>
            <th class="py-2 px-4 text-left text-sm font-semibold">Date</th>
            <th class="py-2 px-4 text-left text-sm font-semibold">Total (€)</th>
            <th class="py-2 px-4 text-left text-sm font-semibold">Statut</th>
            <th class="py-2 px-4 text-center text-sm font-semibold">Actions</th>
          </tr>
          </thead>
          <tbody>
          <tr
              v-for="sale in saleStore.sales"
              :key="sale.id"
              class="border-t border-gray-300 dark:border-gray-600"
          >
            <td class="py-2 px-4">
              {{ sale.customer.firstname }} {{ sale.customer.lastname }}
            </td>
            <td class="py-2 px-4">
              {{ sale.service.name }}
            </td>
            <td class="py-2 px-4">
              {{ formatDate(sale.saleDate) }}
            </td>
            <td class="py-2 px-4">
              {{ sale.total.toFixed(2) }}
            </td>
            <td class="py-2 px-4">
              <span :class="statusClass(sale.status)">{{ SaleStatuses[sale.status] }}</span>
            </td>
            <td class="py-2 px-4 text-center">
              <BaseButton v-if="sale.status !== 'cancelled'" @click="handleCancel(sale.id)" variant="delete">annuler</BaseButton>
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
import Loader from "~/components/loader/Loader.vue";
import BaseButton from '~/components/common/BaseButton.vue';

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

