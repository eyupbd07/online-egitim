<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AssignmentUpload from '@/Components/Student/AssignmentUpload.vue';

const props = defineProps({
    course: Object,
    activeLesson: Object, // Controller'dan gelebilir
    completedLessonIds: { type: Array, default: () => [] },
    submissions: { type: Array, default: () => [] }
});

// Güvenli İlk Ders Seçimi (Hata Önleyici)
const firstLesson = props.course?.lessons?.length > 0 ? props.course.lessons[0] : null;
const currentLesson = ref(props.activeLesson || firstLesson);
const activeTab = ref('content');

// Eğer currentLesson değişirse (örn: bir sonraki derse geçince) tab'i sıfırla
watch(currentLesson, () => {
    activeTab.value = 'content';
});

const changeLesson = (lesson) => {
    currentLesson.value = lesson;
};

// Video URL Hesaplayıcı
const videoUrl = computed(() => {
    if (!currentLesson.value?.video_url) return '';
    try {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = currentLesson.value.video_url.match(regExp);
        return (match && match[2].length === 11) ? `https://www.youtube.com/embed/${match[2]}` : '';
    } catch { return ''; }
});

const getSubmission = (assignmentId) => {
    return props.submissions?.find(s => s.assignment_id === assignmentId);
};
</script>

<template>
    <Head :title="course?.title || 'Kurs İzleme'" />

    <div class="d-flex flex-column flex-md-row h-screen overflow-hidden bg-grey-lighten-5 font-inter">
        
        <div class="flex-grow-1 overflow-y-auto d-flex flex-column relative scroll-smooth">
            
            <div class="bg-black video-wrapper elevation-4">
                <iframe v-if="videoUrl" :src="videoUrl" class="video-iframe" frameborder="0" allowfullscreen></iframe>
                <div v-else class="d-flex flex-column align-center justify-center h-100 text-grey-darken-1 bg-grey-darken-4">
                    <VIcon icon="mdi-play-network-outline" size="64" class="mb-4 opacity-50" />
                    <span class="text-subtitle-1 font-weight-medium">Bu ders için video içeriği bulunmuyor.</span>
                </div>
            </div>

            <div class="pa-6 pa-md-8 flex-grow-1 bg-white" style="min-height: 50vh;">
                <div v-if="currentLesson" class="max-w-4xl mx-auto">
                    
                    <div class="mb-8 border-b pb-4">
                        <div class="d-flex align-center gap-2 mb-2">
                             <VChip size="small" color="primary" variant="tonal" class="font-weight-bold">
                                Ders {{ course.lessons.findIndex(l => l.id === currentLesson.id) + 1 }}
                             </VChip>
                             <span class="text-caption text-medium-emphasis font-weight-bold text-uppercase tracking-wider">
                                {{ course.title }}
                             </span>
                        </div>
                        <h1 class="text-h4 font-weight-bold text-grey-darken-4 lh-tight">
                            {{ currentLesson.title }}
                        </h1>
                    </div>

                    <VTabs v-model="activeTab" color="primary" align-tabs="start" class="mb-6 border-b">
                        <VTab value="content" class="text-none font-weight-bold letter-spacing-0 px-0 mr-6" :ripple="false">
                            <VIcon start icon="mdi-text-box-outline" size="small" /> Ders Notları
                        </VTab>
                        <VTab value="assignments" class="text-none font-weight-bold letter-spacing-0 px-0 mr-6" :ripple="false">
                             <VIcon start icon="mdi-file-document-edit-outline" size="small" /> Ödevler
                             <VBadge v-if="course.assignments?.length" :content="course.assignments.length" color="error" inline class="ml-1" />
                        </VTab>
                        <VTab value="quizzes" class="text-none font-weight-bold letter-spacing-0 px-0" :ripple="false">
                             <VIcon start icon="mdi-head-question-outline" size="small" /> Sınavlar
                             <VBadge v-if="course.quizzes?.length" :content="course.quizzes.length" color="warning" inline class="ml-1" />
                        </VTab>
                    </VTabs>

                    <VWindow v-model="activeTab" class="py-2">
                        <VWindowItem value="content">
                            <div class="text-body-1 text-grey-darken-3 leading-loose course-content">
                                {{ currentLesson.content || 'Bu ders için henüz yazılı içerik eklenmemiş.' }}
                            </div>
                        </VWindowItem>

                        <VWindowItem value="assignments">
                            <div v-if="course.assignments?.length" class="d-flex flex-column gap-4">
                                <AssignmentUpload 
                                    v-for="assign in course.assignments" 
                                    :key="assign.id" 
                                    :assignment="assign" 
                                    :submission="getSubmission(assign.id)" 
                                />
                            </div>
                            <div v-else class="text-center py-10 bg-grey-lighten-5 rounded-lg border-dashed">
                                <VIcon icon="mdi-file-check-outline" size="48" color="grey-lighten-1" class="mb-2" />
                                <div class="text-body-2 text-medium-emphasis">Bu ders için ödev bulunmuyor.</div>
                            </div>
                        </VWindowItem>

                        <VWindowItem value="quizzes">
                            <div v-if="course.quizzes?.length" class="d-flex flex-column gap-3">
                                <VCard v-for="quiz in course.quizzes" :key="quiz.id" variant="outlined" class="pa-4 border bg-grey-lighten-5 hover:bg-white transition-all">
                                    <div class="d-flex justify-space-between align-center flex-wrap gap-3">
                                        <div class="d-flex align-center">
                                            <VAvatar color="warning" variant="tonal" class="mr-4" rounded>
                                                <VIcon icon="mdi-trophy-outline" />
                                            </VAvatar>
                                            <div>
                                                <div class="font-weight-bold text-subtitle-1 text-grey-darken-4">{{ quiz.title }}</div>
                                                <div class="text-caption text-medium-emphasis">
                                                    <VIcon icon="mdi-check-decagram" size="12" class="mr-1" />
                                                    Geçme Notu: %{{ quiz.passing_score }}
                                                </div>
                                            </div>
                                        </div>
                                        <VBtn :href="route('student.quizzes.show', quiz.id)" color="warning" variant="flat" class="text-capitalize font-weight-bold px-6">
                                            Sınava Başla
                                        </VBtn>
                                    </div>
                                </VCard>
                            </div>
                            <div v-else class="text-center py-10 bg-grey-lighten-5 rounded-lg border-dashed">
                                <VIcon icon="mdi-clipboard-text-outline" size="48" color="grey-lighten-1" class="mb-2" />
                                <div class="text-body-2 text-medium-emphasis">Bu ders için sınav bulunmuyor.</div>
                            </div>
                        </VWindowItem>
                    </VWindow>
                </div>

                <div v-else class="d-flex align-center justify-center h-100 text-medium-emphasis">
                    <div class="text-center">
                        <VIcon icon="mdi-alert-circle-outline" size="48" class="mb-2" />
                        <h3>İçerik yüklenemedi.</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar bg-white border-s overflow-y-auto d-flex flex-column elevation-1">
            <div class="pa-5 border-b bg-grey-lighten-5 sticky-top z-10">
                <h3 class="text-overline font-weight-bold text-grey-darken-1 mb-1">MÜFREDAT</h3>
                <div class="text-caption text-medium-emphasis">{{ completedLessonIds.length }} / {{ course.lessons.length }} Ders Tamamlandı</div>
                <VProgressLinear :model-value="(completedLessonIds.length / course.lessons.length) * 100" color="success" height="6" rounded class="mt-2" />
            </div>
            
            <VList bg-color="transparent" class="pa-2 flex-grow-1">
                <VListItem 
                    v-for="(lesson, i) in course.lessons" 
                    :key="lesson.id" 
                    @click="changeLesson(lesson)" 
                    :active="currentLesson?.id === lesson.id" 
                    rounded="lg" 
                    class="mb-1 lesson-item py-3" 
                    active-class="bg-primary-lighten-5 text-primary"
                    :ripple="false"
                >
                    <template v-slot:prepend>
                        <div class="lesson-number mr-3" :class="{ 'completed': completedLessonIds.includes(lesson.id) }">
                             <VIcon v-if="completedLessonIds.includes(lesson.id)" icon="mdi-check" size="14" />
                             <span v-else>{{ i + 1 }}</span>
                        </div>
                    </template>
                    
                    <VListItemTitle class="font-weight-medium text-body-2" :class="currentLesson?.id === lesson.id ? 'text-primary font-weight-bold' : 'text-grey-darken-3'">
                        {{ lesson.title }}
                    </VListItemTitle>
                    
                    <template v-slot:append>
                        <VIcon v-if="currentLesson?.id === lesson.id" icon="mdi-play-circle" color="primary" size="20" />
                    </template>
                </VListItem>
            </VList>
            
            <div class="pa-4 border-t bg-grey-lighten-5">
                 <Link :href="route('student.my-courses')">
                    <VBtn block variant="outlined" color="grey-darken-3" prepend-icon="mdi-arrow-left" class="text-capitalize font-weight-bold border-opacity-25">
                        Kurslardan Çık
                    </VBtn>
                 </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Google Font */
