<template>
    <p class="text-lg font-semibold mb-4">Informations personnelles</p>
    <!-- Formulaire -->
  <section class="shadow-md p-5 rounded-md">
    <form @submit.prevent="handleUpdateUser(props.user)" >
        <!-- Nom et Prénom -->
          <div class="mb-4">
            <label for="firstname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prénom</label>
            <input
              type="text"
              id="firstname"
              v-model="props.user.firstname"
              required
              placeholder="Votre prénom"
              class="input p-1 cursor-pointer"
            />
          </div>
          <div class="mb-4">
            <label for="lastname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
            <input
              type="text"
              id="lastname"
              v-model="props.user.lastname"
              required
              placeholder="Votre nom"
              class="input p-1 cursor-pointer"
            />
          </div>
        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
          <input
            type="email"
            id="email"
            v-model="props.user.email"
            required
            placeholder="votre@email.com"
            class="input p-1 cursor-pointer"
          />
        </div>
        <!-- Boutons -->
        <div class="flex justify-end space-x-3 mt-6">
          <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
            Enregistrer
          </button>
        </div>
      </form>
  </section>
  <section class="my-4">
    <p class="text-lg font-semibold">Mot de passe</p>
      <button>
        Réinitialiser
      </button>
  </section>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue';
import type { User } from '~/interfaces/UserInterface';
import { useAuthStore } from '~/stores/auth';

const authStore = useAuthStore();

const props = defineProps<{ 
  user: User | null;
}>();

const handleUpdateUser = async (updatedUser: User) => {
  try {
    const id = updatedUser.id;
    const payload = {
      email: updatedUser.email,
      firstname: updatedUser.firstname,
      lastname: updatedUser.lastname
    };

    const response = await useNuxtApp().$axios.patch(`/users/${id}`, payload);
    authStore.updateUser(response.data);
    alert('Utilisateur mis à jour avec succès.');
  } catch (err: any) {
    console.error('Erreur API :', err);
    const message = err.response?.data?.message || 'Erreur inconnue';
    alert(message);
  }
};

</script>

