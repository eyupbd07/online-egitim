<script setup>
import { ref, watch, onMounted, onUnmounted, computed } from 'vue'
import { Head, useForm, usePage, router } from '@inertiajs/vue3'

const props = defineProps({
    quiz: Object,
    questions: Array,
    quiz_result: Object,
    server_time: Number
})

const page = usePage()
// Eğer sayfa yüklendiğinde sonuç varsa direkt bitmiş say
const quizFinished = ref(!!props.quiz_result)
const result = ref(props.quiz_result || null)
const studentAnswers = ref({})

// Soruları al
const questionList = computed(() => props.questions || props.quiz?.questions || []);

/* --- SÜRE MANTIĞI --- */
const dbDuration = Number(props.quiz?.duration) || 30
const totalSeconds = dbDuration * 60
const STORAGE_KEY = `quiz_start_time_${props.quiz?.id}`
let startTime = parseInt(localStorage.getItem(STORAGE_KEY))

if (!startTime || isNaN(startTime)) {
    if (!quizFinished.value) {
        startTime = props.server_time || Date.now() / 1000
        localStorage.setItem(STORAGE_KEY, startTime)
    }
}

const timeLeft = ref(Math.max(totalSeconds - ((props.server_time || Date.now()/1000) - startTime), 0))
let timer = null

const startCountdown = () => {
    if (quizFinished.value || totalSeconds <= 0) return
    timer = setInterval(() => {
        if (timeLeft.value > 0) timeLeft.value--
        else { clearInterval(timer); submitQuiz(); }
    }, 1000)
}

const formatTime = (seconds) => {
    const m = Math.floor(seconds / 60)
    const s = Math.floor(seconds % 60)
    return `${m}:${s < 10 ? '0' : ''}${s}`
}

/* --- SINAV GÖNDERME --- */
const form = useForm({ answers: {} })

const submitQuiz = () => {
    if (quizFinished.value) return
    form.answers = studentAnswers.value
    
    form.post(route('student.quizzes.submit', props.quiz.id), {
        preserveScroll: true,
        onSuccess: () => {
            localStorage.removeItem(STORAGE_KEY)
            if(timer) clearInterval(timer)
        },
        onError: (e) => console.error(e)
    })
}

const goToCertificates = () => {
    window.location.href = route('student.certificates.index');
}

onMounted(() => { if (!quizFinished.value) startCountdown() })
onUnmounted(() => { if (timer) clearInterval(timer) })

// Controller'dan sonuç (quiz_result) geldiğinde burası çalışır
watch(() => page.props.quiz_result, (newVal) => {
    if (newVal) {
        result.value = newVal
        quizFinished.value = true
        localStorage.removeItem(STORAGE_KEY)
        if (timer) clearInterval(timer)
    }
}, { immediate: true, deep: true })
</script>

