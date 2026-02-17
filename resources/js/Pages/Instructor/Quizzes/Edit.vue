<script setup>
import { useForm, Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import DefaultLayout from '@/layouts/default.vue';

defineOptions({ layout: DefaultLayout });

const props = defineProps({
    quiz: Object,
    questions: { type: Array, default: () => [] },
    course_id: Number
});

const snackbar = ref({ show: false, text: '', color: 'success' });
const questionDialog = ref(false);
const currentEditingId = ref(null);

// 1. Quiz Bilgileri Formu (405 ve 403 hatalarını çözen yapı)
const quizForm = useForm({
    title: props.quiz?.title || '',
    description: props.quiz?.description || '',
});

// 2. Soru Formu
const questionForm = useForm({
    question_text: '',
    option_a: '',
    option_b: '',
    option_c: '',
    option_d: '',
    correct_answer: 'a'
});

const notify = (text, color = 'success') => {
    snackbar.value = { show: true, text, color };
};

// --- Quiz Güncelleme ---
const updateQuiz = () => {
    // web.php'deki 'quizzes.update' rotasına PUT isteği gönderir
    quizForm.put(route('instructor.quizzes.update', props.quiz.id), {
        onSuccess: () => notify('Sınav bilgileri başarıyla güncellendi.'),
        onError: (errors) => {
            console.error(errors);
            notify('Güncelleme sırasında bir hata oluştu.', 'error');
        },
        preserveScroll: true
    });
};

// --- Soru Yönetimi ---
const openQuestionModal = (question = null) => {
    if (question) {
        currentEditingId.value = question.id;
        questionForm.question_text = question.question_text;
        questionForm.option_a = question.option_a;
        questionForm.option_b = question.option_b;
        questionForm.option_c = question.option_c;
        questionForm.option_d = question.option_d;
        questionForm.correct_answer = question.correct_option || 'a';
    } else {
        currentEditingId.value = null;
        questionForm.reset();
    }
    questionDialog.value = true;
};

const saveQuestion = () => {
    const isUpdate = !!currentEditingId.value;
    
    // URL Yapısı web.php'deki 'questions.update' ve 'questions.store' ile tam eşleşir
    const targetRoute = isUpdate 
        ? route('instructor.questions.update', currentEditingId.value)
        : route('instructor.questions.store', props.quiz.id);

    const method = isUpdate ? 'put' : 'post';

    questionForm[method](targetRoute, {
        onSuccess: () => {
            questionDialog.value = false;
            questionForm.reset();
            notify(isUpdate ? 'Soru güncellendi.' : 'Yeni soru eklendi.');
            // F5 yapmaya gerek kalmadan verileri tazeler
            router.reload({ only: ['questions'] });
        },
        onError: () => notify('Lütfen tüm alanları doldurun.', 'error'),
        preserveScroll: true
    });
};

const deleteQuestion = (id) => {
    if (confirm('Bu soruyu silmek istediğinize emin misiniz?')) {
        router.delete(route('instructor.questions.destroy', id), {
            onSuccess: () => {
                notify('Soru silindi.', 'warning');
                router.reload({ only: ['questions'] });
            },
            preserveScroll: true
        });
    }
};

const questionCount = computed(() => props.questions?.length || 0);

// Props değiştiğinde formu senkronize et
watch(() => props.quiz, (newVal) => {
    if (newVal) {
        quizForm.title = newVal.title;
        quizForm.description = newVal.description;
    }
}, { deep: true });
</script>

<template>
    <Head title="Sınav Düzenle" />
    
    <div style="background-color: #f3f4f6; min-height: 100vh; color: #111827;">
        <VContainer fluid class="pa-6">
            <div class="d-flex align-center mb-6">
                <Link :href="route('instructor.courses.edit', course_id)" preserve-scroll>
                    <VBtn icon="mdi-arrow-left" variant="outlined" class="mr-4" />
                </Link>
                <h1 class="text-h5 font-weight-bold">Sınav Düzenle</h1>
                <VSpacer />
                <VChip color="primary" variant="flat">Quiz ID: {{ quiz?.id }}</VChip>
            </div>

            <VRow>
                <VCol cols="12" md="4">
                    <VCard class="pa-4 elevation-2">
                        <VCardTitle class="px-0 font-weight-bold mb-4">Genel Bilgiler</VCardTitle>
                        <form @submit.prevent="updateQuiz">
                            <VTextField
                                v-model="quizForm.title"
                                label="Sınav Başlığı"
                                variant="outlined"
                                class="mb-4"
                                :error-messages="quizForm.errors.title"
                            />
                            <VTextarea
                                v-model="quizForm.description"
                                label="Açıklama"
                                variant="outlined"
                                rows="4"
                                class="mb-4"
                                :error-messages="quizForm.errors.description"
                            />
                            <VBtn 
                                type="submit" 
                                color="primary" 
                                block 
                                :loading="quizForm.processing"
                            >
                                <VIcon icon="mdi-content-save" class="mr-2" />
                                Bilgileri Güncelle
                            </VBtn>
                        </form>
                    </VCard>
                </VCol>

                <VCol cols="12" md="8">
                    <VCard class="pa-4 elevation-2">
                        <div class="d-flex justify-space-between align-center mb-4">
                            <VCardTitle class="px-0 font-weight-bold">Sorular ({{ questionCount }})</VCardTitle>
                            <VBtn color="success" prepend-icon="mdi-plus" @click="openQuestionModal()">Soru Ekle</VBtn>
                        </div>

                        <VExpansionPanels v-if="questions.length > 0" variant="accordion">
                            <VExpansionPanel v-for="(q, index) in questions" :key="q.id" class="mb-2 border">
                                <VExpansionPanelTitle>
                                    <VChip size="x-small" color="primary" class="mr-3">{{ index + 1 }}</VChip>
                                    <span class="text-truncate" style="max-width: 400px;">{{ q.question_text }}</span>
                                    <VSpacer />
                                    <VChip size="small" color="success" variant="tonal">Cevap: {{ q.correct_option?.toUpperCase() }}</VChip>
                                </VExpansionPanelTitle>
                                <VExpansionPanelText>
                                    <VRow dense class="mb-4">
                                        <VCol v-for="opt in ['a','b','c','d']" :key="opt" cols="12" sm="6">
                                            <div :class="['pa-2 rounded border', q.correct_option === opt ? 'bg-green-lighten-5 border-success' : '']">
                                                <strong>{{ opt.toUpperCase() }}:</strong> {{ q['option_' + opt] }}
                                            </div>
                                        </VCol>
                                    </VRow>
                                    <div class="d-flex justify-end gap-2">
                                        <VBtn size="small" color="primary" variant="text" @click="openQuestionModal(q)">Düzenle</VBtn>
                                        <VBtn size="small" color="error" variant="text" @click="deleteQuestion(q.id)">Sil</VBtn>
                                    </div>
                                </VExpansionPanelText>
                            </VExpansionPanel>
                        </VExpansionPanels>
                        <div v-else class="text-center py-10 border-dashed rounded text-grey">Henüz soru eklenmemiş.</div>
                    </VCard>
                </VCol>
            </VRow>
        </VContainer>

        <VDialog v-model="questionDialog" max-width="600px" persistent>
            <VCard>
                <VToolbar color="primary" class="px-4">
                    <span class="text-h6 text-white">{{ currentEditingId ? 'Soruyu Düzenle' : 'Yeni Soru Ekle' }}</span>
                    <VSpacer />
                    <VBtn icon="mdi-close" color="white" variant="text" @click="questionDialog = false" />
                </VToolbar>
                <VCardText class="pa-6">
                    <VTextarea 
                        v-model="questionForm.question_text" 
                        label="Soru Metni" 
                        variant="outlined" 
                        rows="3" 
                        class="mb-4"
                        :error-messages="questionForm.errors.question_text"
                    />
                    <VRow dense>
                        <VCol v-for="opt in ['a','b','c','d']" :key="opt" cols="12" sm="6">
                            <VTextField 
                                v-model="questionForm['option_' + opt]" 
                                :label="opt.toUpperCase() + ' Şıkkı'" 
                                variant="outlined" 
                                density="compact"
                                :error-messages="questionForm.errors['option_' + opt]"
                            />
                        </VCol>
                    </VRow>
                    <VSelect 
                        v-model="questionForm.correct_answer" 
                        :items="['a','b','c','d']" 
                        label="Doğru Cevap" 
                        variant="outlined" 
                        class="mt-4"
                    />
                </VCardText>
                <VCardActions class="pa-4">
                    <VSpacer />
                    <VBtn variant="text" @click="questionDialog = false">İptal</VBtn>
                    <VBtn 
                        color="primary" 
                        variant="elevated" 
                        @click.stop.prevent="saveQuestion" 
                        :loading="questionForm.processing"
                    >
                        {{ currentEditingId ? 'Güncelle' : 'Kaydet' }}
                    </VBtn>
                </VCardActions>
            </VCard>
        </VDialog>

        <VSnackbar v-model="snackbar.show" :color="snackbar.color" location="top right" timeout="2500">
            {{ snackbar.text }}
        </VSnackbar>
    </div>
</template>