.font-inter { font-family: 'Inter', system-ui, -apple-system, sans-serif !important; }

/* Layout & Scroll */
.h-screen { height: 100vh; }
.sidebar { width: 350px; min-width: 350px; }
.scroll-smooth { scroll-behavior: smooth; }
.relative { position: relative; }
.sticky-top { position: sticky; top: 0; }
.z-10 { z-index: 10; }

/* Video Wrapper (16:9 Aspect Ratio) */
.video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; background: #000; box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
.video-iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }

/* Lesson Number Box */
.lesson-number { 
    width: 28px; height: 28px; 
    background: #f1f5f9; 
    border-radius: 8px; 
    display: flex; align-items: center; justify-content: center; 
    font-size: 0.75rem; font-weight: 700; color: #64748b; 
    transition: all 0.2s ease;
}
.lesson-item--active .lesson-number { background: rgba(var(--v-theme-primary), 0.1); color: rgb(var(--v-theme-primary)); }
.lesson-number.completed { background: #dcfce7; color: #166534; } /* Yeşil tik */

/* Typography & Spacing */
.lh-tight { line-height: 1.2; }
.leading-loose { line-height: 1.8; }
.tracking-wider { letter-spacing: 1px; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }
.gap-4 { gap: 16px; }

/* Utilities */
.border-dashed { border-style: dashed !important; border-color: #cbd5e1 !important; }
.hover\:bg-white:hover { background-color: #fff !important; }
.transition-all { transition: all 0.2s ease; }

/* Responsive Sidebar Gizleme (Opsiyonel: Mobil için eklenebilir) */
@media (max-width: 960px) {
    .d-flex.flex-md-row { flex-direction: column !important; }
    .sidebar { width: 100%; min-width: 100%; height: auto; max-height: 400px; }
}
</style>