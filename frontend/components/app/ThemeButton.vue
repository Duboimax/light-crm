<template>
  <div class="flex items-center space-x-4">
    <button
        @click="toggleTheme"
        class="bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded transition"
    >
      <i :class="theme === 'dark' ? 'pi pi-moon' : 'pi pi-sun'"></i>
      <span class="ml-2">
        {{ theme === 'dark' ? 'Mode Sombre' : 'Mode Clair' }}
      </span>
    </button>
  </div>
</template>

<script setup lang="ts">
import { useThemeStore } from '~/stores/theme';

const themeStore = useThemeStore();
const theme = computed(() => themeStore.theme);

const toggleTheme = () => {
  themeStore.toggleTheme();
};
</script>
