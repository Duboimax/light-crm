<template>
  <tr class="bg-white dark:bg-slate-800">
    <td class="py-2 px-4 border-b border-gray-200 dark:border-slate-700">
      {{ customer.name }}
    </td>
    <td class="py-2 px-4 border-b border-gray-200 dark:border-slate-700">
      {{ customer.email }}
    </td>
    <td class="py-2 px-4 border-b border-gray-200 dark:border-slate-700">
      {{ customer.phone }}
    </td>
    <td class="py-2 px-4 border-b border-gray-200 dark:border-slate-700">
      <div class="whitespace-pre-wrap">
        {{ customer.address }}
      </div>
    </td>
    <td class="py-2 px-4 border-b border-gray-200 dark:border-slate-700">
      {{ formatDate(customer.createdAt) }}
    </td>
    <td class="py-2 px-4 border-b border-gray-200 dark:border-slate-700 text-right relative">
      <!-- Bouton pour afficher le menu -->
      <button
          @click="toggleDropdown"
          class="p-2 rounded hover:bg-gray-100 dark:hover:bg-slate-700"
      >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6h.01M12 12h.01M12 18h.01" />
        </svg>
      </button>

      <!-- Menu déroulant -->
      <div
          v-if="isDropdownOpen"
          class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded shadow-md z-50"
      >
        <ul>
          <li>
            <button
                @click="editCustomer"
                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-600"
            >
              Modifier
            </button>
          </li>
          <li>
            <button
                @click="viewHistory"
                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-600"
            >
              Historique
            </button>
          </li>
          <li>
            <button
                @click="sendEmail"
                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-600"
            >
              Envoyer un mail
            </button>
          </li>
          <li>
            <button
                @click="confirmDelete"
                class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-slate-600"
            >
              Supprimer
            </button>
          </li>
        </ul>
      </div>
    </td>
  </tr>
</template>

<script setup lang="ts">
import { defineProps, defineEmits, ref } from 'vue'

const props = defineProps<{
  customer: Customer
}>()

const emit = defineEmits(['delete', 'edit', 'history', 'email'])

const isDropdownOpen = ref(false)

// Format date helper
const formatDate = (dateString: string): string => {
  const options: Intl.DateTimeFormatOptions = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  }
  return new Date(dateString).toLocaleDateString(undefined, options)
}

// Dropdown toggle
const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

// Actions
const editCustomer = () => {
  emit('edit', props.customer)
  isDropdownOpen.value = false
}

const viewHistory = () => {
  emit('history', props.customer)
  isDropdownOpen.value = false
}

const sendEmail = () => {
  emit('email', props.customer)
  isDropdownOpen.value = false
}

const confirmDelete = () => {
  if (confirm(`Êtes-vous sûr de vouloir supprimer le client "${props.customer.name}" ?`)) {
    emit('delete', props.customer.id)
    isDropdownOpen.value = false
  }
}
</script>

<style scoped>
/* Ajoutez des styles pour le dropdown si nécessaire */
</style>
