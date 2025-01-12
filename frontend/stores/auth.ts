// stores/auth.ts
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

interface User {
    id: string
    email: string
    roles: string[]
    // Ajoutez d'autres propriétés si nécessaire
}

export const useAuthStore = defineStore('auth', () => {
    const nuxtApp = useNuxtApp()

    const user = ref<User | null>(null)
    const token = ref<string | null>(null)

    const isAuthenticated = computed(() => !!token.value)

    const login = async (email: string, password: string) => {
        try {
            const response = await nuxtApp.$axios.post('/login', { email, password })
            token.value = response.data.token
            localStorage.setItem('token', <string>token.value)
            // Charger l'utilisateur après connexion
            await fetchUser()
        } catch (error) {
            throw new Error('Échec de la connexion')
        }
    }

    const register = async (email: string, password: string) => {
        try {
            await nuxtApp.$axios.post('/users', { email, password })
            // Optionnel : Connectez l'utilisateur après l'inscription
            await login(email, password)
        } catch (error) {
            throw new Error('Échec de l\'inscription')
        }
    }

    const fetchUser = async () => {
        try {
            if (token.value) {
                const response = await nuxtApp.$axios.get('/users/me')
                user.value = response.data
            }
        } catch (error) {
            logout()
        }
    }

    const logout = () => {
        token.value = null
        user.value = null
        localStorage.removeItem('token')
    }

    const initialize = () => {
        const storedToken = localStorage.getItem('token')
        if (storedToken) {
            token.value = storedToken
            fetchUser()
        }
    }

    return {
        user,
        token,
        isAuthenticated,
        login,
        register,
        fetchUser,
        logout,
        initialize,
    }
})
