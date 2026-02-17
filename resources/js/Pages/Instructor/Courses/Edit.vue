<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

// Layout Import
import DefaultLayout from '@/layouts/default.vue'

// Bileşen Importları
import LessonManager from '@/Components/Instructor/LessonManager.vue'
import QuizManager from '@/Components/Instructor/QuizManager.vue'
import AssignmentManager from '@/Components/Instructor/AssignmentManager.vue'

defineOptions({ layout: DefaultLayout })

const props = defineProps({
    course: Object
})

const form = useForm({
    title: props.course.title ?? '',
    description: props.course.description ?? '',
    price: props.course.price ?? '',
    image: null,
    _method: 'PUT'
})

const imagePreview = ref(props.course.image_url ?? null)

const handleImageUpload = (e) => {
    const file = e.target.files[0]
    if (!file) return
    form.image = file
    imagePreview.value = URL.createObjectURL(file)
}

const submit = () => {
    form.post(route('instructor.courses.update', props.course.id), {
        preserveScroll: true
    })
}
</script>

<template>
    <Head title="Kurs Düzenle" />

    <VContainer fluid class="custom-page-container pa-6">
        
        <div class="d-flex justify-space-between align-center mb-6">
            <div class="d-flex align-center">
                <VAvatar color="primary" variant="tonal" rounded class="mr-3">
                    <VIcon icon="mdi-notebook-edit-outline" />
                </VAvatar>
                <div>
                    <h1 class="text-h5 font-weight-bold text-grey-darken-3 mb-0">Kurs Düzenle</h1>
                    <div class="text-body-2 text-grey">{{ course.title }}</div>
                </div>
            </div>

            <Link :href="route('instructor.courses.index')">
                <VBtn variant="tonal" color="grey-darken-3" prepend-icon="mdi-arrow-left">
                    Listeye Dön
                </VBtn>
            </Link>
        </div>

        <VForm @submit.prevent="submit">
            <VRow>
                <VCol cols="12" lg="8">
                    
                    <VCard class="mb-6 rounded-lg border-opacity-50" elevation="1">
                        <VCardItem class="pa-4 border-b bg-grey-lighten-5">
                            <template v-slot:prepend>
                                <VIcon icon="mdi-information-outline" color="primary" />
                            </template>
                            <VCardTitle class="font-weight-bold text-body-1">Temel Bilgiler</VCardTitle>
                        </VCardItem>
                        <VCardText class="pa-6">
                            <VTextField
                                v-model="form.title"
                                label="Kurs Başlığı"
                                variant="outlined"
                                density="comfortable"
                                class="mb-4"
                                bg-color="white"
                            />
                            <VTextarea
                                v-model="form.description"
                                label="Açıklama"
                                variant="outlined"
                                rows="4"
                                auto-grow
                                bg-color="white"
                            />
                        </VCardText>
                    </VCard>

                    <div class="mb-6 global-override-zone">
                         <VCard class="rounded-lg border-opacity-50" elevation="1">
                            <VCardItem class="pa-4 border-b bg-grey-lighten-5">
                                <template v-slot:prepend>
                                    <VIcon icon="mdi-playlist-check" color="indigo" />
                                </template>
                                <VCardTitle class="font-weight-bold text-body-1">Müfredat</VCardTitle>
                            </VCardItem>
                            <VCardText class="pa-4">
                                <LessonManager
                                    :courseId="course.id"
                                    :lessons="course.lessons"
                                />
                            </VCardText>
                        </VCard>
                    </div>

                    <VRow>
                        <VCol cols="12" md="6">
                            <VCard class="h-100 rounded-lg border-opacity-50 global-override-zone" elevation="1">
                                <VCardItem class="pa-4 border-b bg-grey-lighten-5">
                                    <template v-slot:prepend>
                                        <VIcon icon="mdi-help-box-multiple-outline" color="purple" />
                                    </template>
                                    <VCardTitle class="font-weight-bold text-body-1">Sınavlar</VCardTitle>
                                </VCardItem>
                                <VCardText class="pa-4">
                                    <QuizManager
                                        :courseId="course.id"
                                        :quizzes="course.quizzes"
                                    />
                                </VCardText>
                            </VCard>
                        </VCol>

                        <VCol cols="12" md="6">
                            <VCard class="h-100 rounded-lg border-opacity-50 global-override-zone" elevation="1">
                                <VCardItem class="pa-4 border-b bg-grey-lighten-5">
                                    <template v-slot:prepend>
                                        <VIcon icon="mdi-file-document-edit-outline" color="teal" />
                                    </template>
                                    <VCardTitle class="font-weight-bold text-body-1">Ödevler</VCardTitle>
                                </VCardItem>
                                <VCardText class="pa-4">
                                    <AssignmentManager
                                        :courseId="course.id"
                                        :assignments="course.assignments"
                                    />
                                </VCardText>
                            </VCard>
                        </VCol>
                    </VRow>
                </VCol>

                <VCol cols="12" lg="4">
                    <VCard class="mb-6 rounded-lg border-opacity-50" elevation="1">
                        <VCardItem class="pa-4 border-b bg-grey-lighten-5">
                            <template v-slot:prepend>
                                <VIcon icon="mdi-image-outline" color="deep-orange" />
                            </template>
                            <VCardTitle class="font-weight-bold text-body-1">Kapak Görseli</VCardTitle>
                        </VCardItem>
                        <VCardText class="pa-4">
                            <div class="upload-area" :class="{ active: imagePreview }">
                                <img v-if="imagePreview" :src="imagePreview" />
                                <div v-else class="text-center">
                                    <VIcon icon="mdi-cloud-upload" size="40" color="grey" />
                                    <div class="mt-2 text-body-2 font-weight-bold">Görsel Yükle</div>
                                </div>
                                <input type="file" accept="image/*" @change="handleImageUpload" />
                            </div>
                        </VCardText>
                    </VCard>

                    <VCard class="rounded-lg border-opacity-50" elevation="1">
                        <VCardItem class="pa-4 border-b bg-grey-lighten-5">
                            <template v-slot:prepend>
                                <VIcon icon="mdi-cash" color="success" />
                            </template>
                            <VCardTitle class="font-weight-bold text-body-1">Fiyatlandırma</VCardTitle>
                        </VCardItem>
                        <VCardText class="pa-6">
                            <VTextField
                                v-model="form.price"
                                label="Satış Fiyatı"
                                prefix="₺"
                                type="number"
                                variant="outlined"
                                density="comfortable"
                                class="mb-4"
                                bg-color="white"
                            />
                            <VBtn
                                block
                                size="large"
                                color="primary"
                                :loading="form.processing"
                                @click="submit"
                                class="text-capitalize font-weight-bold"
                            >
                                <VIcon start icon="mdi-content-save-check" />
                                Kaydet ve Yayınla
                            </VBtn>
                        </VCardText>
                    </VCard>
                </VCol>
            </VRow>
        </VForm>
    </VContainer>
