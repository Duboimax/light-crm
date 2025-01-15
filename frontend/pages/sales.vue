<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Mes Ventes</h1>
      <BaseButton @click="openAddModal" variant="primary">Ajouter une Vente</BaseButton>
    </div>

    <!-- Loading State -->
    <Loader v-if="saleStore.isLoading" />

    <!-- Error State -->
    <div
        v-if="saleStore.error"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
        role="alert"
    >
      <strong class="font-bold">Erreur !</strong>
      <span class="block sm:inline">{{ saleStore.error }}</span>
    </div>

    <!-- PrimeVue DataTable -->
    <div v-if="!saleStore.isLoading && !saleStore.error">
      <div v-if="saleStore.sales.length === 0" class="text-gray-500">
        Aucune vente trouvée. Commencez à ajouter vos ventes !
      </div>

      <DataTable
          :value="saleStore.sales"
          paginator
          :rows="10"
          dataKey="id"
          class="rounded-lg shadow-md"
      >
        <!-- Client -->
        <Column field="customer.firstname" header="Client" :sortable="true" style="min-width: 12rem">
          <template #body="{ data }">
            {{ data.customer.firstname }} {{ data.customer.lastname }}
          </template>
        </Column>

        <!-- Service -->
        <Column field="service.name" header="Service" :sortable="true" style="min-width: 12rem" />

        <!-- Date -->
        <Column field="saleDate" header="Date" :sortable="true" style="min-width: 12rem">
          <template #body="{ data }">
            {{ formatDate(data.saleDate) }}
          </template>
        </Column>

        <!-- Total -->
        <Column field="total" header="Total (€)" :sortable="true" style="min-width: 10rem">
          <template #body="{ data }">
            {{ data.total.toFixed(2) }}
          </template>
        </Column>

        <!-- Statut -->
        <Column field="status" header="Statut" :sortable="true" style="min-width: 10rem">
          <template #body="{ data }">
            <span :class="statusClass(data.status)">{{ SaleStatuses[data.status] }}</span>
          </template>
        </Column>

        <!-- Actions -->
        <Column header="Actions" style="min-width: 12rem">
          <template #body="{ data }">
            <SplitButton
                label="Modifier"
                :model="getActionMenu(data)"
                class="p-button-sm"
                @click="openEditModal(data)"
            />
          </template>
        </Column>
      </DataTable>
    </div>

    <!-- Sale Modal -->
    <SaleModal
        :isOpen="isModalOpen"
        :sale="selectedSale"
        @close="closeModal"
        @add="handleAddSale"
        @update="handleUpdateSale"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useSaleStore } from "~/stores/sales";
import SaleModal from "~/components/sales/SaleModal.vue";
import Loader from "~/components/loader/Loader.vue";
import BaseButton from "~/components/common/BaseButton.vue";
import { type Sale, SaleStatuses } from "~/interfaces/SaleInterface";

const saleStore = useSaleStore();
const isModalOpen = ref(false);
const selectedSale = ref<Sale | null>(null);

const openAddModal = () => {
  selectedSale.value = null;
  isModalOpen.value = true;
};

const openEditModal = (sale: Sale) => {
  selectedSale.value = sale;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const handleAddSale = async (newSaleData: Omit<Sale, "id">) => {
  try {
    await useNuxtApp().$axios.post("/sales", newSaleData);
    await saleStore.fetchSales();
    isModalOpen.value = false;
  } catch (err: any) {
    alert(err.response?.data?.message || "Erreur lors de l'ajout de la vente.");
  }
};

const handleUpdateSale = async (updatedSale: Sale) => {
  try {
    await useNuxtApp().$axios.patch(`/sales/${updatedSale.id}`, updatedSale);
    await saleStore.fetchSales();
    isModalOpen.value = false;
  } catch (err: any) {
    alert(err.response?.data?.message || "Erreur lors de la modification de la vente.");
  }
};

const handleCancel = async (id: string) => {
  if (confirm("Êtes-vous sûr de vouloir annuler cette vente ?")) {
    await saleStore.updateSaleStatus(id, "cancelled");
  }
};

const formatDate = (date: string): string => {
  return new Date(date).toLocaleDateString("fr-FR", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
};

const statusClass = (status: string): string => {
  switch (status) {
    case "completed":
      return "text-green-500 font-semibold";
    case "pending":
      return "text-yellow-500 font-semibold";
    case "cancelled":
      return "text-red-500 font-semibold";
    default:
      return "";
  }
};

const getActionMenu = (sale: Sale) => [
  {
    label: "Annuler",
    icon: "pi pi-times",
    command: () => handleCancel(sale.id),
    disabled: sale.status === "cancelled",
  },
  {
    label: "Historique",
    icon: "pi pi-clock",
    command: () => alert("Afficher l'historique"),
  },
];
onMounted(() => {
  saleStore.fetchSales();
});
</script>

<style scoped>
/* Ajout des styles pour DataTable */
.data-table {
  border-radius: 0.5rem;
  overflow: hidden;
}

.data-table th,
.data-table td {
  padding: 1rem;
  text-align: left;
}
</style>
