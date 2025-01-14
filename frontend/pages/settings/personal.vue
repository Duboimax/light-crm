<template>
    <div class="mx-auto h-full w-full max-w-3xl p-4">
      <n-card :content-style="{ padding: '0' }" class="h-full">
        <div class="flex h-full gap-4 divide-x divide-slate-100">
          <main class="flex-grow p-4">
            <section class="my-4">
                <NuxtLink to="/settings" class="flex items-center">
                    <Icon name="heroicons:chevron-left" />
                    <p>Settings</p>
                </NuxtLink>
            </section>
        </main>
        </div>
      </n-card>
    </div>
</template>