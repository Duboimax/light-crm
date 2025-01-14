<template>
  <div class="bg-white shadow-md rounded-lg p-4">
    <h2 class="text-lg font-bold">{{ sale.customer.firstname }} {{ sale.customer.lastname }}</h2>
    <p class="text-sm text-gray-600">Service : {{ sale.service.name }}</p>
    <p class="text-sm text-gray-600">Date : {{ formatDate(sale.saleDate) }}</p>
    <p class="text-sm text-gray-600">Total : {{ sale.total }} €</p>
    <p v-if="sale.discount" class="text-sm text-gray-600">Réduction : {{ sale.discount }} €</p>
    <p class="text-sm text-gray-600">Statut : {{ SaleStatuses[sale.status] }}</p>
    <div class="flex justify-end mt-4 space-x-2">
      <button @click="$emit('delete', sale.id)" class="text-red-500 hover:underline">Annuler</button>
    </div>
  </div>
</template>

<script setup lang="ts">

import {type Sale, SaleStatuses} from "~/interfaces/SaleInterface";

const props = defineProps<{ sale: Sale }>();

const formatDate = (date: string): string => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};
</script>
