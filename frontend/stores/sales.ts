// stores/sales.ts
import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useNuxtApp } from '#app';
import type {Sale} from "~/interfaces/SaleInterface";

export const useSaleStore = defineStore('sales', () => {
    const nuxtApp = useNuxtApp();

    const sales = ref<Sale[]>([]);
    const isLoading = ref(false);
    const error = ref<string | null>(null);

    const fetchSales = async () => {
        isLoading.value = true;
        error.value = null;
        try {
            const response = await nuxtApp.$axios.get('/sales');
            sales.value = response.data;
            console.log(sales.value)
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Erreur lors de la récupération des ventes.';
        } finally {
            isLoading.value = false;
        }
    };

    const addSale = (sale: Sale) => {
        sales.value.push(sale);
    };

    const removeSale = (id: string) => {
        sales.value = sales.value.filter((sale) => sale.id !== id);
    };

    const updateSale = (updatedSale: Sale) => {
        const index = sales.value.findIndex((sale) => sale.id === updatedSale.id);
        if (index !== -1) {
            sales.value[index] = updatedSale;
        }
    };

    const updateSaleStatus = async (id: string, status: string) => {
        try {
            await useNuxtApp().$axios.patch(`/sales/${id}`);
            const updatedSale = { ...sales.value.find((sale) => sale.id === id), status };
            if (updatedSale) {
                updateSale(updatedSale as Sale);
            }
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Erreur lors de la mise à jour du statut.';
        }
    };


    return {
        sales: computed(() => sales.value),
        isLoading,
        error,
        fetchSales,
        addSale,
        removeSale,
        updateSale,
        updateSaleStatus
    };
});
