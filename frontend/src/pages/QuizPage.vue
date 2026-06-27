<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '@/api/client'

const route = useRoute()

type Phase = 'setup' | 'quiz' | 'answered' | 'finished'

const phase       = ref<Phase>('setup')
const categories  = ref<any[]>([])
const selCat      = ref(route.query.category_id as string ?? '')
const weakMode    = ref(false)
const sessionId   = ref<number | null>(null)
const quiz        = ref<any>(null)
const result      = ref<any>(null)
const score       = ref({ total: 0, correct: 0 })
const loadingQ    = ref(false)
const startTime   = ref(0)
const answeredIds = ref<number[]>([])

onMounted(async () => {
  const { data } = await api.get('/categories')
  categories.value = data
})

async function startQuiz() {
  const { data } = await api.post('/quiz-sessions', { category_id: selCat.value || null })
  sessionId.value  = data.id
  score.value      = { total: 0, correct: 0 }
  answeredIds.value = []
  await nextQuestion()
  phase.value = 'quiz'
}

async function nextQuestion() {
  loadingQ.value = true
  try {
    const { data } = await api.get('/quizzes/random', {
      params: {
        category_id: selCat.value || undefined,
        weak_mode:   weakMode.value ? 1 : undefined,
        exclude_ids: answeredIds.value.length ? answeredIds.value : undefined,
      },
    })
    quiz.value      = data
    result.value    = null
    phase.value     = 'quiz'
    startTime.value = Date.now()
  } catch {
    // 問題がなくなった（404）または取得失敗 → 終了
    await endQuiz()
  } finally {
    loadingQ.value = false
  }
}

async function answer(choiceId: number) {
  if (!quiz.value || !sessionId.value) return
  const elapsed = Date.now() - startTime.value
  const { data } = await api.post(`/quiz-sessions/${sessionId.value}/answer`, {
    quiz_id: quiz.value.id,
    choice_id: choiceId,
    response_time_ms: elapsed,
  })
  result.value = data
  score.value.total++
  if (data.is_correct) score.value.correct++
  answeredIds.value = [...answeredIds.value, quiz.value.id]
  phase.value = 'answered'
}

async function endQuiz() {
  if (sessionId.value) {
    await api.post(`/quiz-sessions/${sessionId.value}/end`)
  }
  phase.value = 'finished'
}
</script>

