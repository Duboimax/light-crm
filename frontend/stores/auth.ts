// stores/auth.ts
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { User } from '~/interfaces/UserInterface'

export const useAuthStore = defineStore('auth', () => {
    const nuxtApp = useNuxtApp()
    const user = ref<User | null>(null)
    const token = ref<string | null>(localStorage.getItem('token'))

    const isAuthenticated = computed(() => !!token.value)

    const login = async (email: string, password: string) => {
        try {
            const response = await nuxtApp.$axios.post('/login', { email, password })
            console.log(response)
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
            await nuxtApp.$axios.post('/register', { email, password })
            // Optionnel : Connectez l'utilisateur après l'inscription
            await login(email, password)
        } catch (error) {
            throw new Error('Échec de l\'inscription')
        }
    }

    const fetchUser = async () => {
        try {
            if (token.value) {
                const response = await nuxtApp.$axios.get('/users')
                user.value = response.data
            }
        } catch (error) {
            logout()
        }
    }

    const updateUser = (updatedUser: Partial<User>) => {
        if (user.value) {
            user.value = { ...user.value, ...updatedUser }
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
        updateUser,
        logout,
        initialize,
    }
})