</template>

<style>
/* 1. Global Wrapper: Stillerin dışarı taşmasını engeller ama içeri sızar */
.global-override-zone {
    /* Buton container ayarları */
}

/* 2. Buton Kutusu (Zorla Görünür Yap) */
.global-override-zone .v-btn--icon {
    background-color: #F8FAFC !important; /* Açık Gri/Beyaz */
    border: 1px solid #E2E8F0 !important; /* İnce Çerçeve */
    border-radius: 8px !important;
    width: 36px !important;
    height: 36px !important;
    margin: 4px !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    opacity: 1 !important; /* Görünmezliği Kır */
    color: #475569 !important; /* Varsayılan İkon Rengi */
}

/* 3. Hover Efekti */
.global-override-zone .v-btn--icon:hover {
    background-color: #E2E8F0 !important;
    border-color: #94A3B8 !important;
}

/* 4. DÜZENLEME İKONLARI (MAVİ) - app.css'i ezer */
.global-override-zone .mdi-pencil,
.global-override-zone .mdi-pencil-outline,
.global-override-zone .mdi-pencil-box-outline,
.global-override-zone .mdi-file-document-edit,
.global-override-zone .mdi-file-document-edit-outline {
    color: #2563EB !important; /* Canlı Mavi */
    fill: #2563EB !important;
}

/* 5. SİLME İKONLARI (KIRMIZI) - app.css'i ezer */
.global-override-zone .mdi-delete,
.global-override-zone .mdi-trash-can,
.global-override-zone .mdi-trash-can-outline,
.global-override-zone .mdi-close,
.global-override-zone .mdi-close-circle {
    color: #DC2626 !important; /* Canlı Kırmızı */
    fill: #DC2626 !important;
}

/* Resim Yükleme Alanı */
.upload-area {
    height: 220px;
    border: 2px dashed #CBD5E1;
    background-color: #F8FAFC;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    cursor: pointer;
    transition: 0.3s;
}
.upload-area:hover {
    border-color: #3B82F6;
    background-color: #EFF6FF;
}
.upload-area img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
}
.upload-area input {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}
</style>