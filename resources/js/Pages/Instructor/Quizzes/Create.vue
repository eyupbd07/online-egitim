<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
  course: { type: Object, default: () => ({ id: null, title: 'Yükleniyor...' }) },
  course_id: [String, Number]
});

const form = useForm({
  title: '',
  description: '',
  course_id: props.course?.id || props.course_id, 
  passing_score: 70,
  questions: [{ question_text: '', options: ['', ''], correct_option_index: 0 }]
});

const addQuestion = () => form.questions.push({ question_text: '', options: ['', ''], correct_option_index: 0 });
const removeQuestion = (index) => form.questions.length > 1 && form.questions.splice(index, 1);
const addOption = (qIdx) => form.questions[qIdx].options.push('');
const removeOption = (qIdx, oIdx) => form.questions[qIdx].options.length > 2 && form.questions[qIdx].options.splice(oIdx, 1);

const submit = () => {
  form.post(route('instructor.quizzes.store', form.course_id), { preserveScroll: true });
};
</script>

<template>
  <Head title="Sınav Stüdyosu" />
  <VContainer fluid class="bg-slate-50 min-h-screen pa-0 pb-16">
    
    <div class="sticky-header bg-white border-b px-8 py-4 d-flex align-center justify-space-between">
      <div class="d-flex align-center">
        <VBtn icon="mdi-close" variant="text" color="slate-400" :href="route('instructor.courses.edit', form.course_id)" class="mr-4" />
        <div class="v-divider-vertical mx-4"></div>
        <div>
          <h1 class="text-subtitle-1 font-weight-black text-slate-900 leading-none">Yeni Sınav Oluştur</h1>
          <span class="text-caption text-indigo-600 font-weight-medium">{{ course.title }}</span>
        </div>
      </div>
      <div class="d-flex align-center gap-3">
        <VBtn variant="tonal" color="success" size="large" :loading="form.processing" prepend-icon="mdi-check-all" class="rounded-lg px-8 font-weight-black text-none" @click="submit">
          Sınavı Yayınla
        </VBtn>
      </div>
    </div>

    <VRow justify="center" class="ma-0 pt-10">
      <VCol cols="12" md="10" lg="8" xl="6">
        
        <VForm @submit.prevent="submit">
          <section class="mb-10">
            <div class="d-flex align-center mb-4">
              <VIcon icon="mdi-tune-variant" color="slate-400" class="mr-2" size="20" />
              <span class="text-overline font-weight-black text-slate-500">SINAV KONFİGÜRASYONU</span>
            </div>
            <VCard variant="flat" border class="rounded-xl pa-8 bg-white shadow-sm">
              <VRow gutter="24">
                <VCol cols="12" md="9">
                  <label class="custom-label">Sınav Başlığı</label>
                  <VTextField v-model="form.title" variant="outlined" placeholder="Örn: Modül 1 Değerlendirme Testi" class="mt-1 modern-input" hide-details="auto" prepend-inner-icon="mdi-format-title" :error-messages="form.errors.title" />
                </VCol>
                <VCol cols="12" md="3">
                  <label class="custom-label">Geçme Notu</label>
                  <VTextField v-model="form.passing_score" type="number" variant="outlined" suffix="%" class="mt-1 modern-input" hide-details="auto" prepend-inner-icon="mdi-medal-outline" />
                </VCol>
                <VCol cols="12">
                  <label class="custom-label">Öğrenci Talimatları</label>
                  <VTextarea v-model="form.description" variant="outlined" rows="2" placeholder="Sınav kurallarını buraya yazın..." class="mt-1 modern-input" hide-details="auto" auto-grow />
                </VCol>
              </VRow>
            </VCard>
          </section>

          <div class="d-flex align-center mb-4">
            <VIcon icon="mdi-help-circle-outline" color="slate-400" class="mr-2" size="20" />
            <span class="text-overline font-weight-black text-slate-500">SORU BANKASI ({{ form.questions.length }})</span>
          </div>

          <div v-for="(question, qIndex) in form.questions" :key="qIndex" class="mb-6">
            <VCard variant="flat" border class="rounded-xl overflow-hidden shadow-sm bg-white">
              <div class="px-6 py-4 bg-slate-50 border-b d-flex justify-space-between align-center">
                <div class="d-flex align-center">
                  <span class="question-number mr-3">{{ qIndex + 1 }}</span>
                  <span class="text-body-2 font-weight-bold text-slate-600">Soru İçeriği</span>
                </div>
                <VBtn v-if="form.questions.length > 1" icon="mdi-trash-can-outline" size="small" variant="text" color="red-lighten-1" @click="removeQuestion(qIndex)" />
              </div>
              
              <VCardText class="pa-8">
                <VTextarea v-model="question.question_text" placeholder="Sorunuzu buraya yazın..." variant="outlined" rows="3" class="modern-input mb-8" hide-details="auto" :error-messages="form.errors[`questions.${qIndex}.question_text`]" />
                
                <div class="options-container">
                  <VRadioGroup v-model="question.correct_option_index" hide-details>
                    <div v-for="(option, oIndex) in question.options" :key="oIndex" 
                         class="option-row mb-3" 
                         :class="{'is-correct': question.correct_option_index === oIndex}">
                      
                      <div class="d-flex align-center px-4 py-2 w-100">
                        <VRadio :value="oIndex" color="success" class="mr-2" />
                        <VTextField v-model="question.options[oIndex]" :placeholder="`Seçenek ${String.fromCharCode(65 + oIndex)}`" variant="plain" hide-details class="flex-grow-1 pt-0 mt-0" />
                        <VBtn v-if="question.options.length > 2" icon="mdi-close" size="x-small" variant="text" color="slate-300" @click="removeOption(qIndex, oIndex)" />
                      </div>
                    </div>
                  </VRadioGroup>

                  <VBtn variant="text" color="indigo-600" size="small" prepend-icon="mdi-plus" class="text-none font-weight-bold mt-2" @click="addOption(qIndex)">
                    Seçenek Ekle
                  </VBtn>
                </div>
              </VCardText>
            </VCard>
          </div>

          <VBtn variant="outlined" block color="slate-300" size="large" class="dashed-add-btn rounded-xl py-8 h-auto mb-10" @click="addQuestion">
            <div class="d-flex flex-column align-center">
              <VIcon icon="mdi-plus-circle" color="indigo-600" size="32" class="mb-2" />
              <span class="text-body-1 font-weight-bold text-slate-700 text-none">Yeni Soru Ekle</span>
            </div>
          </VBtn>

        </VForm>
      </VCol>
    </VRow>
  </VContainer>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:deep(*) { font-family: 'Plus Jakarta Sans', sans-serif !important; }

