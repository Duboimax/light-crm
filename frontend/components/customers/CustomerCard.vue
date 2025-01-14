<!-- components/CustomerCard.vue -->
<template>
  <div class="bg-white shadow-md rounded-lg p-6 flex flex-col justify-between">
    <div>
      <h2 class="text-xl font-semibold mb-2">{{ customer.name }}</h2>
      <p class="text-gray-600">{{ customer.email }}</p>
    </div>
    <button @click="deleteCustomer" class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200">
      Supprimer
    </button>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

interface Customer {
  id: number
  name: string
  email: string
}

const props = defineProps<{
  customer: Customer
}>()

const emit = defineEmits(['delete'])

const deleteCustomer = () => {
  emit('delete', props.customer.id)
}
</script>

<style scoped>
/* Ajoutez des styles supplémentaires si nécessaire */
</style>
