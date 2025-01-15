<!-- components/CustomerModal.vue -->
<template>
  <div v-if="isOpen" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white dark:bg-slate-800 rounded-lg w-full max-w-lg p-6">
      <h2 class="text-2xl font-bold mb-4">{{ isEditing ? 'Editer le client' : 'Ajouter un Nouveau Client' }}</h2>
      <form @submit.prevent="handleSubmit">
        <!-- Prénom -->
        <div class="mb-4">
          <label for="firstname" class="block text-gray-700 dark:text-gray-200">Prénom</label>
          <input
              type="text"
              id="firstname"
              v-model="firstname"
              required
              class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <!-- Nom -->
        <div class="mb-4">
          <label for="lastname" class="block text-gray-700 dark:text-gray-200">Nom</label>
          <input
              type="text"
              id="lastname"
              v-model="lastname"
              required
              class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <!-- Email -->
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
        <!-- Téléphone -->
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
        <!-- Adresses -->
        <div class="mb-4 border-t pt-4">
          <h3 class="font-semibold text-lg mb-2">Adresse </h3>
          <div class="mb-2">
            <label for="street" class="block text-gray-700 dark:text-gray-200">Rue</label>
            <input
                type="text"
                v-model="addresses.street"
                required
                class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div class="mb-2">
            <label for="postalCode" class="block text-gray-700 dark:text-gray-200">Code Postal</label>
            <input
                type="text"
                v-model="addresses.postalCode"
                required
                class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div class="mb-2">
            <label for="city" class="block text-gray-700 dark:text-gray-200">Ville</label>
            <input
                type="text"
                v-model="addresses.city"
                required
                class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div class="mb-2">
            <label for="country" class="block text-gray-700 dark:text-gray-200">Pays</label>
            <input
                type="text"
                v-model="addresses.country"
                required
                class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <div class="mb-2">
            <label for="country" class="block text-gray-700 dark:text-gray-200">Etat</label>
            <input
                type="text"
                v-model="addresses.state"
                required
                class="mt-1 block w-full px-3 py-2 bg-gray-50 dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
        </div>
        <div class="flex justify-end space-x-2 mt-6">
          <BaseButton @click="close" variant="secondary">Annuler</BaseButton>
          <BaseButton variant="primary" type="submit" :disabled="isSubmitting">{{ isSubmitting ? (isEditing ? 'Modification...' : 'Ajout...') : (isEditing ? 'Modifier' : 'Ajouter') }}</BaseButton>  
        </div>
      </form>
    </div>
  </div>
</template>


<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue'
import BaseButton from '~/components/common/BaseButton.vue';

const props = defineProps<{
  isOpen: boolean,
  isEditing: boolean,
  customer?: Customer | null
}>()

const emit = defineEmits(['close', 'add', 'update'])

const firstname = ref('')
const lastname = ref('')
const email = ref('')
const phone = ref('')
const addresses = ref<Address>({ street: '', postalCode: '', city: '', country: '', state: '' })
const isSubmitting = ref(false)

// Pré-remplir les champs si un client est fourni
watch(
    () => props.customer,
    (newCustomer) => {
      if (newCustomer) {
        firstname.value = newCustomer.firstname
        lastname.value = newCustomer.lastname
        email.value = newCustomer.email
        phone.value = newCustomer.phone
        addresses.value = newCustomer.address
            ? newCustomer.address
            : { street: '', postalCode: '', city: '', country: '', state: '' }
      } else {
        firstname.value = ''
        lastname.value = ''
        email.value = ''
        phone.value = ''
        addresses.value = { street: '', postalCode: '', city: '', country: '', state: '' }
      }
    },
    { immediate: true }
)


const handleSubmit = async () => {
  isSubmitting.value = true
  try {
    const newCustomer: Customer = {
      firstname: firstname.value,
      lastname: lastname.value,
      email: email.value,
      phone: phone.value,
      address: addresses.value,
      id: props.customer?.id || '',
    }

    if (props.customer) {
      emit('update', newCustomer)
    } else {
      emit('add', newCustomer)
    }

    resetForm()
    emit('close')
  } catch (error) {
    alert('Erreur lors de la soumission.')
  } finally {
    isSubmitting.value = false
  }
}

const resetForm = () => {
  firstname.value = ''
  lastname.value = ''
  email.value = ''
  phone.value = ''
  addresses.value = { street: '', postalCode: '', city: '', country: '', state: '' }
}

const close = () => {
  emit('close')
}

</script>
