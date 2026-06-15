<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '@/api/client'

const records = ref<any>({ data: [], total: 0 })
const stats   = ref<any>(null)
const page    = ref(1)
const loading = ref(true)

onMounted(async () => {
  const [r, s] = await Promise.all([
    api.get('/learning-records', { params: { page: page.value } }),
    api.get('/learning-records/stats'),
  ])
  records.value = r.data
  stats.value   = s.data
  loading.value = false
})

async function changePage(p: number) {
  page.value = p
  const { data } = await api.get('/learning-records', { params: { page: p } })
  records.value  = data
}

function fmtDate(d: string) {
  return new Date(d).toLocaleString('ja-JP', { month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' })
}
</script>

<template>
  <div>
    <h1 class="page-title" style="margin:0 0 1.25rem;font-size:1.4rem;font-weight:800;color:#0c4a6e;display:flex;align-items:center;gap:.5rem;">
      <span class="material-icons" style="font-size:1.4rem;color:#0284c7;">bar_chart</span>
      学習履歴
    </h1>

    <!-- Stats -->
    <div v-if="stats" class="grid-stats" style="margin-bottom:1.25rem;">
      <div class="card" style="text-align:center;">
        <div style="font-size:1.6rem;font-weight:800;color:#0284c7;">{{ stats.total_answered }}</div>
        <div style="font-size:.75rem;color:#64748b;font-weight:600;margin-top:.2rem;">総回答数</div>
      </div>
      <div class="card" style="text-align:center;">
        <div style="font-size:1.6rem;font-weight:800;color:#059669;">{{ stats.accuracy }}%</div>
        <div style="font-size:.75rem;color:#64748b;font-weight:600;margin-top:.2rem;">正答率</div>
      </div>
      <div class="card" style="text-align:center;">
        <div style="font-size:1.6rem;font-weight:800;color:#ea580c;">{{ stats.streak }}日</div>
        <div style="font-size:.75rem;color:#64748b;font-weight:600;margin-top:.2rem;">連続学習</div>
      </div>
    </div>

    <!-- Category chart -->
    <div v-if="stats?.by_category?.length" class="card" style="margin-bottom:1.25rem;">
      <h2 style="margin:0 0 1rem;font-size:.95rem;font-weight:700;color:#0c4a6e;">カテゴリ別正答率</h2>
      <div style="display:flex;flex-direction:column;gap:.875rem;">
        <div v-for="cat in stats.by_category" :key="cat.category_id">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.3rem;">
            <div style="display:flex;align-items:center;gap:.4rem;min-width:0;flex:1;margin-right:.5rem;">
              <div :style="`width:8px;height:8px;border-radius:50%;background:${cat.color};flex-shrink:0;`"></div>
              <span style="font-size:.82rem;font-weight:600;color:#334155;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ cat.category_name }}</span>
              <span style="font-size:.72rem;color:#94a3b8;flex-shrink:0;">{{ cat.total }}問</span>
            </div>
            <span style="font-size:.875rem;font-weight:700;flex-shrink:0;" :style="`color:${cat.accuracy >= 70 ? '#059669' : cat.accuracy >= 40 ? '#d97706' : '#dc2626'}`">
              {{ cat.accuracy }}%
            </span>
          </div>
          <div style="height:8px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
            <div :style="`height:100%;width:${cat.accuracy}%;background:${cat.color};border-radius:99px;transition:width 1s ease;`"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Records list -->
    <div class="card">
      <h2 style="margin:0 0 1rem;font-size:.95rem;font-weight:700;color:#0c4a6e;">回答履歴</h2>

      <div v-if="loading">
        <div v-for="i in 5" :key="i" class="skeleton" style="height:56px;margin-bottom:.6rem;border-radius:.75rem;"></div>
      </div>

      <div v-else-if="records.data.length">
        <div v-for="rec in records.data" :key="rec.id" class="record-row">
          <div :class="`result-dot ${rec.is_correct ? 'result-ok' : 'result-ng'}`">
            <span class="material-icons" :style="`font-size:1rem;color:${rec.is_correct ? '#059669' : '#dc2626'};`">
              {{ rec.is_correct ? 'check_circle' : 'cancel' }}
            </span>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:.85rem;font-weight:600;color:#0c4a6e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
              {{ rec.quiz?.term?.name ?? '?' }}
            </div>
            <div style="font-size:.72rem;color:#94a3b8;margin-top:.1rem;">
              {{ rec.quiz?.term?.category?.name }} ・ {{ fmtDate(rec.answered_at) }}
            </div>
          </div>
          <div v-if="rec.response_time_ms" style="font-size:.72rem;color:#94a3b8;flex-shrink:0;">
            {{ (rec.response_time_ms / 1000).toFixed(1) }}秒
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="records.last_page > 1" style="display:flex;justify-content:center;align-items:center;gap:.5rem;margin-top:1rem;">
          <button class="btn-secondary" style="padding:.4rem .6rem;font-size:.85rem;display:flex;align-items:center;" :disabled="page <= 1" @click="changePage(page-1)">
            <span class="material-icons" style="font-size:1rem;">chevron_left</span>
          </button>
          <span style="font-size:.85rem;color:#64748b;">{{ page }} / {{ records.last_page }}</span>
          <button class="btn-secondary" style="padding:.4rem .6rem;font-size:.85rem;display:flex;align-items:center;" :disabled="page >= records.last_page" @click="changePage(page+1)">
            <span class="material-icons" style="font-size:1rem;">chevron_right</span>
          </button>
        </div>
      </div>

      <div v-else style="text-align:center;padding:2.5rem 1rem;color:#94a3b8;">
        <span class="material-icons" style="font-size:2.5rem;display:block;margin-bottom:.5rem;">assignment</span>
        <p style="margin:0 0 1rem;">まだ学習履歴がありません。クイズを解いてみましょう！</p>
        <RouterLink to="/quiz">
          <button class="btn-primary" style="display:inline-flex;align-items:center;gap:.4rem;">
            <span class="material-icons" style="font-size:1rem;">quiz</span>
            クイズを始める
          </button>
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.skeleton {
  background: linear-gradient(90deg,#e0f2fe 25%,#bae6fd 50%,#e0f2fe 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

.record-row {
  display: flex;
  align-items: center;
  padding: .6rem .25rem;
  border-bottom: 1px solid #f1f5f9;
  gap: .75rem;
}

.result-dot {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.result-ok { background: #dcfce7; }
.result-ng { background: #fef2f2; }
</style>
