<!-- pages/customers.vue -->
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
    <div v-if="customerStore.fetchError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
      <strong class="font-bold">Erreur !</strong>
      <span class="block sm:inline">{{ customerStore.fetchError }}</span>
    </div>

    <!-- Customers Table -->
    <div v-if="!customerStore.isLoading && !customerStore.fetchError">
      <div v-if="customerStore.allCustomers.length === 0" class="text-gray-500">
        Aucun client trouvé. Commencez à ajouter vos clients !
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-slate-800">
          <thead>
          <tr>
            <th class="py-2 px-4 bg-gray-200 dark:bg-slate-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
              Nom
            </th>
            <th class="py-2 px-4 bg-gray-200 dark:bg-slate-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
              Email
            </th>
            <th class="py-2 px-4 bg-gray-200 dark:bg-slate-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
              Téléphone
            </th>
            <th class="py-2 px-4 bg-gray-200 dark:bg-slate-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
              Adresse
            </th>
            <th class="py-2 px-4 bg-gray-200 dark:bg-slate-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
              Créé le
            </th>
            <th class="py-2 px-4 bg-gray-200 dark:bg-slate-700"></th>
          </tr>
          </thead>
          <tbody>
          <CustomerRow
              v-for="customer in customerStore.allCustomers"
              :key="customer.id"
              :customer="customer"
              @delete="handleDelete"
              @edit="setSelectedCustomer"
          />
          </tbody>
        </table>

      </div>
    </div>

    <!-- Add Customer Modal -->
    <AddCustomerModal
        :isOpen="isModalOpen"
        :customer="selectedUser"
        @close="closeAddModal"
        @add="handleAddCustomer"
        @update="handleUpdateCustomer"
        :is-editing="selectedUser !== null"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useCustomerStore } from '~/stores/customers'
import AddCustomerModal from "~/components/customers/CustomerModal.vue";
import CustomerRow from "~/components/customers/CustomerRow.vue";
import Loader from "~/components/loader/Loader.vue";

definePageMeta({
  middleware: 'auth',
})

const customerStore = useCustomerStore()

const isModalOpen = ref(false)
const selectedUser = ref(<Customer | null> null)

const openAddModal = () => {
  selectedUser.value = null
  isModalOpen.value = true
}

const closeAddModal = () => {
  isModalOpen.value = false
}

const setSelectedCustomer = (customer: Customer) => {
  selectedUser.value = customer
  isModalOpen.value = true
}

const handleAddCustomer = async (newCustomerData: Customer) => {
  try {
    delete newCustomerData.id
    // Envoyer la requête pour ajouter un client
    const response = await useNuxtApp().$axios.post('/customers', newCustomerData)

    // Ajouter le client au store avec les données retournées par l'API
    customerStore.addCustomer(response.data)

    // Fermer la modale
    isModalOpen.value = false
  } catch (err: any) {
    alert(err.response?.data?.message || 'Erreur lors de l\'ajout du client.')
  }
}

const handleUpdateCustomer = async (updatedCustomer: Customer) => {
  try {
    const id = updatedCustomer.id
    delete updatedCustomer.id
    // Appel API pour mettre à jour le client
    const response = await useNuxtApp().$axios.patch(`/customers/${id}`, updatedCustomer)

    // Mettre à jour le client dans le store
    const updatedData = response.data
    const customerIndex = customerStore.customers.findIndex(c => c.id === updatedData.id)
    if (customerIndex !== -1) {
      customerStore.customers[customerIndex] = updatedData
    }

    alert('Client mis à jour avec succès.')
  } catch (err: any) {
    alert(err.response?.data?.message || 'Erreur lors de la mise à jour du client.')
  }
}

const handleDelete = async (id: string) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
    try {
      // Supprimer le client via l'API
      await useNuxtApp().$axios.delete(`/customers/${id}`)

      // Supprimer le client du store
      customerStore.removeCustomer(id)

      alert('Client supprimé avec succès.')
    } catch (err: any) {
      alert(err.response?.data?.message || 'Erreur lors de la suppression du client.')
    }
  }
}

onMounted(() => {
  customerStore.fetchCustomers()
})
</script>

<style scoped>

/* Table Styling */
table {
  border-collapse: collapse;
}

th, td {
  border-bottom: 1px solid #ddd;
}

tr:hover {
  background-color: #f1f1f1;
}

.dark tr:hover {
  background-color: #2d3748;
}
</style>
