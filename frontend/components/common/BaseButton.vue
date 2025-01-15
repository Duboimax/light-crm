<template>
    <button
      :class="buttonClasses"
      :type="type"
    >
      <slot />
    </button>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  
  // Props
  const props = defineProps({
    variant: {
      type: String,
      default: 'primary', // Valeur par défaut
      validator: value => ['primary', 'secondary', 'delete'].includes(value), // Ajout de "action"
    },
    type: {
      type: String,
      default: 'button', // Par défaut pour éviter de soumettre sans intention
    },
  });
  
  // Computed pour gérer les classes
  const buttonClasses = computed(() => {
    const baseClasses = 'text-white px-4 py-2 rounded transition';
    const variants = {
      primary: 'bg-indigo-600 hover:bg-indigo-700',
      secondary: 'bg-slate-400 hover:bg-slate-500',
      delete: 'bg-red-500 hover:bg-red-600', // Nouvelle variante
    };
  
    return `${baseClasses} ${variants[props.variant]}`;
  });
  </script>
  