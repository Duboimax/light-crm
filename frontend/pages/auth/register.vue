<!-- pages/register.vue -->
<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const error = ref<string | null>(null)

const handleSubmit = async () => {
  if (password.value !== confirmPassword.value) {
    error.value = 'Les mots de passe ne correspondent pas.'
    return
  }

  try {
    await authStore.register(email.value, password.value)
    router.push('/')
  } catch (err: any) {
    error.value = err.message
  }
}

// Spécifie que cette page utilise le layout 'guest'
definePageMeta({
  layout: 'guest',
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold mb-4">Inscription</h2>
    <form @submit.prevent="handleSubmit">
      <div class="mb-4">
        <label for="email" class="block text-gray-700">Email</label>
        <input
            type="email"
            id="email"
            v-model="email"
            required
            class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>
      <div class="mb-4">
        <label for="password" class="block text-gray-700">Mot de passe</label>
        <input
            type="password"
            id="password"
            v-model="password"
            required
            class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>
      <div class="mb-4">
        <label for="confirmPassword" class="block text-gray-700">Confirmer le mot de passe</label>
        <input
            type="password"
            id="confirmPassword"
            v-model="confirmPassword"
            required
            class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>
      <div v-if="error" class="mb-4 text-red-500">
        {{ error }}
      </div>
      <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700">
        S'inscrire
      </button>
    </form>
  </div>
</template>
