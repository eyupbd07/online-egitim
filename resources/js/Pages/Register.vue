<script setup>
import { useTheme } from 'vuetify'
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue'; // ref eklendi

import authV1MaskDark from '@images/pages/auth-v1-mask-dark.png'
import authV1MaskLight from '@images/pages/auth-v1-mask-light.png'
import authV1Tree2 from '@images/pages/auth-v1-tree-2.png'
import authV1Tree from '@images/pages/auth-v1-tree.png'

defineOptions({ layout: null });

// Formu, Laravel Breeze'in beklediği alanlarla başlatıyoruz
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'student', // Varsayılan rol
  privacyPolicies: false,
})

const vuetifyTheme = useTheme()

const authThemeMask = computed(() => {
  return vuetifyTheme.global.name.value === 'light' ? authV1MaskLight : authV1MaskDark
})

const isPasswordVisible = ref(false)
const isConfirmPasswordVisible = ref(false)

// Formu gönderme fonksiyonu
const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head title="Kayıt Ol" />

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
          Macera Başlıyor 🚀
        </h4>
        <p class="mb-0 text-body-2 text-medium-emphasis">
          Öğrenme yolculuğunuza başlamak için hemen kaydolun!
        </p>
      </VCardText>

      <VCardText>
        <VForm @submit.prevent="submit">
          <VRow>
            <VCol cols="12" class="mb-1">
              <div class="text-caption font-weight-bold mb-1 text-grey-darken-2">İsim Soyisim</div>
              <VTextField
                v-model="form.name"
                placeholder="Adınız Soyadınız"
                variant="outlined"
                color="primary"
                density="comfortable"
                hide-details="auto"
                prepend-inner-icon="mdi-account-outline"
                class="rounded-lg"
                :error-messages="form.errors.name"
              />
            </VCol>
            
            <VCol cols="12" class="mb-1">
              <div class="text-caption font-weight-bold mb-1 text-grey-darken-2">E-posta Adresi</div>
              <VTextField
                v-model="form.email"
                placeholder="ornek@email.com"
                type="email"
                variant="outlined"
                color="primary"
                density="comfortable"
                hide-details="auto"
                prepend-inner-icon="mdi-email-outline"
                class="rounded-lg"
                :error-messages="form.errors.email"
              />
            </VCol>

            <VCol cols="12" class="mb-1">
              <div class="text-caption font-weight-bold mb-1 text-grey-darken-2">Parola</div>
              <VTextField
                v-model="form.password"
                placeholder="············"
                :type="isPasswordVisible ? 'text' : 'password'"
                autocomplete="new-password"
                variant="outlined"
                color="primary"
                density="comfortable"
                hide-details="auto"
                prepend-inner-icon="mdi-lock-outline"
                :append-inner-icon="isPasswordVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                @click:append-inner="isPasswordVisible = !isPasswordVisible"
                class="rounded-lg"
                :error-messages="form.errors.password"
              />
            </VCol>
            
            <VCol cols="12">
              <div class="text-caption font-weight-bold mb-1 text-grey-darken-2">Parola (Tekrar)</div>
              <VTextField
                v-model="form.password_confirmation"
                placeholder="············"
                :type="isConfirmPasswordVisible ? 'text' : 'password'"
                autocomplete="new-password"
                variant="outlined"
                color="primary"
                density="comfortable"
                hide-details="auto"
                prepend-inner-icon="mdi-lock-check-outline"
                :append-inner-icon="isConfirmPasswordVisible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                @click:append-inner="isConfirmPasswordVisible = !isConfirmPasswordVisible"
                class="rounded-lg"
                :error-messages="form.errors.password_confirmation"
              />
              
              <div class="d-flex align-center mt-4 mb-6">
                <VCheckbox
                  id="privacy-policy"
                  v-model="form.privacyPolicies"
                  :error-messages="form.errors.privacyPolicies"
                  hide-details
                  density="compact"
                  color="primary"
                  class="me-2"
                />
                <VLabel
                  for="privacy-policy"
                  style="opacity: 1;"
                  class="text-body-2"
                >
                  <span class="me-1">Kullanım koşullarını</span>
                  <a
                    href="javascript:void(0)"
                    class="text-primary font-weight-bold text-decoration-none hover-underline"
                  >kabul ediyorum</a>
                </VLabel>
              </div>

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
                Kayıt Ol
              </VBtn>
            </VCol>

            <VCol
              cols="12"
              class="text-center text-body-2 mt-2"
            >
              <span class="text-medium-emphasis">Zaten bir hesabınız var mı?</span>
              <Link
                class="text-primary font-weight-bold ms-2 text-decoration-none hover-underline"
                :href="route('login')"
              >
                Giriş yapın
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

/* SaaS stili */
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
.hover-underline:hover {
  text-decoration: underline !important;
}
</style>