.bg-slate-50 { background-color: #f8fafc; }
.text-slate-900 { color: #0f172a; }
.text-slate-500 { color: #64748b; }
.text-slate-700 { color: #334155; }

.sticky-header { position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); }

.v-divider-vertical { width: 1px; height: 24px; background-color: #e2e8f0; }

.custom-label { font-size: 0.75rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; display: block; }

.question-number { width: 24px; height: 24px; background: #4f46e5; color: white; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 0.7rem; font-weight: 900; }

.option-row { border: 1px solid #e2e8f0; border-radius: 12px; transition: all 0.2s ease; background: white; }
.option-row:hover { border-color: #cbd5e1; }
.option-row.is-correct { border-color: #22c55e !important; background-color: #f0fdf4 !important; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1); }

.dashed-add-btn { border-style: dashed !important; border-width: 2px !important; background-color: transparent !important; }
.dashed-add-btn:hover { border-color: #4f46e5 !important; background-color: #f5f3ff !important; }

:deep(.v-field__outline) { --v-field-border-opacity: 0.1 !important; }
:deep(.v-field--focused .v-field__outline) { color: #4f46e5 !important; --v-field-border-opacity: 1 !important; }
:deep(.v-field__input) { font-size: 0.9rem; font-weight: 500; }
</style>