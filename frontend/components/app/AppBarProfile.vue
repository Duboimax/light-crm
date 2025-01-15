<template>
  <div class="relative">
    <Menu
        ref="userMenu"
        :model="menuItems"
        popup
        class="shadow-md rounded-lg"
    />
    <div
        class="flex h-10 w-10 items-center justify-center cursor-pointer"
        @click="toggleMenu"
    >
      <img
          alt="User Avatar"
          :src="user?.avatarUrl || 'https://avatars.githubusercontent.com/u/18229355?v=4'"
          class="h-full w-full rounded-full object-cover"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useAuthStore } from "~/stores/auth";
import { useToast } from "primevue/usetoast";

const toast = useToast();
const userStore = useAuthStore();
const user = userStore.user;

// Utilisez un ref pour accéder au Menu
const userMenu = ref();

// Définir les éléments du menu
const menuItems = ref([
  {
    label: "Profile",
    icon: "pi pi-user",
    command: () => {
      window.location.href = "/settings/child/personal";
    },
  },
  {
    label: "Security",
    icon: "pi pi-lock",
    command: () => {
      window.location.href = "/settings/security";
    },
  },
  {
    label: "Settings",
    icon: "pi pi-cog",
    command: () => {
      window.location.href = "/settings";
    },
  },
  { separator: true },
  {
    label: "Theme",
    icon: "pi pi-moon",
    items: [
      {
        label: "Light",
        command: () => {
          toast.add({
            severity: "info",
            summary: "Theme",
            detail: "Switched to Light Theme",
            life: 3000,
          });
        },
      },
      {
        label: "Dark",
        command: () => {
          toast.add({
            severity: "info",
            summary: "Theme",
            detail: "Switched to Dark Theme",
            life: 3000,
          });
        },
      },
    ],
  },
  {
    label: "Language",
    icon: "pi pi-globe",
    items: [
      {
        label: "English",
        command: () => {
          toast.add({
            severity: "info",
            summary: "Language",
            detail: "Switched to English",
            life: 3000,
          });
        },
      },
      {
        label: "Français",
        command: () => {
          toast.add({
            severity: "info",
            summary: "Language",
            detail: "Switched to French",
            life: 3000,
          });
        },
      },
    ],
  },
  { separator: true },
  {
    label: "Sign Out",
    icon: "pi pi-sign-out",
    command: () => {
      userStore.logout();
    },
  },
]);

// Fonction pour basculer le menu
const toggleMenu = (event: Event) => {
  userMenu.value?.toggle(event);
};
</script>

<style scoped>
.relative {
  position: relative;
}
</style>
