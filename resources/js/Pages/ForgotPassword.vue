<script setup>
import { useTheme } from 'vuetify'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue'; 

import authV1MaskDark from '@images/pages/auth-v1-mask-dark.png'
import authV1MaskLight from '@images/pages/auth-v1-mask-light.png'
import authV1Tree2 from '@images/pages/auth-v1-tree-2.png'
import authV1Tree from '@images/pages/auth-v1-tree.png'

// Controller'dan 'status' (başarı mesajı) prop'unu alıyoruz
defineProps({
    status: String,
});

// Layout'u olmayan bir sayfa olarak tanımlıyoruz
defineOptions({ layout: null });

// Formu 'email' alanı ile başlatıyoruz
const form = useForm({
    email: '',
});

const vuetifyTheme = useTheme()

const authThemeMask = computed(() => {
  return vuetifyTheme.global.name.value === 'light' ? authV1MaskLight : authV1MaskDark
})

// Formu gönderme fonksiyonu
const submit = () => {
    // 'password.email' rotasına (routes/auth.php içinde) POST isteği atar
    form.post(route('password.email'));
};
</script>

<template>
  <Head title="Parolamı Unuttum" />

  <div class="auth-wrapper d-flex align-center justify-center pa-4 bg-grey-lighten-5 fill-height">
    
    <VCard
      class="auth-card pa-6 pa-md-8 rounded-xl"
      max-width="448"
      width="100%"
      elevation="0"
      border
      color="white"
    >
      <VCardItem class="justify-center pt-0 pb-6">
        <Link
          :href="route('login')"
          class="d-flex align-center justify-center gap-3 text-decoration-none"
        >
          <VAvatar color="primary" variant="tonal" rounded size="40">
            <VIcon icon="mdi-school-outline" size="24" />
          </VAvatar>
          <h2 class="font-weight-bold text-h5 text-primary mb-0" style="letter-spacing: -0.5px;">
            Online Eğitim
          </h2>
        </Link>
      </VCardItem>

      <VCardText class="pt-2 text-center">
        <h4 class="text-h5 font-weight-bold text-grey-darken-3 mb-2">
          Parolanızı mı unuttunuz? 🔒
        </h4>
        <p class="mb-0 text-body-2 text-medium-emphasis">
          Endişelenmeyin! E-posta adresinizi girin, size şifre sıfırlama talimatlarını gönderelim.
        </p>
      </VCardText>

      <VCardText>
        <VAlert
          v-if="status"
          type="success"
          variant="tonal"
          color="success"
          icon="mdi-check-circle-outline"
          class="mb-6 rounded-lg border-success text-body-2"
          closable
        >
          {{ status }}
        </VAlert>
        
        <VForm @submit.prevent="submit">
          <VRow>
            <VCol cols="12" class="mb-2">
              <div class="text-caption font-weight-bold mb-1 text-grey-darken-2">E-posta Adresi</div>
              <VTextField
                v-model="form.email"
                placeholder="ornek@email.com"
                type="email"
                autocomplete="email"
                autofocus
                variant="outlined"
                color="primary"
                density="comfortable"
                hide-details="auto"
                prepend-inner-icon="mdi-email-outline"
                class="rounded-lg"
                :error-messages="form.errors.email"
              />
            </VCol>

            <VCol cols="12">
              <VBtn
                block
                type="submit"
                color="primary"
                size="large"
                variant="flat"
                class="rounded-lg font-weight-bold text-capitalize"
                :loading="form.processing"
                :disabled="form.processing"
                elevation="2"
              >
                Sıfırlama Linki Gönder
              </VBtn>
            </VCol>

            <VCol
              cols="12"
              class="text-center mt-2"
            >
              <Link
                class="d-flex align-center justify-center text-grey-darken-2 font-weight-medium text-decoration-none hover-primary"
                :href="route('login')"
              >
                <VIcon
                  icon="mdi-chevron-left"
                  size="20"
                  class="me-1"
                />
                Giriş sayfasına dön
              </Link>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>

    <VImg
      class="auth-footer-start-tree d-none d-md-block position-absolute"
      :src="authV1Tree"
      :width="250"
      style="bottom: 0; left: 0; z-index: 0;"
    />

    <VImg
      :src="authV1Tree2"
      class="auth-footer-end-tree d-none d-md-block position-absolute"
      :width="350"
      style="bottom: 0; right: 0; z-index: 0;"
    />

    <VImg
      class="auth-footer-mask d-none d-md-block position-absolute"
      :src="authThemeMask"
      style="bottom: 0; width: 100%; z-index: 0; opacity: 0.5;"
    />
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";

/* SaaS stili: Login sayfasıyla aynı CSS */
.auth-wrapper {
  min-height: 100vh;
  position: relative;
  overflow: hidden;
}

.auth-card {
  z-index: 1; /* Kartı görsellerin önüne al */
  box-shadow: 0 4px 24px -4px rgba(0, 0, 0, 0.1) !important;
}

/* Link hover efekti */
.hover-primary {
  transition: color 0.2s ease;
  &:hover {
    color: rgb(var(--v-theme-primary)) !important;
  }
}

/* Alert Border Fix */
.border-success {
    border: 1px solid rgba(var(--v-theme-success), 0.2) !important;
}
</style>