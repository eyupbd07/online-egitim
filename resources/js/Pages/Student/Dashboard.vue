<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,    // Enrolled, completed, total_lessons, certificates
    myCourses: Array,  // Öğrencinin aktif kursları
    allCourses: Array  // Keşfetmesi için önerilen tüm kurslar
});

/**
 * YouTube linkinden kapak resmi çeker.
 */
const getCourseCover = (course) => {
    if (course.youtube_url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = course.youtube_url.match(regExp);
        if (match && match[2].length === 11) {
            return `https://img.youtube.com/vi/${match[2]}/maxresdefault.jpg`;
        }
    }
    return `https://picsum.photos/seed/${course.id}/600/400`; 
};

const goToCourse = (slug) => {
    router.visit(route('student.course.show', slug));
};
</script>

<template>
    <Head title="Öğrenci Paneli" />

    <VContainer class="py-8 px-6" fluid>
        
        <header class="mb-10 d-flex flex-wrap justify-space-between align-center gap-4">
            <div>
                <h1 class="text-h4 font-weight-black text-slate-900 d-flex align-center">
                    Merhaba, <span class="text-gradient mx-2">{{ $page.props.auth.user.name }}</span> 👋
                </h1>
                <p class="text-body-1 text-slate-500 font-weight-medium">Bugün yeni bir şeyler öğrenmek için harika bir gün.</p>
            </div>
            <VBtn :to="route('student.courses.index')" color="primary" elevation="0" class="rounded-lg font-weight-bold px-6 text-none" size="large">
                <VIcon start icon="mdi-magnify" class="mr-2" /> Kurs Kataloğunu Aç
            </VBtn>
        </header>

        <VRow class="mb-12">
            <VCol v-for="(stat, index) in [
                { label: 'Kayıtlı Kurslar', value: stats.enrolled, icon: 'mdi-book-open-page-variant', color: '#6366F1', bg: '#EEF2FF' },
                { label: 'Ders İlerlemesi', value: stats.completed, total: stats.total_lessons, icon: 'mdi-progress-check', color: '#10B981', bg: '#ECFDF5' },
                { label: 'Sertifikalarım', value: stats.certificates, icon: 'mdi-medal', color: '#F59E0B', bg: '#FFFBEB', link: 'student.certificates.index' }
            ]" :key="index" cols="12" md="4">
                <VCard class="stat-card-mini" elevation="0" border>
                    <div class="pa-5 d-flex align-center">
                        <div class="stat-icon-box mr-4" :style="{ backgroundColor: stat.bg, color: stat.color }">
                            <VIcon :icon="stat.icon" size="28" />
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-caption font-weight-bold text-slate-400 text-uppercase letter-spacing-1">
                                {{ stat.label }}
                            </div>
                            <div class="text-h4 font-weight-black text-slate-800 d-flex align-baseline">
                                {{ stat.value }}
                                <span v-if="stat.total" class="text-subtitle-2 text-slate-400 ml-1 font-weight-medium">
                                    / {{ stat.total }}
                                </span>
                            </div>
                        </div>
                        <VBtn v-if="stat.link && stat.value > 0" :href="route(stat.link)" icon="mdi-chevron-right" variant="tonal" :color="stat.color" size="x-small" />
                    </div>
                </VCard>
            </VCol>
        </VRow>

        <section class="mb-14">
            <div class="d-flex align-center justify-space-between mb-6">
                <h2 class="text-h5 font-weight-bold text-slate-800 d-flex align-center">
                    <span class="section-indicator mr-3"></span> Öğrenmeye Devam Et
                </h2>
                <Link :href="route('student.my-courses')" class="text-primary text-decoration-none font-weight-bold">
                    Tüm Kurslarım <VIcon icon="mdi-arrow-right" size="small" />
                </Link>
            </div>

            <VRow v-if="myCourses.length > 0">
                <VCol v-for="course in myCourses" :key="course.id" cols="12" md="6" lg="4">
                    <VCard class="modern-course-card" elevation="0" border @click="goToCourse(course.slug)">
                        <div class="position-relative">
                            <VImg :src="getCourseCover(course)" height="200" cover class="rounded-t-xl" />
                            <div class="instructor-badge">{{ course.instructor_name }}</div>
                        </div>
                        <VCardText class="pa-5">
                            <h3 class="text-h6 font-weight-bold text-slate-800 line-clamp-1 mb-4">{{ course.title }}</h3>
                            <div class="d-flex align-center justify-space-between mb-2">
                                <span class="text-caption font-weight-bold text-slate-400">İlerleme</span>
                                <span class="text-caption font-weight-black text-primary">45%</span>
                            </div>
                            <VProgressLinear model-value="45" color="primary" height="8" rounded />
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>
            <VAlert v-else variant="tonal" color="primary" icon="mdi-information-outline" class="rounded-xl border-dashed">
                Henüz aktif bir kursunuz bulunmuyor. Keşfet kısmından bir kursa katılabilirsiniz.
            </VAlert>
        </section>

        <section>
            <div class="d-flex align-center justify-space-between mb-6">
                <h2 class="text-h5 font-weight-bold text-slate-800 d-flex align-center">
                    <span class="section-indicator bg-teal mr-3"></span> Yeni Kurslar Keşfet
                </h2>
            </div>
            <VRow>
                <VCol v-for="course in allCourses" :key="course.id" cols="12" sm="6" md="4" lg="3">
                    <VCard class="explore-card-minimal" elevation="0" border @click="router.visit(route('student.courses.index'))">
                        <VImg :src="getCourseCover(course)" height="140" cover class="rounded-lg mb-3" />
                        <VCardItem class="pa-0">
                            <h4 class="text-body-1 font-weight-bold text-slate-800 line-clamp-1 mb-1">
                                {{ course.title }}
                            </h4>
                            <div class="d-flex align-center text-caption text-slate-400">
                                <VIcon icon="mdi-account-outline" size="14" class="mr-1" /> {{ course.instructor_name }}
                            </div>
                            <div class="mt-3 d-flex justify-space-between align-center">
                                <span class="text-caption font-weight-bold text-success">Ücretsiz / Pro</span>
                                <VBtn variant="text" color="primary" density="compact" class="text-none font-weight-bold">İncele</VBtn>
                            </div>
                        </VCardItem>
                    </VCard>
                </VCol>
            </VRow>
        </section>

    </VContainer>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

