<script setup lang="ts">
import { ref, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/api/client'

const auth   = useAuthStore()
const router = useRouter()

// ── アバター ──────────────────────────────────────────
const avatarInput    = ref<HTMLInputElement | null>(null)
const avatarPreview  = ref<string | null>(null)
const avatarLoading  = ref(false)
const avatarMsg      = ref<{ type: 'ok' | 'ng'; text: string } | null>(null)

function pickAvatar() {
  avatarInput.value?.click()
}

async function onAvatarChange(e: Event) {
  const file = (e.target as HTMLInputElement).files?.[0]
  if (!file) return

  // プレビュー表示
  avatarPreview.value = URL.createObjectURL(file)
  avatarMsg.value = null

  // 即時アップロード
  avatarLoading.value = true
  const form = new FormData()
  form.append('avatar', file)
  try {
    const { data } = await api.post('/user/avatar', form, {
      headers: { 'Content-Type': 'multipart/form-data' },
    })
    auth.user = data
    avatarMsg.value = { type: 'ok', text: 'アバターを更新しました' }
  } catch (e: any) {
    avatarMsg.value = { type: 'ng', text: e.response?.data?.message ?? 'アップロードに失敗しました' }
    avatarPreview.value = null
  } finally {
    avatarLoading.value = false
    if (avatarInput.value) avatarInput.value.value = ''
  }
}

const avatarSrc = computed(() => avatarPreview.value ?? auth.user?.avatar ?? null)
const initials  = computed(() => (auth.user?.name ?? '?').charAt(0).toUpperCase())

// ── 基本情報 ──────────────────────────────────────────
const STATUS_OPTIONS = [
  { value: '学習中',  icon: 'school',       color: '#0284c7', bg: '#e0f2fe' },
  { value: '学習完了', icon: 'emoji_events', color: '#059669', bg: '#dcfce7' },
  { value: '休止中',  icon: 'pause_circle', color: '#64748b', bg: '#f1f5f9' },
]

const profile = reactive({
  name:   auth.user?.name   ?? '',
  email:  auth.user?.email  ?? '',
  status: auth.user?.status ?? '学習中',
})
const profileLoading = ref(false)
const profileMsg     = ref<{ type: 'ok' | 'ng'; text: string } | null>(null)

async function saveProfile() {
  profileLoading.value = true
  profileMsg.value = null
  try {
    const { data } = await api.put('/user/profile', profile)
    auth.user = data
    profileMsg.value = { type: 'ok', text: '基本情報を更新しました' }
  } catch (e: any) {
    profileMsg.value = {
      type: 'ng',
      text: e.response?.data?.errors?.email?.[0] ?? e.response?.data?.message ?? '更新に失敗しました',
    }
  } finally {
    profileLoading.value = false
  }
}

const currentStatus = computed(() =>
  STATUS_OPTIONS.find(s => s.value === profile.status) ?? STATUS_OPTIONS[0]
)

// ── パスワード変更 ─────────────────────────────────────
const pw = reactive({ current_password: '', password: '', password_confirmation: '' })
const showPwCurrent = ref(false)
const showPwNew     = ref(false)
const showPwConfirm = ref(false)
const pwLoading = ref(false)
const pwMsg     = ref<{ type: 'ok' | 'ng'; text: string } | null>(null)

async function changePassword() {
  pwLoading.value = true
  pwMsg.value = null
  try {
    await api.put('/user/password', pw)
    pwMsg.value = { type: 'ok', text: 'パスワードを変更しました' }
    pw.current_password = ''
    pw.password = ''
    pw.password_confirmation = ''
  } catch (e: any) {
    pwMsg.value = { type: 'ng', text: e.response?.data?.message ?? 'パスワード変更に失敗しました' }
  } finally {
    pwLoading.value = false
  }
}

// ── 学習履歴リセット ──────────────────────────────────
const resetStep    = ref<'idle' | 'confirm'>('idle')
const resetLoading = ref(false)
const resetMsg     = ref<{ type: 'ok' | 'ng'; text: string } | null>(null)

async function handleReset() {
  if (resetStep.value === 'idle') { resetStep.value = 'confirm'; return }
  resetLoading.value = true
  resetMsg.value = null
  try {
    await api.delete('/user/history')
    resetMsg.value = { type: 'ok', text: '学習履歴をリセットしました' }
    resetStep.value = 'idle'
  } catch {
    resetMsg.value = { type: 'ng', text: 'リセットに失敗しました' }
    resetStep.value = 'idle'
  } finally {
    resetLoading.value = false
  }
}

// ── 退会 ──────────────────────────────────────────────
const del           = reactive({ password: '' })
const showDelPw     = ref(false)
const delStep       = ref<'idle' | 'confirm'>('idle')
const delLoading = ref(false)
const delMsg     = ref('')

async function handleDelete() {
  if (delStep.value === 'idle') { delStep.value = 'confirm'; return }
  delLoading.value = true
  delMsg.value = ''
  try {
    await api.delete('/user', { data: del })
    auth.user  = null
    auth.token = null
    localStorage.removeItem('token')
    router.push('/login')
  } catch (e: any) {
    delMsg.value = e.response?.data?.message ?? '退会処理に失敗しました'
    delLoading.value = false
  }
}
</script>

<template>
  <div class="mypage-wrap">

    <!-- ページタイトル -->
    <div style="margin-bottom:1.5rem;">
      <h1 class="page-title" style="margin:0 0 .2rem;font-size:1.4rem;font-weight:800;color:#0c4a6e;display:flex;align-items:center;gap:.5rem;">
        <span class="material-icons" style="font-size:1.4rem;color:#0284c7;">person</span>
        マイページ
      </h1>
      <p style="margin:0;color:#64748b;font-size:.85rem;">プロフィールやアカウント設定を管理できます</p>
    </div>

    <!-- ── アバター ── -->
    <div class="card section-card">
      <div class="section-header">
        <div class="section-icon" style="background:linear-gradient(135deg,#818cf8,#4f46e5);">
          <span class="material-icons" style="font-size:1.2rem;color:#fff;">face</span>
        </div>
        <div>
          <div class="section-title">アバター画像</div>
          <div class="section-sub">JPG・PNG・GIF・WebP（最大 2 MB）</div>
        </div>
      </div>

      <!-- 画像 + アップロードボタン -->
      <div style="display:flex;flex-direction:column;align-items:center;gap:1rem;">
        <div class="avatar-wrap" @click="pickAvatar" title="クリックして画像を変更">
          <img v-if="avatarSrc" :src="avatarSrc" alt="アバター" class="avatar-img" />
          <div v-else class="avatar-initials">{{ initials }}</div>
          <div class="avatar-overlay">
            <span class="material-icons" style="font-size:1.4rem;color:#fff;">photo_camera</span>
          </div>
          <div v-if="avatarLoading" class="avatar-loading">
            <span class="material-icons spinning" style="font-size:1.6rem;color:#fff;">refresh</span>
          </div>
        </div>

        <input
          ref="avatarInput"
          type="file"
          accept="image/jpeg,image/png,image/gif,image/webp"
          style="display:none;"
          @change="onAvatarChange"
        />

        <button class="btn-secondary" style="font-size:.82rem;padding:.45rem 1rem;display:inline-flex;align-items:center;gap:.35rem;" @click="pickAvatar">
          <span class="material-icons" style="font-size:1rem;">upload</span>
          画像を選択してアップロード
        </button>
      </div>

      <div v-if="avatarMsg" :class="['msg-box', avatarMsg.type === 'ok' ? 'msg-ok' : 'msg-ng']" style="margin-top:1rem;">
        <span class="material-icons" style="font-size:1rem;">{{ avatarMsg.type === 'ok' ? 'check_circle' : 'error' }}</span>
        {{ avatarMsg.text }}
      </div>
    </div>

    <!-- ── 基本情報 ── -->
    <div class="card section-card">
      <div class="section-header">
        <div class="section-icon" style="background:linear-gradient(135deg,#38bdf8,#0284c7);">
          <span class="material-icons" style="font-size:1.2rem;color:#fff;">account_circle</span>
        </div>
        <div>
          <div class="section-title">基本情報</div>
          <div class="section-sub">名前・メールアドレス・ステータスの変更</div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">名前</label>
        <input v-model="profile.name" type="text" class="input" placeholder="名前を入力" />
      </div>
      <div class="form-group">
        <label class="form-label">メールアドレス</label>
        <input v-model="profile.email" type="email" class="input" placeholder="email@example.com" />
      </div>

      <!-- ステータス選択 -->
      <div class="form-group">
        <label class="form-label">ステータス</label>
        <div class="status-grid">
          <button
            v-for="opt in STATUS_OPTIONS"
            :key="opt.value"
            class="status-btn"
            :class="{ 'status-btn--active': profile.status === opt.value }"
            :style="profile.status === opt.value ? `border-color:${opt.color};background:${opt.bg};` : ''"
            @click="profile.status = opt.value"
          >
            <span class="material-icons" :style="`font-size:1.2rem;color:${profile.status === opt.value ? opt.color : '#94a3b8'};`">
              {{ opt.icon }}
            </span>
            <span :style="`font-size:.8rem;font-weight:700;color:${profile.status === opt.value ? opt.color : '#64748b'};`">
              {{ opt.value }}
            </span>
          </button>
        </div>
      </div>

      <div v-if="profileMsg" :class="['msg-box', profileMsg.type === 'ok' ? 'msg-ok' : 'msg-ng']">
        <span class="material-icons" style="font-size:1rem;">{{ profileMsg.type === 'ok' ? 'check_circle' : 'error' }}</span>
        {{ profileMsg.text }}
      </div>

      <button class="btn-primary save-btn" :disabled="profileLoading" @click="saveProfile">
        <span v-if="profileLoading" class="material-icons spinning" style="font-size:1rem;">refresh</span>
        <span v-else class="material-icons" style="font-size:1rem;">save</span>
        {{ profileLoading ? '保存中...' : '変更を保存' }}
      </button>
    </div>

    <!-- ── パスワード変更 ── -->
    <div class="card section-card">
      <div class="section-header">
        <div class="section-icon" style="background:linear-gradient(135deg,#34d399,#059669);">
          <span class="material-icons" style="font-size:1.2rem;color:#fff;">lock</span>
        </div>
        <div>
          <div class="section-title">パスワード変更</div>
          <div class="section-sub">新しいパスワードを設定します</div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">現在のパスワード</label>
        <div style="position:relative;">
          <input v-model="pw.current_password" :type="showPwCurrent ? 'text' : 'password'" class="input" placeholder="現在のパスワード" autocomplete="current-password" style="padding-right:2.75rem;" />
          <button type="button" @click="showPwCurrent = !showPwCurrent" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;" :title="showPwCurrent ? 'パスワードを隠す' : 'パスワードを表示'">
            <span class="material-icons" style="font-size:1.2rem;">{{ showPwCurrent ? 'visibility_off' : 'visibility' }}</span>
          </button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">新しいパスワード（8文字以上）</label>
        <div style="position:relative;">
          <input v-model="pw.password" :type="showPwNew ? 'text' : 'password'" class="input" placeholder="新しいパスワード" autocomplete="new-password" style="padding-right:2.75rem;" />
          <button type="button" @click="showPwNew = !showPwNew" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;" :title="showPwNew ? 'パスワードを隠す' : 'パスワードを表示'">
            <span class="material-icons" style="font-size:1.2rem;">{{ showPwNew ? 'visibility_off' : 'visibility' }}</span>
          </button>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">新しいパスワード（確認）</label>
        <div style="position:relative;">
          <input v-model="pw.password_confirmation" :type="showPwConfirm ? 'text' : 'password'" class="input" placeholder="もう一度入力" autocomplete="new-password" style="padding-right:2.75rem;" />
          <button type="button" @click="showPwConfirm = !showPwConfirm" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;" :title="showPwConfirm ? 'パスワードを隠す' : 'パスワードを表示'">
            <span class="material-icons" style="font-size:1.2rem;">{{ showPwConfirm ? 'visibility_off' : 'visibility' }}</span>
          </button>
        </div>
      </div>

      <div v-if="pwMsg" :class="['msg-box', pwMsg.type === 'ok' ? 'msg-ok' : 'msg-ng']">
        <span class="material-icons" style="font-size:1rem;">{{ pwMsg.type === 'ok' ? 'check_circle' : 'error' }}</span>
        {{ pwMsg.text }}
      </div>

      <button class="btn-primary save-btn" :disabled="pwLoading" @click="changePassword">
        <span v-if="pwLoading" class="material-icons spinning" style="font-size:1rem;">refresh</span>
        <span v-else class="material-icons" style="font-size:1rem;">lock_reset</span>
        {{ pwLoading ? '変更中...' : 'パスワードを変更' }}
      </button>
    </div>

    <!-- ── 学習履歴リセット ── -->
    <div class="card section-card danger-card">
      <div class="section-header">
        <div class="section-icon" style="background:linear-gradient(135deg,#fb923c,#ea580c);">
          <span class="material-icons" style="font-size:1.2rem;color:#fff;">restart_alt</span>
        </div>
        <div>
          <div class="section-title">学習履歴のリセット</div>
          <div class="section-sub">クイズ結果・正答率がすべて削除されます</div>
        </div>
      </div>

      <div class="danger-notice">
        <span class="material-icons" style="font-size:1rem;color:#ea580c;flex-shrink:0;">warning</span>
        <span>この操作は取り消せません。学習記録がすべて削除されます。</span>
      </div>

      <div v-if="resetMsg" :class="['msg-box', resetMsg.type === 'ok' ? 'msg-ok' : 'msg-ng']">
        <span class="material-icons" style="font-size:1rem;">{{ resetMsg.type === 'ok' ? 'check_circle' : 'error' }}</span>
        {{ resetMsg.text }}
      </div>

      <div v-if="resetStep === 'confirm'" class="confirm-box">
        <p style="margin:0 0 .75rem;font-size:.875rem;font-weight:600;color:#92400e;">本当にリセットしますか？</p>
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
          <button class="btn-danger" :disabled="resetLoading" @click="handleReset">
            <span v-if="resetLoading" class="material-icons spinning" style="font-size:1rem;">refresh</span>
            <span v-else class="material-icons" style="font-size:1rem;">delete_forever</span>
            {{ resetLoading ? '処理中...' : 'はい、リセットする' }}
          </button>
          <button class="btn-secondary" @click="resetStep = 'idle'">キャンセル</button>
        </div>
      </div>
      <button v-else class="btn-danger-outline" @click="handleReset">
        <span class="material-icons" style="font-size:1rem;">restart_alt</span>
        学習履歴をリセット
      </button>
    </div>

    <!-- ── 退会 ── -->
    <div class="card section-card danger-card">
      <div class="section-header">
        <div class="section-icon" style="background:linear-gradient(135deg,#f87171,#dc2626);">
          <span class="material-icons" style="font-size:1.2rem;color:#fff;">person_remove</span>
        </div>
        <div>
          <div class="section-title">退会</div>
          <div class="section-sub">アカウントとすべてのデータを削除します</div>
        </div>
      </div>

      <div class="danger-notice">
        <span class="material-icons" style="font-size:1rem;color:#dc2626;flex-shrink:0;">warning</span>
        <span>退会するとアカウント・学習記録・お気に入りがすべて削除されます。この操作は取り消せません。</span>
      </div>

      <div v-if="delStep === 'confirm'" class="confirm-box">
        <p style="margin:0 0 .75rem;font-size:.875rem;font-weight:600;color:#7f1d1d;">退会するにはパスワードを入力してください</p>
        <div style="position:relative;margin-bottom:.75rem;">
          <input v-model="del.password" :type="showDelPw ? 'text' : 'password'" class="input" placeholder="パスワード" autocomplete="current-password" style="padding-right:2.75rem;" />
          <button type="button" @click="showDelPw = !showDelPw" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0;display:flex;align-items:center;" :title="showDelPw ? 'パスワードを隠す' : 'パスワードを表示'">
            <span class="material-icons" style="font-size:1.2rem;">{{ showDelPw ? 'visibility_off' : 'visibility' }}</span>
          </button>
        </div>
        <div v-if="delMsg" class="msg-box msg-ng" style="margin-bottom:.75rem;">
          <span class="material-icons" style="font-size:1rem;">error</span>{{ delMsg }}
        </div>
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
          <button class="btn-danger" :disabled="delLoading" @click="handleDelete">
            <span v-if="delLoading" class="material-icons spinning" style="font-size:1rem;">refresh</span>
            <span v-else class="material-icons" style="font-size:1rem;">person_remove</span>
            {{ delLoading ? '処理中...' : '退会する' }}
          </button>
          <button class="btn-secondary" @click="delStep = 'idle'; del.password = ''; delMsg = ''">キャンセル</button>
        </div>
      </div>
      <button v-else class="btn-danger-outline" style="border-color:#fca5a5;color:#dc2626;" @click="handleDelete">
        <span class="material-icons" style="font-size:1rem;">person_remove</span>
        退会する
      </button>
    </div>

  </div>
</template>

<style scoped>
.mypage-wrap {
  max-width: 600px;
}

.section-card {
  margin-bottom: 1.25rem;
}

.section-header {
  display: flex;
  align-items: center;
  gap: .875rem;
  margin-bottom: 1.25rem;
}

.section-icon {
  width: 40px;
  height: 40px;
  border-radius: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.section-title {
  font-weight: 700;
  font-size: .95rem;
  color: #0c4a6e;
}

.section-sub {
  font-size: .78rem;
  color: #64748b;
  margin-top: .1rem;
}

/* ── アバター ── */
.avatar-wrap {
  position: relative;
  width: 88px;
  height: 88px;
  border-radius: 50%;
  cursor: pointer;
  flex-shrink: 0;
}

.avatar-img {
  width: 88px;
  height: 88px;
  border-radius: 50%;
  object-fit: cover;
  display: block;
  border: 3px solid #e0f2fe;
}

.avatar-initials {
  width: 88px;
  height: 88px;
  border-radius: 50%;
  background: linear-gradient(135deg, #38bdf8, #0284c7);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-weight: 800;
  font-size: 2rem;
  border: 3px solid #e0f2fe;
}

.avatar-overlay {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  background: rgba(0, 0, 0, .35);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity .2s;
}
.avatar-wrap:hover .avatar-overlay {
  opacity: 1;
}

.avatar-loading {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  background: rgba(0, 0, 0, .5);
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ── ステータス選択 ── */
.status-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: .625rem;
}

.status-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: .3rem;
  padding: .75rem .5rem;
  border-radius: .875rem;
  border: 2px solid #e0f2fe;
  background: #fff;
  cursor: pointer;
  transition: border-color .15s, background .15s, transform .1s;
}
.status-btn:hover        { border-color: #bae6fd; }
.status-btn--active      { transform: scale(1.03); }
.status-btn:active       { transform: scale(.97); }

/* ── フォーム ── */
.form-group {
  margin-bottom: .875rem;
}

.form-label {
  display: block;
  font-size: .82rem;
  font-weight: 600;
  color: #475569;
  margin-bottom: .35rem;
}

.save-btn {
  width: 100%;
  justify-content: center;
  margin-top: .25rem;
}

/* ── メッセージ ── */
.msg-box {
  display: flex;
  align-items: center;
  gap: .4rem;
  padding: .6rem .875rem;
  border-radius: .625rem;
  font-size: .82rem;
  font-weight: 600;
  margin-bottom: .875rem;
}

.msg-ok { background: #dcfce7; color: #15803d; }
.msg-ng { background: #fef2f2; color: #dc2626; }

/* ── 危険ゾーン ── */
.danger-card { border-color: #fecaca; }

.danger-notice {
  display: flex;
  align-items: flex-start;
  gap: .5rem;
  padding: .625rem .875rem;
  background: #fff7ed;
  border-radius: .625rem;
  font-size: .8rem;
  color: #92400e;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.confirm-box {
  background: #fff7ed;
  border: 1.5px solid #fde68a;
  border-radius: .875rem;
  padding: 1rem;
}

.btn-danger {
  background: #dc2626;
  color: #fff;
  font-weight: 600;
  padding: 0.575rem 1.1rem;
  border-radius: 0.75rem;
  border: none;
  cursor: pointer;
  transition: background .2s, transform .1s;
  outline: none;
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  font-size: .875rem;
}
.btn-danger:hover    { background: #b91c1c; }
.btn-danger:active   { transform: scale(.97); }
.btn-danger:disabled { opacity: .6; cursor: not-allowed; }

.btn-danger-outline {
  background: #fff;
  color: #ea580c;
  font-weight: 600;
  padding: 0.575rem 1.1rem;
  border-radius: 0.75rem;
  border: 1.5px solid #fed7aa;
  cursor: pointer;
  transition: background .2s, transform .1s;
  outline: none;
  display: inline-flex;
  align-items: center;
  gap: .4rem;
  font-size: .875rem;
}
.btn-danger-outline:hover  { background: #fff7ed; }
.btn-danger-outline:active { transform: scale(.97); }

.spinning {
  animation: spin 1s linear infinite;
}
</style>
