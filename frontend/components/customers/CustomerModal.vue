<!-- components/CustomerModal.vue -->
<template>
  <div v-if="isOpen" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white dark:bg-slate-800 rounded-lg w-full max-w-lg p-6">
      <h2 class="text-2xl font-bold mb-4">{{ props.isEditing ? 'Editer le client ' : 'Ajouter un Nouveau Client' }}</h2>
      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 dark:text-gray-200">Nom</label>
          <input
              type="text"
              id="name"
              v-model="name"
              required
              class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <div class="mb-4">
          <label for="email" class="block text-gray-700 dark:text-gray-200">Email</label>
          <input
              type="email"
              id="email"
              v-model="email"
              required
              class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <div class="mb-4">
          <label for="phone" class="block text-gray-700 dark:text-gray-200">Téléphone</label>
          <input
              type="text"
              id="phone"
              v-model="phone"
              required
              class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <div class="mb-4">
          <label for="address" class="block text-gray-700 dark:text-gray-200">Adresse</label>
          <textarea
              id="address"
              v-model="address"
              required
              rows="3"
              class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          ></textarea>
        </div>
        <div class="flex justify-end space-x-2">
          <button
              type="button"
              @click="close"
              class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition duration-200"
          >
            Annuler
          </button>
          <button
              type="submit"
              :disabled="isSubmitting"
              class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-200"
          >
            {{ isSubmitting ? (isEditing ? 'Modification...' : 'Ajout...') : (isEditing ? 'Modifier' : 'Ajouter') }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue'

const props = defineProps<{
  isOpen: boolean,
  isEditing: boolean
  customer?: Customer | null // Client à modifier, optionnel
}>()

const emit = defineEmits(['close', 'add', 'update'])

const name = ref('')
const email = ref('')
const phone = ref('')
const address = ref('')
const isSubmitting = ref(false)

// Pré-remplir les champs si un client est fourni
watch(
    () => props.customer,
    (newCustomer) => {
      if (newCustomer) {
        name.value = newCustomer.name
        email.value = newCustomer.email
        phone.value = newCustomer.phone
        address.value = newCustomer.address
      } else {
        // Réinitialiser les champs pour l'ajout
        name.value = ''
        email.value = ''
        phone.value = ''
        address.value = ''
      }
    },
    { immediate: true }
)

const handleSubmit = async () => {
  isSubmitting.value = true
  try {
    const newCustomer = {
      name: name.value,
      email: email.value,
      phone: phone.value,
      address: address.value,
    }

    if (props.customer) {
      // Émettre un événement de mise à jour
      emit('update', { ...props.customer, ...newCustomer })
    } else {
      // Émettre un événement d'ajout
      emit('add', newCustomer)
    }

    // Réinitialiser les champs
    name.value = ''
    email.value = ''
    phone.value = ''
    address.value = ''
    emit('close')
  } catch (error) {
    alert('Erreur lors de la soumission.')
  } finally {
    isSubmitting.value = false
  }
}

const close = () => {
  emit('close')
}
</script>
