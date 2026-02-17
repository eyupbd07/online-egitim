<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/default.vue';

defineOptions({ layout: DefaultLayout });

// Props yapısı korundu
const props = defineProps({ lesson: Object, course_id: Number });

// Form yapısı korundu
const form = useForm({
    title: props.lesson.title,
    video_url: props.lesson.video_url,
    content: props.lesson.content,
    _method: 'PUT'
});

// Submit fonksiyonu korundu
const submit = () => {
    form.post(route('instructor.lessons.update', props.lesson.id));
};
</script>

<template>
    <Head title="Dersi Düzenle" />

    <VContainer fluid class="fill-height bg-grey-lighten-5 py-8 font-inter">
        <VRow justify="center">
            <VCol cols="12" md="8" lg="6">
                
                <div class="d-flex align-center justify-space-between mb-6">
                    <div class="d-flex align-center">
                        <Link :href="route('instructor.courses.edit', course_id)">
                            <VBtn
                                icon="mdi-arrow-left"
                                variant="outlined"
                                color="grey-darken-1"
                                size="small"
                                class="mr-4 bg-white border-opacity-50"
                            />
                        </Link>
                        <div>
                            <h1 class="text-h5 font-weight-bold text-grey-darken-3 lh-1">
                                Dersi Düzenle
                            </h1>
                            <div class="text-caption text-medium-emphasis mt-1">
                                Ders içeriğini, video bağlantısını ve açıklamaları güncelleyin.
                            </div>
                        </div>
                    </div>
                </div>

                <VCard class="rounded-xl border-none shadow-soft overflow-hidden" color="white">
                    
                    <div class="px-6 py-4 bg-grey-lighten-5 border-b d-flex align-center gap-3">
                        <VIcon icon="mdi-notebook-edit-outline" color="primary" />
                        <span class="text-subtitle-2 font-weight-bold text-grey-darken-3">Ders Detayları</span>
                    </div>

                    <VForm @submit.prevent="submit">
                        <VCardText class="pa-6">
                            <VRow dense>
                                
                                <VCol cols="12" class="mb-4">
                                    <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Ders Başlığı</label>
                                    <VTextField 
                                        v-model="form.title" 
                                        placeholder="Örn: Vue.js Kurulumu" 
                                        variant="outlined" 
                                        color="primary"
                                        density="comfortable"
                                        bg-color="grey-lighten-5"
                                        class="rounded-lg"
                                        hide-details="auto"
                                        :error-messages="form.errors.title"
                                    >
                                        <template #prepend-inner>
                                            <VIcon icon="mdi-format-title" color="grey-darken-1" size="small" class="mr-1 opacity-70"/>
                                        </template>
                                    </VTextField>
                                </VCol>

                                <VCol cols="12" class="mb-4">
                                    <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Video Bağlantısı</label>
                                    <VTextField 
                                        v-model="form.video_url" 
                                        placeholder="https://youtube.com/..." 
                                        variant="outlined" 
                                        color="primary"
                                        density="comfortable"
                                        bg-color="grey-lighten-5"
                                        class="rounded-lg"
                                        hide-details="auto"
                                        :error-messages="form.errors.video_url"
                                    >
                                        <template #prepend-inner>
                                            <VIcon icon="mdi-youtube" color="red" size="small" class="mr-1"/>
                                        </template>
                                    </VTextField>
                                    <div class="text-caption text-medium-emphasis mt-1 ml-1">
                                        * YouTube veya Vimeo embed bağlantısını giriniz.
                                    </div>
                                </VCol>

                                <VCol cols="12" class="mb-2">
                                    <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Ders İçeriği / Notlar</label>
                                    <VTextarea 
                                        v-model="form.content" 
                                        placeholder="Ders hakkında detaylı açıklama..." 
                                        variant="outlined" 
                                        color="primary"
                                        rows="6"
                                        auto-grow
                                        density="comfortable"
                                        bg-color="grey-lighten-5"
                                        class="rounded-lg"
                                        hide-details="auto"
                                        :error-messages="form.errors.content"
                                    />
                                </VCol>

                            </VRow>
                        </VCardText>

                        <VDivider class="border-dashed" />

                        <VCardActions class="px-6 py-4 bg-grey-lighten-5 d-flex justify-end gap-3">
                            <Link :href="route('instructor.courses.edit', course_id)">
                                <VBtn
                                    variant="text"
                                    color="grey-darken-2"
                                    class="text-capitalize font-weight-medium rounded-lg"
                                    height="44"
                                >
                                    Vazgeç
                                </VBtn>
                            </Link>

                            <VBtn 
                                type="submit" 
                                color="primary" 
                                variant="flat" 
                                class="text-capitalize font-weight-bold rounded-lg shadow-accent px-6"
                                height="44"
                                :loading="form.processing"
                            >
                                <VIcon start icon="mdi-content-save-check-outline" class="mr-1"/>
                                Değişiklikleri Kaydet
                            </VBtn>
                        </VCardActions>
                    </VForm>
                </VCard>

            </VCol>
        </VRow>
    </VContainer>
</template>

<style scoped>
/* Modern Font */
.font-inter { font-family: 'Inter', system-ui, -apple-system, sans-serif !important; }

/* Yumuşak Gölgeler */
.shadow-soft { box-shadow: 0 12px 32px -4px rgba(16, 24, 40, 0.08), 0 4px 12px -2px rgba(16, 24, 40, 0.03) !important; }
.shadow-accent { box-shadow: 0 4px 12px rgba(var(--v-theme-primary), 0.3) !important; }

/* Tipografi Yardımcıları */
.lh-1 { line-height: 1.2; }
.gap-3 { gap: 12px; }

/* Çizgiler */
.border-dashed { border-style: dashed !important; opacity: 0.6; }

/* Input İçi */
:deep(.v-field__input) {
    font-size: 0.95rem;
}
</style>