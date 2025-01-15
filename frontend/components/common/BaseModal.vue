<template>
    <div
      v-if="modelValue"
      class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
      @click="closeOnOverlay"
    >
      <div
        class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative"
        @click.stop
      >
        <!-- Titre -->
        <h2 v-if="title" class="text-xl font-bold text-gray-800">{{ title }}</h2>
        
        <!-- Message -->
        <p v-if="message" class="mt-4 text-gray-600">{{ message }}</p>
  
        <slot />
        
        <div class="mt-6 flex justify-end space-x-4">
          <BaseButton v-if="cancelButtonText" variant="secondary" @click="handleCancel">
            {{ cancelButtonText }}
          </BaseButton>
          <BaseButton v-if="confirmButtonText" variant="primary" @click="handleConfirm">
            {{ confirmButtonText }}
          </BaseButton>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  import BaseButton from '~/components/common/BaseButton.vue';
  
  const props = defineProps({
    modelValue: Boolean, // Contrôle l'état ouvert/fermé depuis le parent
    cancelButtonText: { type: String, default: 'Annuler' },
    confirmButtonText: { type: String, default: 'Confirmer' },
    title: { type: String, default: '' }, // Titre de la modal
    message: { type: String, default: '' }, // Message de la modal
  });
  
  const emit = defineEmits(['update:modelValue', 'cancel', 'confirm']);
  
  // Fonction pour fermer la modal
  const close = () => {
    emit('update:modelValue', false);
  };
  
  // Fermer la modal si on clique sur l'overlay
  const closeOnOverlay = () => {
    close();
  };
  
  // Gérer l'annulation
  const handleCancel = () => {
    emit('cancel');
    close();
  };
  
  // Gérer la confirmation
  const handleConfirm = () => {
    emit('confirm');
    close();
  };
  </script>
  