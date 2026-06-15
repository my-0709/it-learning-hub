<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/api/client'

const auth   = useAuthStore()
const router = useRouter()

const stats = ref<any>(null)
const categories = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    const [s, c] = await Promise.all([
      api.get('/learning-records/stats'),
      api.get('/categories'),
    ])
    stats.value      = s.data
    categories.value = c.data
  } catch {}
  loading.value = false
})
</script>

<template>
  <div>
    <!-- 挨拶 -->
    <div class="greeting">
      <h1 class="page-title" style="margin:0 0 .25rem;font-weight:800;color:#0c4a6e;display:flex;align-items:flex-start;gap:.4rem;flex-wrap:wrap;word-break:break-all;">
        <span class="material-icons" style="font-size:1.4rem;color:#0284c7;flex-shrink:0;margin-top:.1rem;">waving_hand</span>
        <span>おかえりなさい、{{ auth.user?.name ?? '' }} さん</span>
      </h1>
      <p style="margin:0;color:#64748b;font-size:.875rem;">今日も学習を続けましょう！</p>
    </div>

    <!-- Stats cards -->
    <div v-if="!loading && stats" class="grid-stats" style="margin-bottom:1.5rem;">
      <div class="card" style="text-align:center;">
        <span class="material-icons" style="font-size:1.8rem;color:#0284c7;margin-bottom:.3rem;display:block;">quiz</span>
        <div style="font-size:1.8rem;font-weight:800;color:#0284c7;">{{ stats.total_answered }}</div>
        <div style="font-size:.78rem;color:#64748b;font-weight:600;">回答数</div>
      </div>
      <div class="card" style="text-align:center;">
        <span class="material-icons" style="font-size:1.8rem;color:#059669;margin-bottom:.3rem;display:block;">check_circle</span>
        <div style="font-size:1.8rem;font-weight:800;color:#059669;">{{ stats.accuracy }}%</div>
        <div style="font-size:.78rem;color:#64748b;font-weight:600;">正答率</div>
      </div>
      <div class="card" style="text-align:center;">
        <span class="material-icons" style="font-size:1.8rem;color:#ea580c;margin-bottom:.3rem;display:block;">local_fire_department</span>
        <div style="font-size:1.8rem;font-weight:800;color:#ea580c;">{{ stats.streak }}</div>
        <div style="font-size:.78rem;color:#64748b;font-weight:600;">連続学習日数</div>
      </div>
      <div class="card" style="text-align:center;">
        <span class="material-icons" style="font-size:1.8rem;color:#7c3aed;margin-bottom:.3rem;display:block;">emoji_events</span>
        <div style="font-size:1.8rem;font-weight:800;color:#7c3aed;">{{ stats.total_correct }}</div>
        <div style="font-size:.78rem;color:#64748b;font-weight:600;">正解数</div>
      </div>
    </div>

    <!-- Loading skeleton -->
    <div v-if="loading" class="grid-stats" style="margin-bottom:1.5rem;">
      <div v-for="i in 4" :key="i" class="card skeleton" style="height:100px;"></div>
    </div>

    <!-- Quick actions -->
    <div class="grid-actions" style="margin-bottom:1.5rem;">
      <div class="card action-card" @click="router.push('/quiz')">
        <div style="display:flex;align-items:center;gap:.875rem;">
          <div style="width:44px;height:44px;background:linear-gradient(135deg,#38bdf8,#0284c7);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-icons" style="font-size:1.4rem;color:#fff;">gps_fixed</span>
          </div>
          <div>
            <div style="font-weight:700;color:#0c4a6e;font-size:.95rem;">クイズを始める</div>
            <div style="font-size:.78rem;color:#64748b;margin-top:.15rem;">ランダム4択クイズ</div>
          </div>
        </div>
      </div>
      <div class="card action-card" @click="router.push('/terms')">
        <div style="display:flex;align-items:center;gap:.875rem;">
          <div style="width:44px;height:44px;background:linear-gradient(135deg,#34d399,#059669);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <span class="material-icons" style="font-size:1.4rem;color:#fff;">menu_book</span>
          </div>
          <div>
            <div style="font-weight:700;color:#0c4a6e;font-size:.95rem;">用語を学ぶ</div>
            <div style="font-size:.78rem;color:#64748b;margin-top:.15rem;">IT用語辞典</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Category accuracy -->
    <div v-if="stats && stats.by_category?.length" class="card" style="margin-bottom:1.5rem;">
      <h2 style="margin:0 0 1rem;font-size:.95rem;font-weight:700;color:#0c4a6e;">カテゴリ別正答率</h2>
      <div style="display:flex;flex-direction:column;gap:.75rem;">
        <div v-for="cat in stats.by_category" :key="cat.category_id">
          <div style="display:flex;justify-content:space-between;margin-bottom:.3rem;">
            <span style="font-size:.82rem;font-weight:600;color:#334155;">{{ cat.category_name }}</span>
            <span style="font-size:.82rem;font-weight:700;" :style="`color:${cat.accuracy >= 70 ? '#059669' : cat.accuracy >= 40 ? '#d97706' : '#dc2626'}`">
              {{ cat.accuracy }}%
            </span>
          </div>
          <div style="height:6px;background:#e0f2fe;border-radius:99px;overflow:hidden;">
            <div :style="`height:100%;width:${cat.accuracy}%;background:${cat.color};border-radius:99px;transition:width .8s ease;`"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Categories grid -->
    <div>
      <h2 style="margin:0 0 .875rem;font-size:.95rem;font-weight:700;color:#0c4a6e;">カテゴリから学ぶ</h2>
      <div class="grid-cats">
        <div v-for="cat in categories" :key="cat.id"
          class="cat-card"
          @click="router.push(`/terms?category_id=${cat.id}`)">
          <div :style="`width:32px;height:32px;border-radius:9px;background:${cat.color}20;margin:0 auto .4rem;display:flex;align-items:center;justify-content:center;`">
            <div :style="`width:10px;height:10px;border-radius:50%;background:${cat.color};`"></div>
          </div>
          <div style="font-size:.78rem;font-weight:600;color:#334155;text-align:center;line-height:1.3;">{{ cat.name }}</div>
          <div style="font-size:.7rem;color:#94a3b8;text-align:center;margin-top:.15rem;">{{ cat.terms_count }}語</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.greeting { margin-bottom: 1.5rem; }

.skeleton {
  background: linear-gradient(90deg,#e0f2fe 25%,#bae6fd 50%,#e0f2fe 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

.action-card {
  cursor: pointer;
  transition: transform .2s, box-shadow .2s;
}
.action-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(14,165,233,.15);
}
.action-card:active { transform: scale(.98); }

.cat-card {
  padding: .875rem .5rem;
  border-radius: 1rem;
  background: #fff;
  border: 1.5px solid #e0f2fe;
  cursor: pointer;
  transition: border-color .2s, transform .2s;
}
.cat-card:hover {
  transform: translateY(-2px);
}
.cat-card:active { transform: scale(.97); }
</style>
