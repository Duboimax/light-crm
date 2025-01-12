// middleware/auth.ts
import {useAuthStore} from "~/stores/auth";

export default defineNuxtRouteMiddleware(async (to, from) => {
    const authStore = useAuthStore()

    // Initialiser le store si ce n'est pas déjà fait
    if (!authStore.isAuthenticated && authStore.token) {
        await authStore.fetchUser()
    }

    // Rediriger les utilisateurs non authentifiés vers la page de connexion
    if (!authStore.isAuthenticated && to.path !== 'auth/login' && to.path !== 'auth/register') {
        return navigateTo('auth/login')
    }

    // Rediriger les utilisateurs authentifiés loin des pages de connexion et d'inscription
    if (authStore.isAuthenticated && (to.path === 'auth/login' || to.path === 'auth/register')) {
        return navigateTo('/')
    }
})
