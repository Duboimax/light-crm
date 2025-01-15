<template>
  <p class="text-lg font-semibold mb-4">Informations personnelles</p>
  <!-- Formulaire -->
  <section class="p-5 rounded-md bg-slate-50 mb-8">
    <form @submit.prevent="handleUpdateUser(props.user)">
      <!-- Nom et Prénom -->
      <div class="mb-4">
        <label for="firstname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prénom</label>
        <input
          type="text"
          id="firstname"
          v-model="props.user.firstname"
          required
          placeholder="Votre prénom"
          class="input p-1 cursor-pointer bg-transparent"
        />
      </div>
      <div class="mb-4">
        <label for="lastname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
        <input
          type="text"
          id="lastname"
          v-model="props.user.lastname"
          placeholder="Votre nom"
          class="input p-1 cursor-pointer bg-transparent"
        />
      </div>
      <!-- Email -->
      <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
        <input
          type="email"
          id="email"
          v-model="props.user.email"
          placeholder="votre@email.com"
          class="input p-1 cursor-pointer bg-transparent"
        />
      </div>
      <!-- Boutons -->
      <div class="flex justify-end space-x-3 mt-6">
        <BaseButton variant="primary" type="submit">Enregistrer</BaseButton>
      </div>
    </form>
  </section>

  <section class="my-4 flex justify-between p-5 rounded-md bg-slate-50 items-center">
    <p class="text-md font-semibold">Mot de passe</p>
    <BaseButton @click="showModal = true" variant="secondary">Réinitialiser</BaseButton>
    <!-- Modal -->
    <BaseModal
      v-model="showModal"
      :cancelButtonText="'Annuler'"
      :confirmButtonText="'Confirmer'"
      :title="'Réinitialiser le mot de passe'"
      :message="'Vous allez recevoir un email pour réinitialiser votre mot de passe.'"
      @cancel="closeModal"
      @confirm="confirmReset"
    >
    </BaseModal>
  </section>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits, watch } from 'vue';
import type { User } from '~/interfaces/UserInterface';
import { useAuthStore } from '~/stores/auth';
import BaseButton from '~/components/common/BaseButton.vue';
import BaseModal from '~/components/common/BaseModal.vue';

const showModal = ref(false);

const confirmReset = () => {
  console.log('Réinitialisation confirmée !');
  closeModal();
};

const closeModal = () => {
  showModal.value = false;
};

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