<template>
    <Head :title="quiz?.title || 'Sınav'" />

    <VContainer class="py-10 bg-slate-50 min-h-screen">
        <VRow justify="center">
            <VCol cols="12" md="8" lg="6">

                <div v-if="quizFinished && result">
                    <VCard class="text-center pa-10 elevation-10 rounded-xl">
                        
                        <VAvatar :color="result.is_passed ? 'success' : 'red-lighten-1'" size="120" class="mb-6 elevation-4">
                            <VIcon :icon="result.is_passed ? 'mdi-trophy' : 'mdi-close-octagon'" size="60" color="white" />
                        </VAvatar>

                        <h2 class="text-h4 font-weight-black mb-2" :class="result.is_passed ? 'text-success' : 'text-red'">
                            {{ result.is_passed ? 'TEBRİKLER!' : 'MAALESEF' }}
                        </h2>
                        <div class="text-subtitle-1 text-grey mb-8">
                            {{ result.is_passed ? 'Sınavı başarıyla geçtiniz.' : 'Geçme notunun altında kaldınız.' }}
                        </div>
                        
                        <VCard variant="outlined" class="mb-8 pa-4 border-dashed bg-grey-lighten-5">
                            <VRow>
                                <VCol cols="4" class="border-e">
                                    <div class="text-caption font-weight-bold text-grey">PUAN</div>
                                    <div class="text-h4 font-weight-black text-indigo">{{ result.score }}</div>
                                </VCol>
                                <VCol cols="4" class="border-e">
                                    <div class="text-caption font-weight-bold text-grey">DOĞRU</div>
                                    <div class="text-h4 font-weight-black text-success">{{ result.correct }}</div>
                                </VCol>
                                <VCol cols="4">
                                    <div class="text-caption font-weight-bold text-grey">YANLIŞ</div>
                                    <div class="text-h4 font-weight-black text-red">{{ result.total - result.correct }}</div>
                                </VCol>
                            </VRow>
                        </VCard>

                        <div v-if="result.is_passed">
                            <VBtn color="success" size="x-large" block class="mb-4 rounded-lg font-weight-bold elevation-4" @click="goToCertificates" prepend-icon="mdi-certificate">
                                SERTİFİKAYI AL
                            </VBtn>
                        </div>
                        <div v-else>
                            <VBtn color="warning" size="x-large" block class="mb-4 rounded-lg font-weight-bold" @click="router.visit(route('student.dashboard'))">
                                KURS LİSTESİNE DÖN
                            </VBtn>
                        </div>
                        
                        <div class="mt-4">
                            <a :href="route('student.course.show', quiz.course_id || '#')" class="text-decoration-none text-body-2 font-weight-bold text-grey-darken-1">
                                <VIcon icon="mdi-arrow-left" size="small"/> Kurs İçeriğine Dön
                            </a>
                        </div>
                    </VCard>
                </div>

                <VCard v-else class="rounded-xl border-t-lg border-indigo elevation-6">
                    <div class="pa-6 border-b d-flex align-center justify-space-between bg-white sticky-top z-10">
                        <div>
                            <h1 class="text-h6 font-weight-black text-slate-800">{{ quiz?.title }}</h1>
                            <div class="text-caption font-weight-bold text-grey">{{ questionList.length }} Soru</div>
                        </div>
                        <VChip :color="timeLeft < 60 ? 'red' : 'indigo'" variant="flat" size="large" class="font-weight-black">
                            {{ formatTime(timeLeft) }}
                        </VChip>
                    </div>

                    <VCardText class="pa-6 bg-grey-lighten-5">
                        <div v-for="(q, index) in questionList" :key="q.id" class="mb-8 pa-6 bg-white rounded-xl border question-card">
                            <div class="d-flex mb-6">
                                <VAvatar color="indigo-lighten-5" size="32" class="mr-4 text-subtitle-2 font-weight-black text-indigo">{{ index + 1 }}</VAvatar>
                                <div class="text-body-1 font-weight-bold text-slate-800">{{ q.question_text }}</div>
                            </div>

                            <VRadioGroup v-model="studentAnswers[q.id]" hide-details>
                                <div v-for="opt in ['a', 'b', 'c', 'd']" :key="opt" 
                                     v-show="q['option_' + opt]" 
                                     :class="['option-row mb-3', studentAnswers[q.id] === opt ? 'selected' : '']"
                                     @click="studentAnswers[q.id] = opt">
                                    
                                    <VRadio :value="opt" color="indigo" class="ma-0">
                                        <template #label>
                                            <span class="ml-2 text-body-2 font-weight-medium text-slate-700">
                                                <strong class="text-uppercase mr-2">{{ opt }}.</strong> {{ q['option_' + opt] }}
                                            </span>
                                        </template>
                                    </VRadio>
                                </div>
                            </VRadioGroup>
                        </div>

                        <VBtn color="indigo" block size="x-large" class="py-7 font-weight-black rounded-lg elevation-4" 
                               :loading="form.processing" @click="submitQuiz">
                            SINAVI BİTİR
                        </VBtn>
                    </VCardText>
                </VCard>

            </VCol>
        </VRow>
    </VContainer>
</template>

<style scoped>
.bg-slate-50 { background-color: #f8fafc; }
.border-dashed { border-style: dashed !important; border-width: 2px !important; }
.sticky-top { position: sticky; top: 0; z-index: 100; }
.z-10 { z-index: 10; }

.question-card { border-color: #e2e8f0; }
.option-row {
    border: 2px solid #f1f5f9;
    border-radius: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}
.option-row:hover { border-color: #cbd5e1; background: #f8fafc; }
.option-row.selected {
    border-color: #4f46e5;
    background-color: #eef2ff;
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.1);
}
</style>