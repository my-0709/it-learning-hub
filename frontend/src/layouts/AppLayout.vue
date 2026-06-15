<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useRouter, useRoute } from 'vue-router'
import { onMounted } from 'vue'

const auth   = useAuthStore()
const router = useRouter()
const route  = useRoute()

onMounted(() => auth.fetchMe())

async function handleLogout() {
  await auth.logout()
  router.push('/login')
}

const nav = [
  { to: '/dashboard', icon: 'dashboard',  label: 'ホーム' },
  { to: '/terms',     icon: 'menu_book',  label: '単語帳' },
  { to: '/quiz',      icon: 'quiz',       label: 'クイズ' },
  { to: '/history',   icon: 'bar_chart',  label: '履歴' },
  { to: '/weak',      icon: 'lightbulb',  label: '苦手' },
  { to: '/favorites', icon: 'star',       label: 'お気に入り' },
]

const sidebarExtra = [
  { to: '/mypage', icon: 'person', label: 'マイページ' },
]
</script>

<template>
  <div class="layout-wrap">

    <!-- ══ サイドバー（PC専用） ══ -->
    <aside class="layout-sidebar">
      <!-- Logo -->
      <div style="padding:1.75rem 1.5rem;border-bottom:2px solid #e0f2fe;flex-shrink:0;">
        <div style="display:flex;align-items:center;gap:.75rem;">
          <div style="width:42px;height:42px;background:linear-gradient(135deg,#38bdf8,#0284c7);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-icons" style="font-size:1.5rem;color:#fff;">school</span>
          </div>
          <div>
            <div style="font-weight:800;font-size:1.05rem;color:#0c4a6e;line-height:1.1;">IT Learning</div>
            <div style="font-size:.82rem;color:#7dd3fc;font-weight:600;">Hub</div>
          </div>
        </div>
      </div>

      <!-- Nav -->
      <nav style="flex:1;padding:1.25rem 1rem;display:flex;flex-direction:column;gap:.35rem;overflow-y:auto;">
        <RouterLink
          v-for="item in nav" :key="item.to"
          :to="item.to"
          class="sidebar-link"
          :class="{ 'sidebar-link--active': route.path === item.to }"
        >
          <span class="material-icons" style="font-size:1.4rem;">{{ item.icon }}</span>
          {{ item.label }}
        </RouterLink>
        <div style="height:1.5px;background:#e0f2fe;margin:.625rem 0;"></div>
        <RouterLink
          v-for="item in sidebarExtra" :key="item.to"
          :to="item.to"
          class="sidebar-link"
          :class="{ 'sidebar-link--active': route.path === item.to }"
        >
          <span class="material-icons" style="font-size:1.4rem;">{{ item.icon }}</span>
          {{ item.label }}
        </RouterLink>
      </nav>

      <!-- User -->
      <div style="padding:1.25rem 1rem;border-top:2px solid #e0f2fe;flex-shrink:0;">
        <RouterLink to="/mypage" style="display:flex;align-items:center;gap:.875rem;margin-bottom:.875rem;text-decoration:none;border-radius:.875rem;padding:.5rem;transition:background .15s;" :class="{ 'sidebar-user--active': route.path === '/mypage' }">
          <div style="width:40px;height:40px;border-radius:50%;flex-shrink:0;overflow:hidden;">
            <img v-if="auth.user?.avatar" :src="auth.user.avatar" alt="avatar" style="width:100%;height:100%;object-fit:cover;" />
            <div v-else style="width:100%;height:100%;background:linear-gradient(135deg,#7dd3fc,#0284c7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;">
              {{ auth.user?.name?.charAt(0) ?? '?' }}
            </div>
          </div>
          <div style="overflow:hidden;min-width:0;">
            <div style="font-size:.95rem;font-weight:700;color:#0c4a6e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth.user?.name }}</div>
            <div style="font-size:.8rem;color:#94a3b8;margin-top:.1rem;">{{ auth.user?.status ?? '学習中' }} · マイページ</div>
          </div>
          <span class="material-icons" style="font-size:1.1rem;color:#94a3b8;flex-shrink:0;margin-left:auto;">chevron_right</span>
        </RouterLink>
        <button class="btn-secondary" style="width:100%;justify-content:center;" @click="handleLogout">ログアウト</button>
      </div>
    </aside>

    <!-- ══ コンテンツ列（モバイルヘッダー＋メイン） ══ -->
    <div class="content-col">

      <!-- モバイルヘッダー（スマホ専用） -->
      <header class="mobile-header">
        <div style="display:flex;align-items:center;gap:.5rem;">
          <div style="width:28px;height:28px;background:linear-gradient(135deg,#38bdf8,#0284c7);border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-icons" style="font-size:.95rem;color:#fff;">school</span>
          </div>
          <span style="font-weight:700;font-size:.9rem;color:#0c4a6e;">IT Learning Hub</span>
        </div>
        <div style="display:flex;align-items:center;gap:.5rem;">
          <RouterLink to="/mypage" style="width:30px;height:30px;border-radius:50%;flex-shrink:0;overflow:hidden;text-decoration:none;display:block;">
            <img v-if="auth.user?.avatar" :src="auth.user.avatar" alt="avatar" style="width:100%;height:100%;object-fit:cover;" />
            <div v-else style="width:100%;height:100%;background:linear-gradient(135deg,#7dd3fc,#0284c7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.82rem;">
              {{ auth.user?.name?.charAt(0) ?? '?' }}
            </div>
          </RouterLink>
          <button style="background:none;border:none;cursor:pointer;font-size:.72rem;color:#64748b;font-weight:600;padding:.25rem;" @click="handleLogout">
            ログアウト
          </button>
        </div>
      </header>

      <!-- メインコンテンツ -->
      <main class="layout-main">
        <RouterView />
      </main>

    </div><!-- /content-col -->

    <!-- ══ ボトムナビ（スマホ専用・fixed） ══ -->
    <nav class="bottom-nav">
      <RouterLink
        v-for="item in nav" :key="item.to"
        :to="item.to"
        :class="{ active: route.path === item.to }"
      >
        <span class="material-icons">{{ item.icon }}</span>
        {{ item.label }}
      </RouterLink>
    </nav>

  </div>
</template>

<style scoped>
.sidebar-link {
  display: flex;
  align-items: center;
  gap: .875rem;
  padding: .8rem 1rem;
  border-radius: .875rem;
  text-decoration: none;
  font-size: 1rem;
  font-weight: 500;
  color: #475569;
  transition: background .18s, color .18s;
}
.sidebar-link:hover       { background: #f0f9ff; }
.sidebar-link--active     { background: linear-gradient(90deg,#e0f2fe,#bae6fd); color: #0284c7; font-weight: 700; }
.sidebar-user--active     { background: #f0f9ff; }
</style>
