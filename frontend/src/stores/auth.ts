import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/api/client'

export interface User {
  id: number
  name: string
  email: string
  avatar?: string | null
  status?: string
}

export const useAuthStore = defineStore('auth', () => {
  const user  = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('token'))

  const isLoggedIn = () => !!token.value

  async function login(email: string, password: string) {
    const { data } = await api.post('/auth/login', { email, password })
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('token', data.token)
  }

  async function register(name: string, email: string, password: string, password_confirmation: string) {
    const { data } = await api.post('/auth/register', { name, email, password, password_confirmation })
    token.value = data.token
    user.value  = data.user
    localStorage.setItem('token', data.token)
  }

  async function logout() {
    try { await api.post('/auth/logout') } catch {}
    token.value = null
    user.value  = null
    localStorage.removeItem('token')
  }

  async function fetchMe() {
    if (!token.value) return
    try {
      const { data } = await api.get('/auth/me')
      user.value = data
    } catch {
      token.value = null
      localStorage.removeItem('token')
    }
  }

  return { user, token, isLoggedIn, login, register, logout, fetchMe }
})
