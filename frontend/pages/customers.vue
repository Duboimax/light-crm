<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Mes Clients</h1>
      <button
          @click="openAddModal"
          class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-200"
      >
        Ajouter un Client
      </button>
    </div>

    <!-- Loading State -->
    <Loader v-if="customerStore.isLoading"/>

    <!-- Error State -->
    <div
        v-if="customerStore.fetchError"
        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
        role="alert"
    >
      <strong class="font-bold">Erreur !</strong>
      <span class="block sm:inline">{{ customerStore.fetchError }}</span>
    </div>

    <!-- PrimeVue DataTable -->
    <div v-if="!customerStore.isLoading && !customerStore.fetchError">
      <div v-if="customerStore.customers.length === 0" class="text-gray-500">
        Aucun client trouvé. Commencez à ajouter vos clients !
      </div>

      <DataTable
          :value="customerStore.customers"
          paginator
          :rows="10"
          dataKey="id"
          :loading="customerStore.isLoading"
      >
        <!-- Colonnes -->
        <Column field="firstname" header="Prénom" :sortable="true" style="min-width: 12rem"></Column>
        <Column field="lastname" header="Nom" :sortable="true" style="min-width: 12rem"></Column>
        <Column field="email" header="Email" :sortable="true" style="min-width: 16rem"></Column>
        <Column field="phone" header="Téléphone" :sortable="true" style="min-width: 12rem"></Column>
        <Column field="createdAt" header="Créé le" :sortable="true" style="min-width: 12rem">
          <template #body="{ data }">{{ formatDate(data.createdAt) }}</template>
        </Column>

        <!-- Actions -->
        <Column header="Actions" style="min-width: 12rem">
          <template #body="{ data }">
            <SplitButton
                label="Modifier"
                @click="setSelectedCustomer(data)"
                :model="getActions(data)"
                class="w-full"
            />
          </template>
        </Column>
      </DataTable>
    </div>

    <!-- Add Customer Modal -->
    <AddCustomerModal
        :isOpen="isModalOpen"
        :customer="selectedCustomer"
        @close="closeAddModal"
        @add="handleAddCustomer"
        @update="handleUpdateCustomer"
        :is-editing="selectedCustomer !== null"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useCustomerStore } from '~/stores/customers';
import AddCustomerModal from '~/components/customers/CustomerModal.vue';
import { useToast } from 'primevue/usetoast';
import Loader from "~/components/loader/Loader.vue";

definePageMeta({
  middleware: 'auth',
});

const customerStore = useCustomerStore();
const toast = useToast();

const isModalOpen = ref(false);
const selectedCustomer = ref<Customer | null>(null);

const openAddModal = () => {
  selectedCustomer.value = null;
  isModalOpen.value = true;
};

const closeAddModal = () => {
  isModalOpen.value = false;
};

const setSelectedCustomer = (customer: Customer) => {
  selectedCustomer.value = customer;
  isModalOpen.value = true;
};

const handleAddCustomer = async (newCustomerData: Customer) => {
  try {
    delete newCustomerData.id;
    const response = await useNuxtApp().$axios.post('/customers', newCustomerData);
    customerStore.addCustomer(response.data);
    isModalOpen.value = false;
  } catch (err: any) {
    alert(err.response?.data?.message || 'Erreur lors de l\'ajout du client.');
  }
};

const handleUpdateCustomer = async (updatedCustomer: Customer) => {
  try {
    const id = updatedCustomer.id;
    delete updatedCustomer.id;
    const response = await useNuxtApp().$axios.patch(`/customers/${id}`, updatedCustomer);
    // customerStore.updateCustomer(response.data);
    toast.add({ severity: 'success', summary: 'Succès', detail: 'Client mis à jour avec succès.', life: 3000 });
  } catch (err: any) {
    alert(err.response?.data?.message || 'Erreur lors de la mise à jour du client.');
  }
};

const handleDelete = async (id: string) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
    try {
      await useNuxtApp().$axios.delete(`/customers/${id}`);
      customerStore.removeCustomer(id);
      toast.add({ severity: 'warn', summary: 'Supprimé', detail: 'Client supprimé avec succès.', life: 3000 });
    } catch (err: any) {
      alert(err.response?.data?.message || 'Erreur lors de la suppression du client.');
    }
  }
};

const getActions = (customer: Customer) => [
  {
    label: 'Envoyer un Email',
    command: () => {
      toast.add({ severity: 'info', summary: 'Envoyé', detail: `Email envoyé à ${customer.email}.`, life: 3000 });
    },
  },
  {
    label: 'Historique',
    command: () => {
      toast.add({ severity: 'info', summary: 'Historique', detail: `Affichage de l'historique pour ${customer.firstname}.`, life: 3000 });
    },
  },
  {
    separator: true,
  },
  {
    label: 'Supprimer',
    command: () => handleDelete(customer.id),
  },
];

const formatDate = (date: string): string => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};

onMounted(() => {
  customerStore.fetchCustomers();
});
</script>

<style scoped>
.card {
  background: white;
  padding: 1rem;
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>
