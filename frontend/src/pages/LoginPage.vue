<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth    = useAuthStore()
const router  = useRouter()
const email   = ref('demo@example.com')
const password= ref('password')
const error   = ref('')
const loading = ref(false)

async function submit() {
  error.value   = ''
  loading.value = true
  try {
    await auth.login(email.value, password.value)
    router.push('/dashboard')
  } catch (e: any) {
    const errs = e.response?.data?.errors
    if (errs) {
      error.value = Object.values(errs).flat().join(' ')
    } else {
      error.value = e.response?.data?.message ?? 'サーバーに接続できません。サーバーが起動しているか確認してください。'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div style="min-height:100vh;background:linear-gradient(135deg,#f0f9ff 0%,#e0f2fe 50%,#bae6fd 100%);display:flex;align-items:center;justify-content:center;padding:1rem;">

    <div style="position:fixed;top:-80px;right:-80px;width:300px;height:300px;background:rgba(14,165,233,.08);border-radius:50%;pointer-events:none;"></div>
    <div style="position:fixed;bottom:-60px;left:-60px;width:200px;height:200px;background:rgba(56,189,248,.1);border-radius:50%;pointer-events:none;"></div>

    <div style="width:100%;max-width:420px;">

      <!-- Logo -->
      <div style="text-align:center;margin-bottom:2rem;">
        <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;background:linear-gradient(135deg,#38bdf8,#0284c7);border-radius:18px;margin-bottom:1rem;box-shadow:0 8px 24px rgba(14,165,233,.3);">
          <span class="material-icons" style="font-size:2rem;color:#fff;">school</span>
        </div>
        <h1 style="margin:0;font-size:1.6rem;font-weight:800;color:#0c4a6e;">IT Learning Hub</h1>
        <p style="margin:.5rem 0 0;color:#7dd3fc;font-size:.9rem;">IT用語を楽しく、効率的に学ぼう</p>
      </div>

      <div class="card" style="padding:2rem;">
        <h2 style="margin:0 0 1.5rem;font-size:1.2rem;font-weight:700;color:#0c4a6e;text-align:center;">ログイン</h2>

        <div v-if="error" style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.75rem 1rem;border-radius:.75rem;margin-bottom:1rem;font-size:.875rem;">
          {{ error }}
        </div>

        <form @submit.prevent="submit" style="display:flex;flex-direction:column;gap:1rem;">
          <div>
            <label style="display:block;font-size:.85rem;font-weight:600;color:#334155;margin-bottom:.4rem;">メールアドレス</label>
            <input v-model="email" type="email" class="input" placeholder="demo@example.com" required />
          </div>
          <div>
            <label style="display:block;font-size:.85rem;font-weight:600;color:#334155;margin-bottom:.4rem;">パスワード</label>
            <input v-model="password" type="password" class="input" placeholder="••••••••" required />
          </div>
          <button type="submit" class="btn-primary" style="width:100%;justify-content:center;margin-top:.5rem;" :disabled="loading">
            <span v-if="loading" class="material-icons" style="font-size:1.1rem;animation:spin 1s linear infinite;">hourglass_empty</span>
            <span v-if="loading">ログイン中...</span>
            <span v-else>ログイン</span>
          </button>
        </form>

        <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid #e0f2fe;">
          <p style="font-size:.875rem;color:#64748b;">
            アカウントをお持ちでない方は
            <RouterLink to="/register" style="color:#0284c7;font-weight:600;text-decoration:none;">新規登録</RouterLink>
          </p>
        </div>

        <div style="margin-top:1rem;padding:.75rem;background:#f0f9ff;border-radius:.75rem;font-size:.8rem;color:#64748b;text-align:center;">
          デモ: demo@example.com / password
        </div>
      </div>
    </div>
  </div>
</template>
