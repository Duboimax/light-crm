// plugins/axios.ts
import axios from 'axios'

export default defineNuxtPlugin((nuxtApp) => {
    const config = useRuntimeConfig()

    const api = axios.create({
        baseURL: config.public.apiBaseURL, // Définissez l'URL de votre API dans nuxt.config
    })

    // Ajouter un intercepteur pour inclure le token JWT dans les requêtes
    api.interceptors.request.use((config) => {
        const token = localStorage.getItem('token')
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    })

    // Ajouter l'instance Axios à l'application Nuxt
    nuxtApp.provide('axios', api)
})
