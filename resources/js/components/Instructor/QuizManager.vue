<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    courseId: Number,
    quizzes: {
        type: Array,
        default: () => []
    }
});

// --- URL YÖNLENDİRMELERİ ---

// 1. Yeni Sınav Ekleme Sayfasına Git
const goToCreateQuiz = () => {
    const url = `/instructor/quizzes/create?course_id=${props.courseId}`;
    router.visit(url);
};

// 2. Sınav Düzenleme Sayfasına Git
const goToEditQuiz = (quizId) => {
    const url = `/instructor/quizzes/${quizId}/edit`;
    router.visit(url);
};

// 3. Sınav Silme
const deleteQuiz = (quizId) => {
    if(confirm('Bu sınavı silmek istediğinize emin misiniz?')) {
        router.delete(`/instructor/quizzes/${quizId}`, {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <div class="border rounded pa-4 bg-deep-purple-lighten-5 mt-4">
        
        <div class="d-flex justify-space-between align-center mb-4">
            <h3 class="text-h6 font-weight-bold text-deep-purple-darken-3">
                <VIcon icon="mdi-playlist-check" class="mr-2"/> Sınavlar
            </h3>
            
            <VBtn 
                color="deep-purple-accent-4" 
                variant="elevated" 
                class="font-weight-bold elevation-2 text-white"
                @click="goToCreateQuiz"
            >
                <VIcon icon="mdi-plus" start /> Yeni Sınav Ekle
            </VBtn>
        </div>

        <VList v-if="quizzes && quizzes.length > 0" bg-color="transparent" class="pa-0">
            <VListItem v-for="(quiz, i) in quizzes" :key="quiz.id" class="bg-white mb-2 rounded border elevation-1">
                
                <template v-slot:prepend>
                    <VAvatar color="deep-purple-lighten-5" size="small" class="mr-3 text-deep-purple font-weight-bold border">
                        {{ i + 1 }}
                    </VAvatar>
                </template>
                
                <VListItemTitle class="font-weight-bold text-grey-darken-3">
                    {{ quiz.title }}
                </VListItemTitle>
                <VListItemSubtitle class="text-caption mt-1">
                    {{ quiz.questions_count || 0 }} Soru
                </VListItemSubtitle>
                
                <template v-slot:append>
                    <div class="d-flex align-center">
                        <VBtn 
                            icon="mdi-pencil-outline" 
                            variant="text" 
                            color="primary" 
                            size="small"
                            class="mr-1"
                            @click.stop="goToEditQuiz(quiz.id)"
                        />

                        <VBtn 
                            icon="mdi-trash-can-outline" 
                            variant="text" 
                            color="error" 
                            size="small"
                            @click.stop="deleteQuiz(quiz.id)"
                        />
                    </div>
                </template>
            </VListItem>
        </VList>

        <div v-else class="text-center text-body-2 text-grey py-6 bg-white rounded border border-dashed">
            <VIcon icon="mdi-folder-open-outline" size="large" class="mb-2 d-block mx-auto"/>
            Henüz sınav eklenmemiş. 
        </div>
    </div>
</template>