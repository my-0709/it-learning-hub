<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api/client'

const route  = useRoute()
const router = useRouter()
const term   = ref<any>(null)
const loading= ref(true)

onMounted(async () => {
  try {
    const { data } = await api.get(`/terms/${route.params.id}`)
    term.value = data
  } catch { router.push('/terms') }
  loading.value = false
})

async function toggleFav() {
  if (!term.value) return
  const { data } = await api.post(`/favorites/${term.value.id}`)
  term.value.is_favorite = data.is_favorite
}

function difficultyLabel(d: number) {
  return ['','初級','初中級','中級','上級','最上級'][d] ?? '？'
}
function difficultyColor(d: number) {
  return ['','#22c55e','#84cc16','#f59e0b','#f97316','#ef4444'][d] ?? '#6b7280'
}
function difficultyBg(d: number) {
  return ['','#dcfce7','#ecfccb','#fef3c7','#fff7ed','#fef2f2'][d] ?? '#f1f5f9'
}
</script>

<template>
  <div>
    <button class="btn-secondary" style="margin-bottom:1.25rem;font-size:.85rem;display:flex;align-items:center;gap:.25rem;" @click="router.push('/terms')">
      <span class="material-icons" style="font-size:1rem;">arrow_back</span>
      単語帳に戻る
    </button>

    <div v-if="loading" class="card skeleton" style="height:280px;"></div>

    <div v-else-if="term">
      <div class="card" style="margin-bottom:1rem;">
        <!-- Header -->
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;gap:.5rem;">
          <div style="min-width:0;flex:1;">
            <div style="display:flex;align-items:center;flex-wrap:wrap;gap:.5rem;margin-bottom:.6rem;">
              <div :style="`width:10px;height:10px;border-radius:50%;background:${term.category?.color ?? '#38bdf8'};flex-shrink:0;`"></div>
              <span style="font-size:.8rem;color:#64748b;font-weight:600;">{{ term.category?.name }}</span>
              <span :style="`font-size:.75rem;font-weight:700;padding:.2rem .65rem;border-radius:999px;color:${difficultyColor(term.difficulty)};background:${difficultyBg(term.difficulty)};`">
                {{ difficultyLabel(term.difficulty) }}
              </span>
            </div>
            <h1 style="margin:0;font-size:1.4rem;font-weight:800;color:#0c4a6e;line-height:1.3;">{{ term.name }}</h1>
          </div>
          <button style="background:none;border:none;cursor:pointer;padding:.25rem;display:flex;align-items:center;flex-shrink:0;" @click="toggleFav">
            <span class="material-icons" :style="`font-size:1.8rem;color:${term.is_favorite ? '#f59e0b' : '#cbd5e1'};`">
              {{ term.is_favorite ? 'star' : 'star_border' }}
            </span>
          </button>
        </div>

        <!-- Tags -->
        <div v-if="term.tags?.length" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1.25rem;">
          <span v-for="tag in term.tags" :key="tag.id" class="badge" style="background:#e0f2fe;color:#0284c7;">
            # {{ tag.name }}
          </span>
        </div>

        <!-- Definition -->
        <div style="margin-bottom:1.25rem;">
          <h3 style="margin:0 0 .5rem;font-size:.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">定義</h3>
          <p style="margin:0;color:#1e293b;line-height:1.75;font-size:.95rem;">{{ term.definition }}</p>
        </div>

        <!-- Example -->
        <div v-if="term.example">
          <h3 style="margin:0 0 .5rem;font-size:.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;">使用例</h3>
          <div style="background:#f0f9ff;border-left:4px solid #38bdf8;padding:.875rem 1rem;border-radius:0 .75rem .75rem 0;">
            <p style="margin:0;color:#0c4a6e;line-height:1.75;font-size:.9rem;font-style:italic;">{{ term.example }}</p>
          </div>
        </div>
      </div>

      <!-- Action buttons -->
      <div class="detail-actions">
        <button class="btn-primary" style="flex:1;justify-content:center;display:flex;align-items:center;gap:.4rem;"
          @click="router.push(`/quiz?category_id=${term.category_id}`)">
          <span class="material-icons" style="font-size:1.1rem;">gps_fixed</span>
          このカテゴリのクイズ
        </button>
        <button class="btn-secondary" style="flex:1;justify-content:center;display:flex;align-items:center;gap:.4rem;"
          @click="router.push('/terms')">
          <span class="material-icons" style="font-size:1.1rem;">menu_book</span>
          他の用語を見る
        </button>
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

.detail-actions {
  display: flex;
  gap: .75rem;
}

@media (max-width: 480px) {
  .detail-actions { flex-direction: column; }
  .detail-actions button { width: 100%; }
}
</style>
