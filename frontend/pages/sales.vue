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

    <!-- Sales Grid -->
    <div v-if="!saleStore.isLoading && !saleStore.error">
      <div v-if="saleStore.sales.length === 0" class="text-gray-500">
        Aucune vente trouvée. Commencez à ajouter vos ventes !
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <SaleCard
            v-for="sale in saleStore.sales"
            :key="sale.id"
            :sale="sale"
            @delete="handleCancel"
        />
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
import SaleCard from '~/components/sales/SaleCard.vue';
import SaleModal from '~/components/sales/SaleModal.vue';
import type { Sale } from '~/interfaces/SaleInterface';

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
    const response = await useNuxtApp().$axios.post('/sales', newSaleData);
    saleStore.addSale(response.data);
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

// Charger les ventes lors du montage de la page
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
