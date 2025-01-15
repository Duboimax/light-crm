// https://nuxt.com/docs/api/configuration/nuxt-config
import Aura from '@primevue/themes/aura';

export default defineNuxtConfig({
  devtools: { enabled: true },
  ssr: false,

  modules: [
    '@nuxtjs/tailwindcss',
    'nuxt-icon',
    '@nuxtjs/i18n',
    '@primevue/nuxt-module'
  ],

  runtimeConfig: {
    public: {
      apiBaseURL: 'http://localhost:8000',
    },
  },

  tailwindcss: {
    exposeConfig: true,
  },

  primevue: {
    options: {
      theme: {
        preset: Aura,
        options: {
          darkModeSelector: false || 'none',
        }
      }
    }
  },

  app: {
    pageTransition: {
      name: 'slide-left',
      mode: 'out-in',
    },
  },

  i18n: {
    locales: [
      {
        code: 'en',
        name: 'English',
        icon: 'cif:gb',
        file: 'en-US.json',
      },
      {
        code: 'lo',
        name: 'ພາສາລາວ',
        icon: 'cif:la',
        file: 'lo-LA.json',
      },
    ],
    lazy: true,
    langDir: 'locales',
    defaultLocale: 'lo',
    vueI18n: './i18n.config.ts', // if you are using custom path, default
  },


  router: {
    middleware: ['auth'],
  },

  compatibilityDate: '2025-01-12',
});