// middleware/auth.ts
import { useAuthStore } from "~/stores/auth";

export default defineNuxtRouteMiddleware(async (to, from) => {
    const authStore = useAuthStore();

    // Initialiser le store si ce n'est pas déjà fait
    if (!authStore.isAuthenticated && authStore.token) {
        await authStore.fetchUser();
    }

    // Rediriger les utilisateurs non authentifiés vers la page de connexion (/login)
    if (!authStore.isAuthenticated && to.path !== '/login' && to.path !== '/register' && to.meta.layout !== 'guest') {
        return navigateTo('/auth/login', { replace: true });
    }

    // Rediriger les utilisateurs authentifiés loin des pages de connexion et d'inscription
    if (authStore.isAuthenticated && (to.path === '/login' || to.path === '/register')) {
        return navigateTo('/', { replace: true });
    }
});
