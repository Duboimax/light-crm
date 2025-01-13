<template>
  <aside class="w-full">
    <ul class="flex w-full flex-col space-y-4">
      <li
          v-for="(item, index) in AppMenu"
          :key="index"
          class="relative flex items-center"
      >
        <!-- Barre d'indicateur active -->
        <div
            v-if="isActivePath(item.path)"
            class="absolute inset-y-0 left-0 w-1 bg-primary-400 rounded-md"
        ></div>

        <!-- Lien de menu -->
        <nuxt-link
            :to="item.path"
            :class="[
            'flex items-center space-x-3 p-2 pl-4 rounded-md transition',
            isActivePath(item.path)
              ? 'text-primary-600 bg-slate-100 dark:bg-slate-800'
              : 'text-gray-700 hover:bg-slate-100 dark:text-gray-300 dark:hover:bg-slate-800'
          ]"
            :title="item.title"
        >
          <Icon :name="item.icon" class="text-xl" />
          <p>{{ item.title }}</p>
        </nuxt-link>
      </li>
    </ul>
  </aside>
</template>


<script setup lang="ts">
import { AppMenu } from '~/constants';

const isActivePath = (to?: string) => {
  const roots = to?.split('/').filter((r) => r !== '');
  const { fullPath } = useRouter().currentRoute.value;

  if (fullPath === '/' && !roots?.length) {
    return true;
  }

  const currentPath = fullPath.split('/')[1];
  return roots?.some((r) => currentPath === r);
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-active {
  opacity: 0;
}
</style>
