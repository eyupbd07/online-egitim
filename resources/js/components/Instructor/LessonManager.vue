<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    courseId: Number,
    lessons: {
        type: Array,
        default: () => []
    }
});

const showForm = ref(false);

// DÜZELTME BURADA: 'description' yerine 'content' yaptık.
const form = useForm({
    title: '',
    video_url: '',
    content: '' 
});

const createLesson = () => {
    form.post(route('instructor.lessons.store', props.courseId), {
        onSuccess: () => { form.reset(); showForm.value = false; }
    });
};

const editLesson = (id) => {
    // Ders düzenleme sayfasına yönlendirme
    router.visit(`/instructor/lessons/${id}/edit`);
};

const deleteLesson = async (id) => {
    if(!id) { alert("Hata: Ders ID bulunamadı!"); return; }

    if(confirm('Bu dersi silmek istediğinize emin misiniz?')) {
        try {
            const url = `/instructor/lessons/${id}`;
            await axios.delete(url);
            router.reload({ only: ['lessons'] });
        } catch (error) {
            console.error("Silme Hatası Detayı:", error);
            router.reload();
        }
    }
};
</script>

<template>
    <div class="border rounded pa-4 bg-indigo-lighten-5 mt-4">
        
        <div class="d-flex justify-space-between align-center mb-4">
            <h3 class="text-h6 font-weight-bold text-indigo-darken-3">
                <VIcon icon="mdi-playlist-play" class="mr-2"/> Ders İçerikleri
            </h3>
            
            <VBtn 
                color="indigo-darken-1" 
                variant="elevated" 
                class="font-weight-bold elevation-2 text-white"
                @click="showForm = !showForm"
            >
                <VIcon :icon="showForm ? 'mdi-close' : 'mdi-plus'" start/>
                {{ showForm ? 'İptal' : 'Yeni Ders Ekle' }}
            </VBtn>
        </div>

        <VExpandTransition>
            <div v-if="showForm" class="bg-white pa-4 rounded-lg mb-4 border elevation-1">
                <VTextField 
                    v-model="form.title" 
                    label="Ders Başlığı" 
                    variant="outlined" 
                    density="compact" 
                    class="mb-2" 
                    bg-color="white"
                ></VTextField>
                
                <VTextField 
                    v-model="form.video_url" 
                    label="Video Linki" 
                    variant="outlined" 
                    density="compact" 
                    class="mb-2"
                    bg-color="white"
                ></VTextField>
                
                <VTextarea 
                    v-model="form.content" 
                    label="Açıklama (İçerik)" 
                    variant="outlined" 
                    rows="2" 
                    class="mb-2"
                    bg-color="white"
                ></VTextarea>
                
                <VBtn 
                    block 
                    color="indigo" 
                    @click="createLesson" 
                    :loading="form.processing"
                    class="text-white"
                >
                    Kaydet
                </VBtn>
            </div>
        </VExpandTransition>

        <VList lines="one" bg-color="transparent" v-if="lessons && lessons.length > 0" class="pa-0">
            <VListItem v-for="(lesson, index) in lessons" :key="lesson.id" class="bg-white mb-2 rounded border elevation-1">
                
                <template v-slot:prepend>
                    <VAvatar color="indigo-lighten-5" size="small" class="mr-3 text-indigo font-weight-bold border">
                        {{ index + 1 }}
                    </VAvatar>
                </template>
                
                <VListItemTitle class="font-weight-bold text-grey-darken-3">
                    {{ lesson.title }}
                </VListItemTitle>
                
                <template v-slot:append>
                    <div class="d-flex align-center">
                        <VBtn 
                            icon="mdi-pencil-outline" 
                            variant="text" 
                            color="primary" 
                            size="small"
                            class="mr-1"
                            @click.stop="editLesson(lesson.id)"
                        />

                        <VBtn 
                            icon="mdi-trash-can-outline" 
                            variant="text" 
                            color="error" 
                            size="small"
                            @click.stop="deleteLesson(lesson.id)"
                        />
                    </div>
                </template>
            </VListItem>
        </VList>
        
        <div v-else class="text-center text-body-2 text-grey py-6 bg-white rounded border border-dashed">
            <VIcon icon="mdi-video-off-outline" size="large" class="mb-2 d-block mx-auto"/>
            Henüz ders eklenmemiş.
        </div>
    </div>
</template>