// stores/customers.ts
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useNuxtApp } from '#app'

export const useCustomerStore = defineStore('customers', () => {
    const nuxtApp = useNuxtApp()

    const customers = ref<Customer[]>([])
    const loading = ref<boolean>(false)
    const error = ref<string | null>(null)

    const allCustomers = computed(() => customers.value)
    const isLoading = computed(() => loading.value)
    const fetchError = computed(() => error.value)

    const fetchCustomers = async () => {
        loading.value = true
        error.value = null
        try {
            const response = await nuxtApp.$axios.get('/customers')
            customers.value = response.data
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Erreur lors de la récupération des clients.'
        } finally {
            loading.value = false
        }
    }

    const addCustomer = (customer: Omit<Customer, "id" | "createdAt">) => {
        customers.value = [...customers.value, customer]
    }

    const removeCustomer = (id: string) => {
        customers.value = customers.value.filter(c => c.id !== id)
    }

    return {
        customers,
        loading,
        error,
        allCustomers,
        isLoading,
        fetchError,
        fetchCustomers,
        addCustomer,
        removeCustomer,
    }
})
