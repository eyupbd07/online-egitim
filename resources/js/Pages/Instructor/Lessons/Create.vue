<script setup>
import { Head, useForm, router } from '@inertiajs/vue3'

const props = defineProps({ course: Object })

const form = useForm({
    title: '',
    video_url: '',
    content: ''
})

const submit = () => {
    // Kurs ID'sini parametre olarak gönderiyoruz
    form.post(route('instructor.lessons.store', { course: props.course.id }));
}
</script>

<template>
    <Head title="Yeni Ders Ekle" />
    <VContainer class="py-10">
        <VRow justify="center">
            <VCol cols="12" md="8">
                <VBtn variant="text" prepend-icon="ri-arrow-left-line" @click="router.visit(route('instructor.courses.edit', course.id))" class="mb-4">
                    Geri Dön
                </VBtn>
                
                <VCard elevation="3" border>
                    <VCardTitle class="bg-primary text-white pa-4">Yeni Ders Oluştur</VCardTitle>
                    <VCardText class="pa-6">
                        <form @submit.prevent="submit">
                            <VTextField v-model="form.title" label="Ders Başlığı" variant="outlined" class="mb-4" :error-messages="form.errors.title" />
                            <VTextField v-model="form.video_url" label="YouTube URL" variant="outlined" class="mb-4" />
                            <VTextarea v-model="form.content" label="Ders İçeriği" variant="outlined" rows="5" />
                            <VBtn color="primary" block size="large" type="submit" :loading="form.processing">Dersi Kaydet</VBtn>
                        </form>
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>
    </VContainer>
</template>