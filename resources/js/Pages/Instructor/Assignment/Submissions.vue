<script setup>
import { Head, Link } from '@inertiajs/vue3';

// Controller'dan gelen veriler
defineProps({
    assignment: Object,
    submissions: Array
});

// Dosya indirme bağlantısını oluşturur
const getFileUrl = (path) => {
    return path ? `/storage/${path}` : '#';
};
</script>

<template>
    <Head :title="`${assignment.title} - Gelen Ödevler`" />

    <VContainer fluid class="bg-grey-lighten-5 min-h-screen pa-8">
        
        <div class="d-flex align-center justify-space-between mb-8">
            <div class="d-flex align-center">
                <Link :href="route('instructor.courses.edit', assignment.course_id)">
                    <VBtn icon="mdi-arrow-left" variant="text" color="grey-darken-2" class="mr-4" />
                </Link>
                <div>
                    <h1 class="text-h5 font-weight-black text-grey-darken-3">{{ assignment.title }}</h1>
                    <div class="text-subtitle-2 text-indigo font-weight-bold">
                        {{ assignment.course.title }} &bull; Toplam {{ submissions.length }} Teslim
                    </div>
                </div>
            </div>
        </div>

        <VCard border flat class="rounded-xl">
            <VTable class="submission-table">
                <thead class="bg-grey-lighten-4">
                    <tr>
                        <th class="text-left font-weight-bold pl-6 text-grey-darken-2">ÖĞRENCİ</th>
                        <th class="text-left font-weight-bold text-grey-darken-2">TESLİM TARİHİ</th>
                        <th class="text-left font-weight-bold text-grey-darken-2">ÖĞRENCİ NOTU</th>
                        <th class="text-right font-weight-bold pr-6 text-grey-darken-2">DOSYA</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sub in submissions" :key="sub.id" class="submission-row">
                        <td class="pl-6 py-4">
                            <div class="d-flex align-center">
                                <VAvatar color="indigo-lighten-4" size="40" class="mr-3 font-weight-bold text-indigo-darken-3">
                                    {{ sub.user.name.charAt(0).toUpperCase() }}
                                </VAvatar>
                                <div>
                                    <div class="font-weight-bold text-body-2">{{ sub.user.name }}</div>
                                    <div class="text-caption text-grey">{{ sub.user.email }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="text-body-2 text-grey-darken-2">
                            {{ new Date(sub.created_at).toLocaleDateString('tr-TR') }}
                            <span class="text-caption text-grey ml-1">
                                {{ new Date(sub.created_at).toLocaleTimeString('tr-TR', {hour: '2-digit', minute:'2-digit'}) }}
                            </span>
                        </td>

                        <td class="text-body-2 text-grey-darken-3" style="max-width: 300px;">
                            <div v-if="sub.student_note" class="d-flex align-start">
                                <VIcon icon="mdi-message-text-outline" size="small" color="grey" class="mr-2 mt-1" />
                                <span class="text-truncate-2">{{ sub.student_note }}</span>
                            </div>
                            <span v-else class="text-caption text-grey-lighten-1 italic">Not eklenmemiş</span>
                        </td>

                        <td class="text-right pr-6">
                            <VBtn 
                                :href="getFileUrl(sub.file_path)" 
                                target="_blank"
                                prepend-icon="mdi-download" 
                                color="indigo" 
                                variant="tonal" 
                                size="small" 
                                class="font-weight-bold text-none rounded-lg"
                            >
                                İndir
                            </VBtn>
                        </td>
                    </tr>

                    <tr v-if="submissions.length === 0">
                        <td colspan="4" class="text-center py-10">
                            <VIcon icon="mdi-folder-open-outline" size="48" color="grey-lighten-2" class="mb-2" />
                            <div class="text-grey text-body-1">Henüz hiç kimse ödev yüklemedi.</div>
                        </td>
                    </tr>
                </tbody>
            </VTable>
        </VCard>
    </VContainer>
</template>

<style scoped>
.submission-table th { height: 56px !important; letter-spacing: 0.5px; font-size: 0.75rem; }
.submission-row:hover { background-color: #f8fafc; }
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.italic { font-style: italic; }
.min-h-screen { min-height: 100vh; }
</style>