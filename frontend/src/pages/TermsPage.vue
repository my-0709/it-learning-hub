<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/api/client'

const route  = useRoute()
const router = useRouter()

const terms      = ref<any>({ data: [], total: 0, last_page: 1 })
const categories = ref<any[]>([])
const search     = ref((route.query.q as string) ?? '')
const categoryId = ref((route.query.category_id as string) ?? '')
const page       = ref(Number(route.query.page ?? 1))
const loading    = ref(false)

async function fetchCategories() {
  const { data } = await api.get('/categories')
  categories.value = data
}

async function fetchTerms() {
  loading.value = true
  try {
    const { data } = await api.get('/terms', {
      params: { q: search.value || undefined, category_id: categoryId.value || undefined, page: page.value }
    })
    terms.value = data
  } finally {
    loading.value = false
  }
}

onMounted(() => { fetchCategories(); fetchTerms() })
watch([search, categoryId], () => { page.value = 1; fetchTerms() })
watch(page, fetchTerms)

async function toggleFav(term: any) {
  try {
    const { data } = await api.post(`/favorites/${term.id}`)
    term.is_favorite = data.is_favorite
  } catch {}
}

function difficultyLabel(d: number) {
  return ['','★☆☆☆☆','★★☆☆☆','★★★☆☆','★★★★☆','★★★★★'][d] ?? '★'
}
function difficultyColor(d: number) {
  return ['','#22c55e','#84cc16','#f59e0b','#f97316','#ef4444'][d] ?? '#6b7280'
}
</script>

<template>
  <div>
    <div style="margin-bottom:1.25rem;">
      <h1 class="page-title" style="margin:0 0 .2rem;font-size:1.4rem;font-weight:800;color:#0c4a6e;display:flex;align-items:center;gap:.5rem;">
        <span class="material-icons" style="font-size:1.4rem;color:#0284c7;">menu_book</span>
        単語帳
      </h1>
      <p style="margin:0;color:#64748b;font-size:.85rem;">IT用語を検索・フィルタリングして学ぼう</p>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom:1rem;">
      <div class="filters-row">
        <div style="position:relative;flex:1;min-width:0;">
          <span class="material-icons" style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);font-size:1.1rem;color:#94a3b8;pointer-events:none;">search</span>
          <input v-model="search" class="input" style="padding-left:2.5rem;" placeholder="用語を検索..." />
        </div>
        <select v-model="categoryId" class="input filter-select">
          <option value="">すべてのカテゴリ</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>
      </div>
    </div>

    <!-- Results count -->
    <div style="margin-bottom:.875rem;font-size:.82rem;color:#64748b;" v-if="!loading">
      {{ terms.total }} 件の用語
    </div>

    <!-- Loading skeleton -->
    <div v-if="loading" class="grid-cards">
      <div v-for="i in 6" :key="i" class="card skeleton" style="height:120px;"></div>
    </div>

    <!-- Terms grid -->
    <div v-else-if="terms.data.length" class="grid-cards">
      <div v-for="term in terms.data" :key="term.id"
        class="card term-card"
        @click="router.push(`/terms/${term.id}`)">

        <button class="fav-btn" @click.stop="toggleFav(term)">
          <span class="material-icons" :style="`font-size:1.3rem;color:${term.is_favorite ? '#f59e0b' : '#cbd5e1'};`">
            {{ term.is_favorite ? 'star' : 'star_border' }}
          </span>
        </button>

        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem;">
          <div :style="`width:8px;height:8px;border-radius:50%;background:${term.category?.color ?? '#38bdf8'};flex-shrink:0;`"></div>
          <span style="font-size:.72rem;color:#64748b;font-weight:600;">{{ term.category?.name }}</span>
          <span style="margin-left:auto;margin-right:1.75rem;font-size:.7rem;" :style="`color:${difficultyColor(term.difficulty)};font-weight:600;`">
            {{ difficultyLabel(term.difficulty) }}
          </span>
        </div>

        <h3 style="margin:0 0 .4rem;font-size:.95rem;font-weight:700;color:#0c4a6e;">{{ term.name }}</h3>
        <p style="margin:0;font-size:.8rem;color:#475569;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
          {{ term.definition }}
        </p>
      </div>
    </div>

    <div v-else style="text-align:center;padding:3rem 1rem;color:#94a3b8;">
      <span class="material-icons" style="font-size:3rem;display:block;margin-bottom:.75rem;">search_off</span>
      <p style="margin:0;">条件に一致する用語が見つかりませんでした</p>
    </div>

    <!-- Pagination -->
    <div v-if="terms.last_page > 1" style="display:flex;justify-content:center;align-items:center;gap:.5rem;margin-top:1.25rem;">
      <button class="btn-secondary" style="padding:.4rem .8rem;font-size:.85rem;display:flex;align-items:center;" :disabled="page <= 1" @click="page--">
        <span class="material-icons" style="font-size:1rem;">chevron_left</span>前へ
      </button>
      <span style="font-size:.85rem;color:#64748b;">{{ page }} / {{ terms.last_page }}</span>
      <button class="btn-secondary" style="padding:.4rem .8rem;font-size:.85rem;display:flex;align-items:center;" :disabled="page >= terms.last_page" @click="page++">
        次へ<span class="material-icons" style="font-size:1rem;">chevron_right</span>
      </button>
    </div>
  </div>
</template>

<style scoped>
.filters-row {
  display: flex;
  gap: .75rem;
  flex-wrap: wrap;
}
.filter-select {
  width: auto;
  min-width: 140px;
  flex-shrink: 0;
}

.skeleton {
  background: linear-gradient(90deg,#e0f2fe 25%,#bae6fd 50%,#e0f2fe 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

.term-card {
  cursor: pointer;
  transition: transform .2s, box-shadow .2s;
  position: relative;
}
.term-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(14,165,233,.15);
}
.term-card:active { transform: scale(.98); }

.fav-btn {
  position: absolute;
  top: .75rem;
  right: .75rem;
  background: none;
  border: none;
  cursor: pointer;
  padding: .25rem;
  display: flex;
  align-items: center;
}

@media (max-width: 767px) {
  .filter-select { min-width: 0; flex: 1; }
}
</style>