/* GENEL STİL */
:deep(*) {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
}

.text-slate-900 { color: #0f172a; }
.text-slate-800 { color: #1e293b; }
.text-slate-500 { color: #64748b; }
.text-slate-400 { color: #94a3b8; }

.text-gradient {
    background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* STAT KARTLARI */
.stat-card-mini {
    background: #ffffff;
    border-radius: 20px !important;
    border-color: #f1f5f9 !important;
    transition: all 0.3s ease;
}
.stat-card-mini:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.08) !important;
}
.stat-icon-box {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* KURS KARTLARI */
.modern-course-card {
    border-radius: 24px !important;
    border-color: #f1f5f9 !important;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}
.modern-course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
}
.instructor-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    padding: 4px 12px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 800;
    color: #6366f1;
}

/* KEŞFET KARTLARI */
.explore-card-minimal {
    padding: 12px;
    border-radius: 20px !important;
    border-color: #f1f5f9 !important;
    transition: all 0.2s ease;
    cursor: pointer;
}
.explore-card-minimal:hover {
    border-color: #6366f1 !important;
    background: #fcfcff;
}

/* DEKORASYON */
.section-indicator {
    width: 6px;
    height: 24px;
    background: #6366f1;
    border-radius: 4px;
}
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.letter-spacing-1 { letter-spacing: 0.5px; }
</style>