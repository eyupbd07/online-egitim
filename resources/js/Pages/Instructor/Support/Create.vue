<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

// Formu başlatıyoruz (Logic korundu)
const form = useForm({
  subject: '',
  message: '',
});

// Formu gönderme fonksiyonu (Logic korundu)
const submit = () => {
  form.post(route('instructor.support.store'));
};
</script>

<template>
  <Head title="Yeni Destek Talebi" />

  <VContainer class="fill-height py-10" fluid>
    <VRow justify="center">
      <VCol cols="12" md="8" lg="6">
        
        <div class="d-flex align-center mb-6">
          <VBtn
            :component="Link"
            :href="route('instructor.support.index')"
            icon="mdi-arrow-left"
            variant="text"
            color="grey-darken-1"
            class="mr-3"
          />
          <div>
            <h1 class="text-h5 font-weight-bold text-high-emphasis">Destek Talebi Oluştur</h1>
            <p class="text-body-2 text-medium-emphasis mb-0">
              Sorunlarınızı çözmek için buradayız.
            </p>
          </div>
        </div>

        <VCard class="rounded-xl border" elevation="0">
          <VAlert
            color="primary"
            variant="tonal"
            icon="mdi-information-slab-circle-outline"
            class="rounded-t-xl rounded-b-0 border-b"
            density="compact"
          >
            <span class="text-caption text-primary font-weight-medium">
              Destek ekibimiz genellikle 24 saat içinde yanıt verir.
            </span>
          </VAlert>

          <VForm @submit.prevent="submit" class="pa-6 pa-md-8">
            <VRow>
              <VCol cols="12">
                <label class="text-caption font-weight-bold text-medium-emphasis mb-1 d-block">
                  KONU BAŞLIĞI
                </label>
                <VTextField
                  v-model="form.subject"
                  placeholder="Örn: Video yükleme hatası #404"
                  :error-messages="form.errors.subject"
                  variant="outlined"
                  density="comfortable"
                  color="primary"
                  bg-color="grey-lighten-5"
                  prepend-inner-icon="mdi-format-title"
                  hide-details="auto"
                  class="mb-4"
                  rounded="lg"
                />
              </VCol>

              <VCol cols="12">
                <label class="text-caption font-weight-bold text-medium-emphasis mb-1 d-block">
                  DETAYLI AÇIKLAMA
                </label>
                <VTextarea
                  v-model="form.message"
                  placeholder="Yaşadığınız sorunu detaylıca anlatın..."
                  :error-messages="form.errors.message"
                  variant="outlined"
                  density="comfortable"
                  color="primary"
                  bg-color="grey-lighten-5"
                  prepend-inner-icon="mdi-text-box-outline"
                  rows="8"
                  auto-grow
                  hide-details="auto"
                  rounded="lg"
                />
                <div class="d-flex justify-end mt-2">
                  <span class="text-caption text-disabled">
                    Lütfen hata kodlarını veya ekran görüntülerini açıklamaya ekleyin.
                  </span>
                </div>
              </VCol>
            </VRow>

            <VDivider class="my-6 border-opacity-15" />

            <div class="d-flex justify-end align-center gap-4">
              <VBtn
                :component="Link"
                :href="route('instructor.support.index')"
                variant="text"
                color="grey-darken-1"
                class="text-capitalize font-weight-regular px-4"
                rounded="lg"
              >
                Vazgeç
              </VBtn>

              <VBtn
                type="submit"
                color="primary"
                variant="flat"
                size="large"
                :loading="form.processing"
                :disabled="form.processing"
                class="text-capitalize font-weight-bold px-6"
                rounded="lg"
                elevation="1"
                prepend-icon="mdi-send-outline"
              >
                Talebi Gönder
              </VBtn>
            </div>
          </VForm>
        </VCard>

      </VCol>
    </VRow>
  </VContainer>
</template>

<style scoped>
/* Küçük boşluk ayarları */
.gap-4 {
  gap: 16px;
}
</style>