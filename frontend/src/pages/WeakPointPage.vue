<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/client'

const router   = useRouter()
const weakTerms= ref<any[]>([])
const loading  = ref(true)

onMounted(async () => {
  try {
    const { data } = await api.get('/weak-points')
    weakTerms.value = data
  } finally {
    loading.value = false
  }
})

function accuracyColor(a: number) {
  if (a >= 70) return '#059669'
  if (a >= 40) return '#d97706'
  return '#dc2626'
}
function accuracyBg(a: number) {
  if (a >= 70) return '#dcfce7'
  if (a >= 40) return '#fef3c7'
  return '#fef2f2'
}
</script>

<template>
  <div>
    <div style="margin-bottom:1.25rem;">
      <h1 class="page-title" style="margin:0 0 .2rem;font-size:1.4rem;font-weight:800;color:#0c4a6e;display:flex;align-items:center;gap:.5rem;">
        <span class="material-icons" style="font-size:1.4rem;color:#0284c7;">lightbulb</span>
        苦手分析
      </h1>
      <p style="margin:0;color:#64748b;font-size:.85rem;">正答率の低い用語を集中的に学習しましょう</p>
    </div>

    <div v-if="loading" style="display:flex;flex-direction:column;gap:.75rem;">
      <div v-for="i in 5" :key="i" class="skeleton" style="height:66px;border-radius:.875rem;"></div>
    </div>

    <div v-else-if="weakTerms.length">
      <!-- Header banner -->
      <div class="card" style="margin-bottom:1rem;background:linear-gradient(135deg,#fef3c7,#fff7ed);border-color:#fde68a;">
        <div class="weak-banner">
          <span class="material-icons" style="font-size:1.8rem;color:#d97706;flex-shrink:0;">warning</span>
          <div style="flex:1;min-width:0;">
            <div style="font-weight:700;color:#92400e;font-size:.9rem;">苦手用語が {{ weakTerms.length }} 件あります</div>
            <div style="font-size:.78rem;color:#b45309;margin-top:.15rem;">苦手モードのクイズで集中練習しましょう</div>
          </div>
          <button class="btn-primary"
            style="font-size:.82rem;padding:.45rem .875rem;flex-shrink:0;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;gap:.3rem;"
            @click="router.push('/quiz?weak=1')">
            <span class="material-icons" style="font-size:1rem;">gps_fixed</span>
            苦手クイズ
          </button>
        </div>
      </div>

      <!-- Weak terms list -->
      <div style="display:flex;flex-direction:column;gap:.5rem;">
        <div v-for="(term, index) in weakTerms" :key="term.term_id"
          class="card weak-item"
          @click="router.push(`/terms/${term.term_id}`)">
          <div style="display:flex;align-items:center;gap:.75rem;">
            <!-- Rank -->
            <div class="rank-badge">{{ index + 1 }}</div>

            <!-- Info -->
            <div style="flex:1;min-width:0;">
              <div style="display:flex;align-items:center;gap:.4rem;margin-bottom:.15rem;">
                <div :style="`width:7px;height:7px;border-radius:50%;background:${term.color};flex-shrink:0;`"></div>
                <span style="font-size:.72rem;color:#64748b;font-weight:600;">{{ term.category_name }}</span>
              </div>
              <div style="font-weight:700;color:#0c4a6e;font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ term.term_name }}
              </div>
            </div>

            <!-- Stats -->
            <div style="text-align:right;flex-shrink:0;">
              <div :style="`font-size:1.05rem;font-weight:800;color:${accuracyColor(term.accuracy)};`">
                {{ term.accuracy }}%
              </div>
              <div style="font-size:.68rem;color:#94a3b8;">{{ term.total }}問中{{ term.correct }}問</div>
            </div>

            <!-- Mini bar (hidden on very small) -->
            <div class="mini-bar">
              <div style="height:6px;background:#f1f5f9;border-radius:99px;overflow:hidden;">
                <div :style="`height:100%;width:${term.accuracy}%;background:${accuracyColor(term.accuracy)};border-radius:99px;transition:width .8s;`"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="card" style="text-align:center;padding:2.5rem 1rem;">
      <span class="material-icons" style="font-size:3rem;color:#059669;display:block;margin-bottom:.75rem;">celebration</span>
      <h3 style="margin:0 0 .4rem;color:#0c4a6e;font-size:1rem;">苦手な用語はありません！</h3>
      <p style="color:#64748b;margin:0 0 1.25rem;font-size:.875rem;">2回以上回答した用語が対象です。クイズをもっと解いてみましょう。</p>
      <RouterLink to="/quiz">
        <button class="btn-primary" style="display:inline-flex;align-items:center;gap:.4rem;">
          <span class="material-icons" style="font-size:1rem;">quiz</span>
          クイズを始める
        </button>
      </RouterLink>
    </div>
  </div>
</template>

<style scoped>
.skeleton {
  background: linear-gradient(90deg,#e0f2fe 25%,#bae6fd 50%,#e0f2fe 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

.weak-banner {
  display: flex;
  align-items: center;
  gap: .75rem;
  flex-wrap: wrap;
}

.weak-item {
  cursor: pointer;
  transition: transform .2s, box-shadow .2s;
  padding: .875rem 1rem;
}
.weak-item:hover {
  transform: translateX(4px);
  box-shadow: 0 4px 16px rgba(14,165,233,.12);
}
.weak-item:active { transform: scale(.98); }

.rank-badge {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .72rem;
  font-weight: 800;
  color: #64748b;
  flex-shrink: 0;
}

.mini-bar {
  width: 56px;
  flex-shrink: 0;
}

@media (max-width: 400px) {
  .mini-bar { display: none; }
}
</style>
