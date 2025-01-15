<!-- components/CustomerCard.vue -->
<template>
  <div class="bg-white shadow-md rounded-lg p-6 flex flex-col justify-between">
    <div>
      <h2 class="text-xl font-semibold mb-2">{{ customer.name }}</h2>
      <p class="text-gray-600">{{ customer.email }}</p>
    </div>
    <BaseButton  @click="deleteCustomer" variant="delete">Supprimer</BaseButton>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'
import BaseButton from '../common/Basebutton.vue';

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
