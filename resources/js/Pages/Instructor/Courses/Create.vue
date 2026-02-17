<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  title: '',
  description: '',
  youtube_url: '',   
});

const submit = () => {
  form.post(route('instructor.courses.store'));
};
</script>

<template>
  <Head title="Yeni Kurs Oluştur" />
  <VContainer class="py-10 px-6 bg-slate-50 min-h-screen" fluid>
    <header class="mb-10 text-center">
        <v-avatar color="primary-lighten-5" size="64" class="mb-4">
            <v-icon icon="mdi-video-plus-outline" color="primary" size="32" />
        </v-avatar>
        <h1 class="text-h3 font-weight-black text-slate-900">İçerik Stüdyosu</h1>
        <p class="text-subtitle-1 text-slate-500 mt-2">YouTube linkinizi ekleyin, kapak resmini biz otomatik hazırlayalım.</p>
    </header>

    <VRow justify="center">
      <VCol cols="12" lg="7">
        <VCard elevation="0" border class="rounded-xl overflow-hidden bg-white shadow-sm">
          <v-toolbar color="primary" flat>
            <v-toolbar-title class="font-weight-bold ml-4 text-white">Yeni Kurs Detayları</v-toolbar-title>
          </v-toolbar>

          <VForm @submit.prevent="submit" class="pa-8">
            <VRow>
              <VCol cols="12">
                <label class="text-caption font-weight-black text-slate-400 mb-1 d-block text-uppercase">Kurs Başlığı</label>
                <VTextField
                  v-model="form.title"
                  placeholder="Örn: Sıfırdan İleri Seviye Python Eğitimi"
                  variant="outlined"
                  class="modern-input"
                  :error-messages="form.errors.title"
                />
              </VCol>

              <VCol cols="12">
                <label class="text-caption font-weight-black text-slate-400 mb-1 d-block text-uppercase">Kurs Hakkında</label>
                <VTextarea
                  v-model="form.description"
                  placeholder="Bu kursta neler anlatacaksınız?"
                  variant="outlined"
                  rows="4"
                  class="modern-input"
                  :error-messages="form.errors.description"
                />
              </VCol>

              <VCol cols="12">
                <label class="text-caption font-weight-black text-slate-400 mb-1 d-block text-uppercase">Tanıtım Videosu (YouTube URL)</label>
                <VTextField
                  v-model="form.youtube_url"
                  placeholder="https://www.youtube.com/watch?v=..."
                  variant="outlined"
                  class="modern-input"
                  prepend-inner-icon="mdi-youtube"
                  color="red"
                  :error-messages="form.errors.youtube_url"
                />
                <div class="mt-4 d-flex align-center bg-blue-50 pa-4 rounded-lg border border-dashed border-blue-200">
                    <v-icon icon="mdi-auto-fix" size="24" color="blue" class="mr-3" />
                    <span class="text-body-2 text-blue-700 font-weight-bold">
                        Akıllı Özellik: Kapak resmi bu videodan otomatik olarak çekilip veritabanına kaydedilecektir.
                    </span>
                </div>
              </VCol>
            </VRow>

            <div class="mt-10 d-flex justify-space-between align-center border-t pt-8">
              <v-btn variant="text" color="slate-400" :href="route('instructor.courses.index')" class="px-6 rounded-lg font-weight-bold">VAZGEÇ</v-btn>
              <VBtn
                type="submit"
                color="primary"
                size="large"
                elevation="0"
                class="rounded-xl px-10 font-weight-black text-none"
                :loading="form.processing"
              >
                KURSUNU YAYINLA
              </VBtn>
            </div>
          </VForm>
        </VCard>
      </VCol>
    </VRow>
  </VContainer>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
:deep(*) { font-family: 'Plus Jakarta Sans', sans-serif !important; }
.bg-slate-50 { background-color: #f8fafc; }
.text-slate-900 { color: #0f172a; }
.text-slate-500 { color: #64748b; }
.text-slate-400 { color: #94a3b8; }
.modern-input :deep(.v-field__outline) { --v-field-border-opacity: 0.1 !important; }
.modern-input :deep(.v-field--focused .v-field__outline) { color: #6366f1 !important; --v-field-border-opacity: 1 !important; }
</style>