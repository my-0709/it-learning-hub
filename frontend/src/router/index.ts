import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/login',    component: () => import('@/pages/LoginPage.vue'),    meta: { guest: true } },
    { path: '/register', component: () => import('@/pages/RegisterPage.vue'), meta: { guest: true } },
    {
      path: '/',
      component: () => import('@/layouts/AppLayout.vue'),
      meta: { auth: true },
      children: [
        { path: '',         redirect: '/dashboard' },
        { path: 'dashboard',component: () => import('@/pages/DashboardPage.vue') },
        { path: 'terms',    component: () => import('@/pages/TermsPage.vue') },
        { path: 'terms/:id',component: () => import('@/pages/TermDetailPage.vue') },
        { path: 'quiz',     component: () => import('@/pages/QuizPage.vue') },
        { path: 'history',  component: () => import('@/pages/HistoryPage.vue') },
        { path: 'weak',     component: () => import('@/pages/WeakPointPage.vue') },
        { path: 'favorites',component: () => import('@/pages/FavoritesPage.vue') },
        { path: 'mypage',   component: () => import('@/pages/MyPage.vue') },
      ],
    },
    { path: '/:pathMatch(.*)*', redirect: '/dashboard' },
  ],
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()
  if (to.meta.auth && !auth.isLoggedIn()) return '/login'
  if (to.meta.guest && auth.isLoggedIn())  return '/dashboard'
})

export default router
