<template>
  <div
    class="flex h-full w-12 cursor-pointer items-center justify-center rounded-xl p-2 transition-all duration-200 hover:bg-gray-200 dark:hover:bg-gray-800"
  >
    <Badge :value="10" :max="15" class="p-badge-primary">
      <Icon name="system-uicons:bell" size="24" />
    </Badge>
  </div>
</template>

<script setup lang="ts"></script>
