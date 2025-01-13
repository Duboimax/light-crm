import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useNuxtApp } from '#app';
import type {Service} from "~/interfaces/ServiceInterface";

export const useServiceStore = defineStore('services', () => {
    const nuxtApp = useNuxtApp();

    const services = ref<Service[]>([]);
    const isLoading = ref(false);
    const error = ref<string | null>(null);

    const allServices = computed(() => services.value);

    const fetchServices = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await nuxtApp.$axios.get('/services');
            services.value = response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Erreur lors de la récupération des services.';
        } finally {
            isLoading.value = false;
        }
    };

    const addService = (service: Service) => {
        services.value = [...services.value, service];
    };

    const removeService = (id: string) => {
        services.value = services.value.filter((service) => service.id !== id);
    };

    const updateService = (updatedService: Service) => {
        const index = services.value.findIndex((service) => service.id === updatedService.id);
        if (index !== -1) {
            services.value[index] = updatedService;
        }
    };

    return {
        services,
        isLoading,
        error,
        allServices,
        fetchServices,
        addService,
        removeService,
        updateService,
    };
});