<template>
  <div>
    <h1 class="page-title" style="margin:0 0 1.25rem;font-size:1.4rem;font-weight:800;color:#0c4a6e;display:flex;align-items:center;gap:.5rem;">
      <span class="material-icons" style="font-size:1.4rem;color:#0284c7;">quiz</span>
      クイズ
    </h1>

    <!-- Setup -->
    <div v-if="phase === 'setup'" class="card quiz-panel">
      <h2 style="margin:0 0 1.25rem;font-size:1.05rem;font-weight:700;color:#0c4a6e;">クイズ設定</h2>
      <div style="display:flex;flex-direction:column;gap:1rem;">
        <div>
          <label style="display:block;font-size:.85rem;font-weight:600;color:#334155;margin-bottom:.4rem;">カテゴリ</label>
          <select v-model="selCat" class="input">
            <option value="">すべてのカテゴリ（ランダム）</option>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
        </div>
        <label style="display:flex;align-items:center;gap:.6rem;cursor:pointer;font-size:.9rem;font-weight:500;color:#334155;">
          <input type="checkbox" v-model="weakMode" style="width:16px;height:16px;accent-color:#0ea5e9;" />
          苦手問題優先モード
        </label>
        <button class="btn-primary" style="justify-content:center;display:flex;align-items:center;gap:.4rem;" @click="startQuiz">
          <span class="material-icons" style="font-size:1.1rem;">rocket_launch</span>
          クイズを開始する
        </button>
      </div>
    </div>

    <!-- Quiz / Answered -->
    <div v-else-if="phase === 'quiz' || phase === 'answered'" class="quiz-panel">
      <!-- Score bar -->
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <div style="font-size:.85rem;color:#64748b;">問題数: {{ score.total }}</div>
        <div style="font-size:.85rem;font-weight:700;color:#0284c7;">正解: {{ score.correct }}</div>
        <button class="btn-secondary" style="font-size:.8rem;padding:.35rem .75rem;" @click="endQuiz">終了する</button>
      </div>

      <div v-if="loadingQ" class="card skeleton" style="height:240px;"></div>

      <div v-else-if="quiz" class="card">
        <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.875rem;">
          <div :style="`width:8px;height:8px;border-radius:50%;background:${quiz.term?.category?.color ?? '#38bdf8'};flex-shrink:0;`"></div>
          <span style="font-size:.78rem;color:#64748b;font-weight:600;">{{ quiz.term?.category?.name }}</span>
        </div>

        <h2 style="margin:0 0 1.25rem;font-size:1rem;font-weight:700;color:#0c4a6e;line-height:1.65;">{{ quiz.question }}</h2>

        <div style="display:flex;flex-direction:column;gap:.6rem;">
          <button v-for="choice in quiz.choices" :key="choice.id"
            class="choice-btn"
            :class="{
              'choice-correct': phase === 'answered' && choice.is_correct,
              'choice-wrong':   phase === 'answered' && !choice.is_correct && result?.correct_choice?.id !== choice.id,
              'choice-show':    phase === 'answered' && !choice.is_correct && result?.correct_choice?.id === choice.id,
            }"
            :disabled="phase === 'answered'"
            @click="answer(choice.id)">
            {{ choice.body }}
          </button>
        </div>

        <!-- Result feedback -->
        <div v-if="phase === 'answered'" style="margin-top:1.25rem;">
          <div class="feedback" :class="result.is_correct ? 'feedback-ok' : 'feedback-ng'">
            <div style="font-weight:700;font-size:.95rem;margin-bottom:.3rem;display:flex;align-items:center;gap:.4rem;">
              <span class="material-icons" style="font-size:1.1rem;">{{ result.is_correct ? 'check_circle' : 'cancel' }}</span>
              {{ result.is_correct ? '正解！' : '不正解' }}
            </div>
            <p style="margin:0;font-size:.85rem;line-height:1.6;">{{ result.explanation }}</p>
          </div>
          <button v-if="!loadingQ" class="btn-primary" style="width:100%;justify-content:center;display:flex;align-items:center;gap:.4rem;margin-top:.75rem;" @click="nextQuestion">
            <span v-if="loadingQ" class="material-icons" style="font-size:1.1rem;animation:spin 1s linear infinite;">hourglass_empty</span>
            <template v-else>
              次の問題へ
              <span class="material-icons" style="font-size:1.1rem;">arrow_forward</span>
            </template>
          </button>
        </div>
      </div>
    </div>

    <!-- Finished -->
    <div v-else-if="phase === 'finished'" class="card quiz-panel" style="text-align:center;">
      <span class="material-icons" style="font-size:3rem;color:#f59e0b;display:block;margin-bottom:1rem;">emoji_events</span>
      <h2 style="margin:0 0 .4rem;font-size:1.25rem;font-weight:800;color:#0c4a6e;">クイズ終了！</h2>
      <p style="margin:0 0 1.5rem;color:#64748b;font-size:.9rem;">お疲れさまでした</p>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
        <div style="background:#f0f9ff;border-radius:.875rem;padding:1rem;">
          <div style="font-size:1.8rem;font-weight:800;color:#0284c7;">{{ score.total }}</div>
          <div style="font-size:.78rem;color:#64748b;font-weight:600;">回答数</div>
        </div>
        <div style="background:#f0fdf4;border-radius:.875rem;padding:1rem;">
          <div style="font-size:1.8rem;font-weight:800;color:#059669;">
            {{ score.total ? Math.round(score.correct / score.total * 100) : 0 }}%
          </div>
          <div style="font-size:.78rem;color:#64748b;font-weight:600;">正答率</div>
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:.6rem;">
        <button class="btn-primary" style="justify-content:center;display:flex;align-items:center;gap:.4rem;" @click="phase='setup'">
          <span class="material-icons" style="font-size:1.1rem;">refresh</span>
          もう一度挑戦する
        </button>
        <RouterLink to="/history" style="text-decoration:none;">
          <button class="btn-secondary" style="width:100%;justify-content:center;display:flex;align-items:center;gap:.4rem;">
            <span class="material-icons" style="font-size:1.1rem;">bar_chart</span>
            学習履歴を見る
          </button>
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
.quiz-panel {
  width: 100%;
  max-width: 560px;
}

.skeleton {
  background: linear-gradient(90deg,#e0f2fe 25%,#bae6fd 50%,#e0f2fe 75%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

.choice-btn {
  width: 100%;
  text-align: left;
  padding: .875rem 1rem;
  border-radius: .875rem;
  border: 1.5px solid #bae6fd;
  cursor: pointer;
  font-size: .9rem;
  font-weight: 500;
  color: #334155;
  background: #fff;
  transition: border-color .18s, background .18s;
  line-height: 1.5;
}
.choice-btn:not(:disabled):hover { border-color: #0ea5e9; background: #f0f9ff; }
.choice-btn:disabled              { cursor: default; }
.choice-correct { border-color: #22c55e !important; background: #dcfce7 !important; color: #166534 !important; font-weight: 700 !important; }
.choice-wrong   { border-color: #e2e8f0 !important; color: #94a3b8 !important; }
.choice-show    { border-color: #22c55e !important; background: #dcfce7 !important; color: #166534 !important; }

.feedback {
  padding: .875rem 1rem;
  border-radius: .875rem;
  border: 1.5px solid;
}
.feedback-ok { background: #dcfce7; border-color: #22c55e; color: #166534; }
.feedback-ng { background: #fef2f2; border-color: #fca5a5; color: #991b1b; }

@media (max-width: 767px) {
  .quiz-panel { max-width: 100%; }
  .choice-btn { font-size: .875rem; padding: .75rem .875rem; }
}
</style>
