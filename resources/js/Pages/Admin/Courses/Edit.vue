<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

// Controller'dan gönderilen veriler
const props = defineProps({
  course: Object,
  instructors: Array,
});

// useForm ile mevcut kurs verilerini dolduruyoruz
const form = useForm({
  _method: 'PUT',
  title: props.course?.title || '',
  description: props.course?.description || '',
  youtube_url: props.course?.youtube_url || '',
  instructor_id: props.course?.instructor_id || null,
});

// Formu gönderecek olan fonksiyon
const submit = () => {
  if (!props.course?.id) return;
  const updateUrl = `/admin/courses/${props.course.id}`;
  form.post(updateUrl);
};

// Eğitmen listesini computed ile hazırlama
// Bu yöntem, VSelect'in veriyi daha stabil okumasını sağlar.
const formattedInstructors = computed(() => {
  return (props.instructors || []).map(i => ({
    title: i.name,
    value: i.id,
  }));
});
</script>

<template>
  <Head :title="`Kurs Düzenle: ${course?.title || ''}`" />

  <VContainer fluid class="fill-height bg-grey-lighten-5 py-8 font-inter">
    <VRow justify="center">
      <VCol cols="12" md="10" lg="8">
        
        <div class="d-flex align-center justify-space-between mb-6">
          <div class="d-flex align-center">
             <VBtn
                :component="Link"
                :href="route('admin.courses.index')"
                variant="outlined"
                color="grey-darken-1"
                size="small"
                class="rounded-lg border-opacity-50 bg-white mr-4"
                icon="mdi-arrow-left"
             />
             <div>
                <h1 class="text-subtitle-1 font-weight-bold text-grey-darken-4 lh-1">
                   Kurs Yönetimi
                </h1>
                <div class="d-flex align-center text-caption text-medium-emphasis mt-1">
                   Düzenle <VIcon icon="mdi-chevron-right" size="14" class="mx-1"/> {{ course?.title }}
                </div>
             </div>
          </div>
        </div>

        <VCard class="rounded-xl border-none shadow-soft overflow-hidden" color="white">
          
          <div class="px-8 py-6 bg-grey-lighten-5 border-b d-flex align-center">
            <VAvatar color="primary" variant="flat" size="56" class="mr-5 shadow-accent">
              <VIcon icon="mdi-notebook-edit-outline" color="white" size="28" />
            </VAvatar>
            <div>
              <div class="text-h6 font-weight-bold text-grey-darken-4">
                {{ course?.title || 'Kurs Başlığı Yükleniyor...' }}
              </div>
              <div class="text-body-2 text-medium-emphasis">
                İçerik, açıklama ve eğitmen bilgilerini buradan güncelleyebilirsiniz.
              </div>
            </div>
          </div>

          <VForm @submit.prevent="submit">
            <VCardText class="px-8 py-8">
              <VRow dense>
                
                <VCol cols="12" class="mb-3">
                  <div class="d-flex align-center gap-2">
                    <VIcon icon="mdi-information-outline" color="primary" size="20"/>
                    <span class="text-subtitle-2 font-weight-bold text-grey-darken-3">Kurs Detayları</span>
                  </div>
                </VCol>

                <VCol cols="12" class="mb-4">
                  <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Kurs Adı</label>
                  <VTextField
                    v-model="form.title"
                    placeholder="Örn: Sıfırdan İleri Seviye Vue.js"
                    variant="outlined"
                    color="primary"
                    density="comfortable"
                    class="rounded-lg font-size-14"
                    bg-color="grey-lighten-5"
                    hide-details="auto"
                    :error-messages="form.errors.title"
                  >
                    <template #prepend-inner>
                        <VIcon icon="mdi-format-title" color="grey-darken-1" size="20" class="mr-1 opacity-70"/>
                    </template>
                  </VTextField>
                </VCol>

                <VCol cols="12" class="mb-6">
                  <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Açıklama</label>
                  <VTextarea
                    v-model="form.description"
                    placeholder="Kurs hakkında kısa bir özet..."
                    rows="4"
                    variant="outlined"
                    color="primary"
                    density="comfortable"
                    class="rounded-lg font-size-14"
                    bg-color="grey-lighten-5"
                    hide-details="auto"
                    :error-messages="form.errors.description"
                  />
                </VCol>

                <VCol cols="12">
                   <VDivider class="border-dashed mb-6"/>
                </VCol>

                <VCol cols="12" class="mb-3">
                  <div class="d-flex align-center gap-2">
                    <VIcon icon="mdi-video-account" color="primary" size="20"/>
                    <span class="text-subtitle-2 font-weight-bold text-grey-darken-3">Medya & Eğitmen</span>
                  </div>
                </VCol>

                <VCol cols="12" md="6" class="mb-4">
                  <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Tanıtım Videosu (YouTube URL)</label>
                  <VTextField
                    v-model="form.youtube_url"
                    placeholder="https://youtube.com/watch?v=..."
                    variant="outlined"
                    color="primary"
                    density="comfortable"
                    class="rounded-lg font-size-14"
                    bg-color="grey-lighten-5"
                    hide-details="auto"
                    :error-messages="form.errors.youtube_url"
                  >
                    <template #prepend-inner>
                        <VIcon icon="mdi-youtube" color="red" size="20" class="mr-1"/>
                    </template>
                  </VTextField>
                </VCol>

                <VCol cols="12" md="6">
                  <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Atanan Eğitmen</label>
                  
                  <VSelect
                    v-model="form.instructor_id"
                    :items="formattedInstructors"
                    item-title="title"
                    item-value="value"
                    placeholder="Eğitmen Seçiniz"
                    
                    variant="outlined"
                    color="primary"
                    density="comfortable"
                    bg-color="grey-lighten-5"
                    class="rounded-lg font-size-14 saas-select"
                    hide-details="auto"
                    menu-icon="mdi-chevron-down"
                    :error-messages="form.errors.instructor_id"
                    
                    autocomplete="off"
                    name="course_instructor_selector_x"
                  >
                    <template #prepend-inner>
                        <VIcon icon="mdi-account-tie" color="grey-darken-1" size="20" class="mr-1 opacity-70"/>
                    </template>
                    
                    <template #selection="{ item }">
                         <span class="text-body-2 font-weight-medium text-grey-darken-3">
                            {{ item.title }}
                         </span>
                    </template>
                  </VSelect>
                  
                  <div class="text-caption text-medium-emphasis mt-2 ml-1">
                    <VIcon icon="mdi-alert-circle-outline" size="14" class="mr-1" />
                    Bu işlem, kurs yönetim panelinin sahibini değiştirir.
                  </div>
                </VCol>

              </VRow>
            </VCardText>

            <div class="px-8 py-6 bg-grey-lighten-5 border-t d-flex align-center justify-end gap-3">
              <VBtn
                :component="Link"
                :href="route('admin.courses.index')"
                variant="text"
                color="grey-darken-2"
                class="text-capitalize font-weight-medium rounded-lg"
                height="44"
              >
                Vazgeç
              </VBtn>

              <VBtn
                type="submit"
                color="primary"
                variant="flat"
                class="text-capitalize font-weight-bold rounded-lg shadow-accent px-6"
                height="44"
                :loading="form.processing"
                :disabled="form.processing"
              >
                <VIcon start icon="mdi-content-save-check-outline" class="mr-1"/>
                Değişiklikleri Kaydet
              </VBtn>
            </div>
          </VForm>
        </VCard>

      </VCol>
    </VRow>
  </VContainer>
</template>

<style scoped>
/* Google Font: Inter (SaaS Standardı) */
.font-inter { font-family: 'Inter', system-ui, -apple-system, sans-serif !important; }

/* Tipografi ve Aralıklar */
.lh-1 { line-height: 1; }
.font-size-14 { font-size: 0.92rem !important; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }

/* Gölgeler */
.shadow-soft { box-shadow: 0 12px 32px -4px rgba(16, 24, 40, 0.08), 0 4px 12px -2px rgba(16, 24, 40, 0.03) !important; }
.shadow-accent { box-shadow: 0 4px 12px rgba(var(--v-theme-primary), 0.3) !important; }

/* Çizgiler */
.border-dashed { border-style: dashed !important; opacity: 0.4; }

/* --- CRITICAL FIX: INPUT GİZLEME (Yazı Çakışması İçin) --- */
/* Input yüksekliğini sabitler ve içindeki yazıyı ortalar */
.saas-select :deep(.v-field__input) {
  min-height: 44px;
  display: flex;
  align-items: center;
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}

/* Tarayıcı Autofill'den gelen yazıları görünmez yapar */
.saas-select :deep(input) {
    opacity: 0 !important; 
}
</style>