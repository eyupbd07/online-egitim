<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

// Controller'dan gelen veriler
const props = defineProps({
  ticket: Object,
  statuses: Array, 
});

// --- 1. SABİT LİSTE (Kesin Çözüm) ---
const statusOptions = [
  { title: 'Açık', value: 'open', color: 'success' },
  { title: 'Beklemede', value: 'pending', color: 'warning' },
  { title: 'Kapalı', value: 'closed', color: 'grey' },
];

// Renk Bulucu
const getStatusColor = (statusVal) => {
    return statusOptions.find(o => o.value === statusVal)?.color || 'primary';
};

// Başlık Bulucu (Header'daki Chip İçin)
const getStatusTitle = (statusVal) => {
    return statusOptions.find(o => o.value === statusVal)?.title || statusVal;
};

// --- 2. FORM YAPISI ---
const form = useForm({
  admin_reply: props.ticket?.admin_reply || '', 
  status: props.ticket?.status || 'open', 
});

const submit = () => {
  if (!props.ticket?.id) return;
  const updateUrl = route('admin.support.update', props.ticket.id); 
  form.post(updateUrl);
};
</script>

<template>
  <Head :title="`Talep #${ticket?.id || ''}`" />

  <VContainer fluid class="fill-height bg-grey-lighten-5 py-8 font-inter">
    <VRow justify="center">
      <VCol cols="12" lg="10" xl="9">
        
        <div class="d-flex align-center justify-space-between mb-6">
          <div class="d-flex align-center gap-3">
             <VBtn
                :component="Link"
                :href="route('admin.support.index')"
                variant="outlined"
                color="grey-darken-1"
                size="small"
                class="rounded-lg border-opacity-50 bg-white"
                icon="mdi-arrow-left"
             />
             <div>
                <h1 class="text-subtitle-1 font-weight-bold text-grey-darken-4 lh-1">
                   Talep Yönetimi
                </h1>
                <div class="d-flex align-center text-caption text-medium-emphasis mt-1">
                   Destek Merkezi <VIcon icon="mdi-chevron-right" size="14" class="mx-1"/> #{{ ticket?.id }}
                </div>
             </div>
          </div>

          <VChip
             :color="getStatusColor(ticket?.status)"
             variant="tonal"
             size="default"
             class="font-weight-bold px-4 rounded-lg"
             label
          >
             <VIcon start size="10" icon="mdi-circle" class="mr-2 opacity-100" />
             {{ getStatusTitle(ticket?.status) }}
          </VChip>
        </div>

        <VCard class="rounded-xl border-none shadow-soft overflow-hidden" color="white">
            <VRow no-gutters>
              
              <VCol cols="12" md="7" class="bg-grey-lighten-5 pa-0 d-flex flex-column border-e">
                 
                 <div class="pa-8 pb-4">
                    <div class="text-overline text-grey-darken-1 font-weight-bold letter-spacing-1 mb-4">
                        TALEP BİLGİSİ
                    </div>
                    
                    <h2 class="text-h5 font-weight-bold text-grey-darken-4 mb-2 lh-title">
                        {{ ticket?.subject || 'Başlık Yükleniyor...' }}
                    </h2>
                    
                    <div class="d-flex align-center text-caption text-grey-darken-1 mb-6">
                        <VIcon icon="mdi-calendar-blank" size="16" class="mr-2 text-medium-emphasis"/>
                        {{ ticket?.created_at || 'Tarih yok' }}
                    </div>

                    <div class="user-card bg-white pa-4 rounded-xl border shadow-xs d-flex align-center">
                        <VAvatar color="primary" variant="tonal" size="48" class="mr-4 rounded-lg">
                           <span class="text-h6 font-weight-bold primary--text">
                               {{ ticket?.user_name?.charAt(0).toUpperCase() || 'U' }}
                           </span>
                        </VAvatar>
                        <div>
                           <div class="text-subtitle-2 font-weight-bold text-grey-darken-3">
                               {{ ticket?.user_name || 'İsimsiz Kullanıcı' }}
                           </div>
                           <div class="text-caption text-medium-emphasis">
                               {{ ticket?.user_email || 'email@yok.com' }}
                           </div>
                        </div>
                    </div>
                 </div>

                 <VDivider class="mx-8 border-dashed" />

                 <div class="pa-8 flex-grow-1">
                    <div class="text-overline text-grey-darken-1 font-weight-bold letter-spacing-1 mb-3">
                        MESAJ İÇERİĞİ
                    </div>
                    
                    <div class="message-bubble bg-white pa-6 rounded-xl border shadow-xs relative">
                        <VIcon 
                           icon="mdi-format-quote-open" 
                           class="position-absolute text-grey-lighten-4" 
                           size="64" 
                           style="top: 10px; right: 20px;" 
                        />
                        
                        <div v-if="ticket?.message" class="text-body-2 text-grey-darken-3 relative z-1" style="white-space: pre-wrap; line-height: 1.8;">
                            {{ ticket.message }}
                        </div>
                        <VAlert v-else type="info" variant="tonal" density="compact" class="text-caption rounded-lg">
                            Mesaj içeriği bulunamadı.
                        </VAlert>
                    </div>
                 </div>
              </VCol>

              <VCol cols="12" md="5" class="bg-white pa-8 d-flex flex-column h-100">
                 
                 <div class="d-flex align-center gap-2 mb-6">
                    <div class="bg-primary-lighten-5 pa-2 rounded-lg">
                        <VIcon icon="mdi-flash-outline" color="primary" size="20" />
                    </div>
                    <div class="text-subtitle-2 font-weight-bold text-grey-darken-4">
                        YANIT & İŞLEM
                    </div>
                 </div>

                 <VForm @submit.prevent="submit" class="d-flex flex-column flex-grow-1">
                    
                    <div class="mb-6 flex-grow-1">
                       <label class="text-caption font-weight-bold text-grey-darken-2 mb-2 d-block ml-1">
                          Yanıtınız
                       </label>
                       <VTextarea
                          v-model="form.admin_reply"
                          placeholder="Çözüm önerinizi veya yanıtınızı buraya yazın..."
                          rows="10"
                          no-resize
                          variant="outlined"
                          color="primary"
                          bg-color="grey-lighten-5"
                          density="comfortable"
                          class="rounded-lg font-size-14"
                          hide-details="auto"
                       />
                    </div>

                    <div class="mb-8">
                       <label class="text-caption font-weight-bold text-grey-darken-2 mb-2 d-block ml-1">
                          Durumu Güncelle
                       </label>
                       
                       <VSelect
                          v-model="form.status"
                          :items="statusOptions"
                          
                          item-title="title"
                          item-value="value"
                          
                          label=""
                          single-line
                          placeholder="Seçiniz"
                          
                          variant="outlined"
                          color="primary"
                          bg-color="grey-lighten-5"
                          density="comfortable"
                          
                          :error-messages="form.errors.status"
                          class="rounded-lg saas-select font-size-14"
                          hide-details="auto"
                          menu-icon="mdi-chevron-down"
                          
                          autocomplete="off"
                          id="ticket_status_selector"
                          name="custom_status_field_xyz"
                       >
                          <template #prepend-inner>
                             <div class="d-flex align-center justify-center mr-3 border-e pr-3 icon-box">
                                <VIcon icon="mdi-list-status" color="grey-darken-1" size="18" />
                             </div>
                          </template>
                          
                          <template #selection="{ item }">
                             <span class="text-body-2 font-weight-medium text-grey-darken-3">
                                {{ item.title }}
                             </span>
                          </template>
                       </VSelect>
                    </div>

                    <VDivider class="mb-6 border-dashed" />

                    <VBtn
                       type="submit"
                       color="primary"
                       variant="flat"
                       height="52"
                       :loading="form.processing"
                       :disabled="form.processing || !ticket?.id"
                       block
                       class="rounded-lg text-capitalize font-weight-bold text-body-1 shadow-accent transition-all"
                    >
                       <VIcon start icon="mdi-send-outline" class="mr-2" size="18" />
                       Yanıtla ve Kaydet
                    </VBtn>
                 </VForm>
              </VCol>

            </VRow>
        </VCard>

      </VCol>
    </VRow>
  </VContainer>
