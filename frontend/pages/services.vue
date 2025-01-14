<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Services</h1>
      <button
          @click="openAddModal"
          class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition"
      >
        Ajouter un Service
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="serviceStore.isLoading" class="flex justify-center items-center">
      <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
    </div>

    <!-- Error State -->
    <div v-if="serviceStore.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
      <strong class="font-bold">Erreur !</strong>
      <span class="block sm:inline">{{ serviceStore.error }}</span>
    </div>

    <!-- Services Grid -->
    <div v-if="!serviceStore.isLoading && !serviceStore.error" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <ServiceCard
          v-for="service in serviceStore.allServices"
          :key="service.id"
          :service="service"
          @delete="handleDelete"
          @edit="openEditModal"
      />
    </div>

    <!-- Add/Edit Modal -->
    <ServiceModal
        :isOpen="isModalOpen"
        :service="selectedService"
        @close="closeModal"
        @add="handleAddService"
        @update="handleUpdateService"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useServiceStore } from '~/stores/services';
import type {Service} from "~/interfaces/ServiceInterface";
import ServiceModal from "~/components/services/ServiceModal.vue";
import ServiceCard from "~/components/services/ServiceCard.vue";

const serviceStore = useServiceStore();

const isModalOpen = ref(false);
const selectedService = ref<Service | null>(null);

const openAddModal = () => {
  selectedService.value = null;
  isModalOpen.value = true;
};

const openEditModal = (service: Service) => {
  selectedService.value = service;
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  selectedService.value = null;
};

const handleAddService = async (newService: Service) => {
  try {
    delete newService.id
    const response = await useNuxtApp().$axios.post('/services', newService)
    serviceStore.addService(response.data);
    closeModal();
  } catch (err: any) {
    alert(err.response?.data?.message || 'Erreur lors de l\'ajout du service.')
  }
};

const handleUpdateService = async (updatedService: Service) => {
  closeModal();
  try {
    const id = updatedService.id
    delete updatedService.id
    const response = await useNuxtApp().$axios.patch(`/services/${id}`, updatedService)
    serviceStore.updateService(response.data);
    alert('Service mis à jour avec succès.')
  } catch (err: any) {
    alert(err.response?.data?.message || 'Erreur lors de la mise à jour du service.')
  }
};

const handleDelete = async (id: string) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce service ?')) {
    try {
      await useNuxtApp().$axios.delete(`/services/${id}`)
      serviceStore.removeService(id);
      alert('Client service avec succès.')
    } catch (err: any) {
      alert(err.response?.data?.message || 'Erreur lors de la suppression du service.')
    }
  }
};

onMounted(() => {
  serviceStore.fetchServices();
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
