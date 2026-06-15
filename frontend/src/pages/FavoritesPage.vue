<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/api/client'

const router = useRouter()
const terms  = ref<any>({ data: [], total: 0, last_page: 1 })
const page   = ref(1)
const loading= ref(true)

onMounted(() => fetchFavorites())

async function fetchFavorites() {
  loading.value = true
  try {
    const { data } = await api.get('/favorites', { params: { page: page.value } })
    terms.value = data
  } finally { loading.value = false }
}

async function toggleFav(term: any) {
  await api.post(`/favorites/${term.id}`)
  terms.value.data = terms.value.data.filter((t: any) => t.id !== term.id)
  terms.value.total--
}
</script>

<template>
  <div>
    <div style="margin-bottom:1.25rem;">
      <h1 class="page-title" style="margin:0 0 .2rem;font-size:1.4rem;font-weight:800;color:#0c4a6e;display:flex;align-items:center;gap:.5rem;">
        <span class="material-icons" style="font-size:1.4rem;color:#f59e0b;">star</span>
        お気に入り
      </h1>
      <p style="margin:0;color:#64748b;font-size:.85rem;">ブックマークした用語一覧</p>
    </div>

    <div v-if="loading" class="grid-cards">
      <div v-for="i in 4" :key="i" class="card skeleton" style="height:110px;"></div>
    </div>

    <div v-else-if="terms.data.length" class="grid-cards">
      <div v-for="term in terms.data" :key="term.id"
        class="card fav-card"
        @click="router.push(`/terms/${term.id}`)">

        <button class="fav-btn" @click.stop="toggleFav(term)" title="お気に入りから削除">
          <span class="material-icons" style="font-size:1.3rem;color:#f59e0b;">star</span>
        </button>

        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.45rem;">
          <div :style="`width:8px;height:8px;border-radius:50%;background:${term.category?.color ?? '#38bdf8'};flex-shrink:0;`"></div>
          <span style="font-size:.72rem;color:#64748b;font-weight:600;">{{ term.category?.name }}</span>
        </div>
        <h3 style="margin:0 0 .4rem;font-size:.95rem;font-weight:700;color:#0c4a6e;padding-right:1.75rem;line-height:1.3;">{{ term.name }}</h3>
        <p style="margin:0;font-size:.8rem;color:#475569;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
          {{ term.definition }}
        </p>
      </div>
    </div>

    <div v-else class="card" style="text-align:center;padding:2.5rem 1rem;">
      <span class="material-icons" style="font-size:3rem;color:#cbd5e1;display:block;margin-bottom:.75rem;">star_border</span>
      <h3 style="margin:0 0 .4rem;color:#0c4a6e;font-size:1rem;">お気に入りがまだありません</h3>
      <p style="color:#64748b;margin:0 0 1.25rem;font-size:.875rem;">用語カードの星マークをタップしてお気に入り登録しましょう</p>
      <RouterLink to="/terms">
        <button class="btn-primary" style="display:inline-flex;align-items:center;gap:.4rem;">
          <span class="material-icons" style="font-size:1rem;">menu_book</span>
          単語帳を見る
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

.fav-card {
  cursor: pointer;
  transition: transform .2s, box-shadow .2s;
  position: relative;
}
.fav-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(14,165,233,.15);
}
.fav-card:active { transform: scale(.98); }

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
</style>
