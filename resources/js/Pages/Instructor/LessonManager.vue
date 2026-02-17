<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    courseId: Number,
    lessons: Array
});

const showForm = ref(false);
const form = useForm({
    title: '',
    video_url: '',
    description: ''
});

const createLesson = () => {
    form.post(route('instructor.lessons.store', props.courseId), {
        onSuccess: () => {
            form.reset();
            showForm.value = false;
            // Sayfa yenilendiğinde yeni ders listede görünür
        }
    });
};

const deleteLesson = (id) => {
    if(confirm('Bu dersi silmek istediğinize emin misiniz?')) {
        useForm({}).delete(route('instructor.lessons.destroy', id));
    }
};
</script>

<template>
    <div class="mt-6 border rounded pa-4 bg-white">
        <div class="d-flex justify-space-between align-center mb-4">
            <h3 class="text-h6 font-weight-bold">🎬 Ders İçerikleri</h3>
            <VBtn size="small" color="secondary" @click="showForm = !showForm">
                <VIcon :icon="showForm ? 'ri-close-line' : 'ri-add-line'" start/>
                {{ showForm ? 'İptal' : 'Yeni Ders Ekle' }}
            </VBtn>
        </div>

        <div v-if="showForm" class="mb-4 pa-4 bg-grey-lighten-4 rounded border">
            <VTextField v-model="form.title" label="Ders Başlığı" variant="outlined" density="compact" class="mb-2" />
            <VTextField v-model="form.video_url" label="Video Linki (Youtube)" variant="outlined" density="compact" class="mb-2" />
            <VTextarea v-model="form.description" label="Ders Açıklaması" variant="outlined" rows="2" class="mb-2" />
            <VBtn block color="success" @click="createLesson" :loading="form.processing">Kaydet</VBtn>
        </div>

        <VList v-if="lessons && lessons.length > 0" density="compact" class="border rounded">
            <VListItem v-for="(lesson, i) in lessons" :key="lesson.id" lines="one">
                <template v-slot:prepend>
                    <VAvatar color="grey-lighten-2" size="small" class="mr-3 text-subtitle-2 font-weight-bold">{{ i+1 }}</VAvatar>
                </template>
                <VListItemTitle class="font-weight-medium">{{ lesson.title }}</VListItemTitle>
                <template v-slot:append>
                    <VBtn icon="ri-delete-bin-line" color="error" variant="text" size="small" @click="deleteLesson(lesson.id)" />
                </template>
            </VListItem>
        </VList>
        <div v-else class="text-center text-grey py-4">
            Henüz bu kursa ait bir ders eklenmemiş.
        </div>
    </div>
</template>