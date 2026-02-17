<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

// Controller'dan gönderilen props
const props = defineProps({
  user: Object,
  roles: Array,
});

// Form başlatma
const form = useForm({
  _method: 'PUT',
  name: props.user?.name || '',
  email: props.user?.email || '',
  role: props.user?.role || '',
});

// Rol Türkçeleştirme
const roleTranslations = {
  admin: 'Yönetici',
  instructor: 'Eğitmen',
  student: 'Öğrenci',
};

// VSelect Veri Hazırlığı
const formattedRoles = computed(() => {
  return props.roles.map(role => ({
    title: roleTranslations[role] || role, // Ekranda görünecek (Türkçe)
    value: role,                           // Veritabanına gidecek (İngilizce)
  }));
});

// Gönderme Fonksiyonu
const submit = () => {
  form.post(route('admin.users.update', props.user.id));
};
</script>

<template>
  <Head :title="`Kullanıcı Düzenle: ${user?.name || ''}`" />

  <VContainer fluid class="fill-height bg-grey-lighten-5 py-8 font-inter">
    <VRow justify="center">
      <VCol cols="12" md="8" lg="6">
        
        <div class="d-flex align-center justify-space-between mb-6">
          <div class="d-flex align-center">
             <VBtn
                :component="Link"
                :href="route('admin.users.index')"
                variant="outlined"
                color="grey-darken-1"
                size="small"
                class="rounded-lg border-opacity-50 bg-white mr-4"
                icon="mdi-arrow-left"
             />
             <div>
                <h1 class="text-subtitle-1 font-weight-bold text-grey-darken-4 lh-1">
                   Kullanıcı Yönetimi
                </h1>
                <div class="d-flex align-center text-caption text-medium-emphasis mt-1">
                   Düzenle <VIcon icon="mdi-chevron-right" size="14" class="mx-1"/> {{ user?.name }}
                </div>
             </div>
          </div>
        </div>

        <VCard class="rounded-xl border-none shadow-soft overflow-hidden" color="white">
          
          <div class="px-8 py-6 bg-grey-lighten-5 border-b d-flex align-center">
            <VAvatar color="primary" variant="flat" size="56" class="mr-5 shadow-accent">
              <span class="text-h5 font-weight-bold text-white">
                {{ user?.name?.charAt(0).toUpperCase() || 'U' }}
              </span>
            </VAvatar>
            <div>
              <div class="text-h6 font-weight-bold text-grey-darken-4">
                {{ user?.name || 'Yükleniyor...' }}
              </div>
              <div class="text-body-2 text-medium-emphasis d-flex align-center">
                <VIcon icon="mdi-email-outline" size="14" class="mr-1"/>
                {{ user?.email }}
              </div>
            </div>
          </div>

          <VForm @submit.prevent="submit">
            <VCardText class="px-8 py-8">
              <VRow dense>
                
                <VCol cols="12" class="mb-3">
                  <div class="d-flex align-center gap-2">
                    <VIcon icon="mdi-card-account-details-outline" color="primary" size="20"/>
                    <span class="text-subtitle-2 font-weight-bold text-grey-darken-3">Hesap Bilgileri</span>
                  </div>
                </VCol>

                <VCol cols="12" class="mb-4">
                  <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Ad Soyad</label>
                  <VTextField
                    v-model="form.name"
                    placeholder="Örn: Ahmet Yılmaz"
                    variant="outlined"
                    color="primary"
                    density="comfortable"
                    class="rounded-lg font-size-14"
                    bg-color="grey-lighten-5"
                    hide-details="auto"
                    :error-messages="form.errors.name"
                  >
                    <template #prepend-inner>
                        <VIcon icon="mdi-account-circle-outline" color="grey-darken-1" size="20" class="mr-1 opacity-70"/>
                    </template>
                  </VTextField>
                </VCol>

                <VCol cols="12" class="mb-6">
                  <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">E-posta Adresi</label>
                  <VTextField
                    v-model="form.email"
                    type="email"
                    placeholder="ahmet@ornek.com"
                    variant="outlined"
                    color="primary"
                    density="comfortable"
                    class="rounded-lg font-size-14"
                    bg-color="grey-lighten-5"
                    hide-details="auto"
                    :error-messages="form.errors.email"
                  >
                    <template #prepend-inner>
                        <VIcon icon="mdi-email-outline" color="grey-darken-1" size="20" class="mr-1 opacity-70"/>
                    </template>
                  </VTextField>
                </VCol>

                <VCol cols="12">
                   <VDivider class="border-dashed mb-6"/>
                </VCol>

                <VCol cols="12" class="mb-3">
                  <div class="d-flex align-center gap-2">
                    <VIcon icon="mdi-shield-check-outline" color="primary" size="20"/>
                    <span class="text-subtitle-2 font-weight-bold text-grey-darken-3">Yetkilendirme</span>
                  </div>
                </VCol>

                <VCol cols="12">
                  <label class="text-caption font-weight-bold text-grey-darken-2 mb-1 d-block">Kullanıcı Rolü</label>
                  
                  <VSelect
                    v-model="form.role"
                    :items="formattedRoles"
                    
                    item-title="title"
                    item-value="value"
                    
                    placeholder="Rol Seçiniz"
                    variant="outlined"
                    color="primary"
                    density="comfortable"
                    bg-color="grey-lighten-5"
                    class="rounded-lg font-size-14 saas-select"
                    hide-details="auto"
                    menu-icon="mdi-chevron-down"
                    :error-messages="form.errors.role"
                    
                    autocomplete="off"
                    name="random_role_field_name_123"
                  >
                    <template #prepend-inner>
                        <VIcon icon="mdi-security" color="grey-darken-1" size="20" class="mr-1 opacity-70"/>
                    </template>
                    
                    <template #selection="{ item }">
                         <span class="text-body-2 font-weight-medium text-grey-darken-3">
                            {{ item.title }}
                         </span>
                    </template>
                  </VSelect>
                  
                  <div class="text-caption text-medium-emphasis mt-2 ml-1">
                    <VIcon icon="mdi-information-outline" size="14" class="mr-1" />
                    Bu ayar kullanıcının sistemdeki erişim seviyesini belirler.
                  </div>
                </VCol>

              </VRow>
            </VCardText>

            <div class="px-8 py-6 bg-grey-lighten-5 border-t d-flex align-center justify-end gap-3">
              <VBtn
                :component="Link"
                :href="route('admin.users.index')"
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
                <VIcon start icon="mdi-content-save-outline" class="mr-1"/>
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
/* Google Font: Inter */
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

/* Tarayıcı Autofill'den gelen "admin" yazısını görünmez yapar */
.saas-select :deep(input) {
    opacity: 0 !important; 
}
</style>