</template>

<style scoped>
.font-inter { font-family: 'Inter', system-ui, -apple-system, sans-serif !important; }
.lh-1 { line-height: 1; }
.lh-title { line-height: 1.3; }
.letter-spacing-1 { letter-spacing: 0.8px; }
.font-size-14 { font-size: 0.92rem !important; }

.shadow-xs { box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05) !important; }
.shadow-soft { box-shadow: 0 12px 32px -4px rgba(16, 24, 40, 0.08), 0 4px 12px -2px rgba(16, 24, 40, 0.03) !important; }
.shadow-accent { box-shadow: 0 4px 12px rgba(var(--v-theme-primary), 0.25) !important; }

.gap-3 { gap: 12px; }
.gap-2 { gap: 8px; }
.z-1 { position: relative; z-index: 1; }
.border-dashed { border-style: dashed !important; }

/* --- CRITICAL FIX: INPUT GİZLEME VE HİZALAMA --- */
/* 1. Yüksekliği ve hizalamayı düzeltir */
.saas-select :deep(.v-field__input) {
  min-height: 48px; 
  display: flex;
  align-items: center;
  padding-top: 0 !important;
  padding-bottom: 0 !important;
}

/* 2. Tarayıcı Autofill'in yarattığı "open" yazısını GÖRÜNMEZ yapar */
.saas-select :deep(input) {
    opacity: 0 !important;
    width: 0 !important;
}

.icon-box {
   height: 24px;
   min-width: 24px;
}

.user-card { transition: border-color 0.2s ease; }
.user-card:hover { border-color: rgba(var(--v-theme-primary), 0.4) !important; }
</style>