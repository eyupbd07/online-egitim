<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
  tickets: Array,
  flash: Object,
});

// --- Detay Görüntüleme Modalı ---
const dialogShow = ref(false);
const ticketToShow = ref(null);

const openShowModal = (ticket) => {
  ticketToShow.value = ticket;
  dialogShow.value = true;
};

// Durum Renkleri
const getStatusChipColor = (status) => {
  if (status === 'closed') return 'error';
  if (status === 'pending') return 'warning';
  if (status === 'open') return 'success';
  return 'secondary';
};

// Durum Çevirileri
const getStatusTranslation = (status) => {
  const translations = {
    open: 'Açık',
    closed: 'Kapalı',
    pending: 'Beklemede'
  };
  return translations[status] || status;
};

// Bildirimler (Snackbar)
const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

watch(() => props.flash, (flash) => {
  if (flash?.message || flash?.error) {
    snackbarMessage.value = flash.message || flash.error;
    snackbarColor.value = flash.message ? 'success' : 'error';
    snackbar.value = true;
  }
}, { immediate: true, deep: true });
</script>

<template>
  <Head title="Destek Taleplerim" />

  <VContainer class="py-8 px-6" fluid>
    <header class="mb-8 d-flex justify-space-between align-end">
      <div>
        <div class="d-flex align-center mb-1">
          <VIcon icon="mdi-help-circle-outline" color="primary" class="me-2" size="small" />
          <span class="text-overline font-weight-black text-primary letter-spacing-1">EĞİTMEN DESTEK</span>
        </div>
        <h1 class="text-h4 font-weight-black text-slate-900">Destek Taleplerim</h1>
        <p class="text-subtitle-1 text-slate-500 mt-1">Oluşturduğunuz talepleri ve admin yanıtlarını buradan takip edebilirsiniz.</p>
      </div>
      <VBtn
        color="primary"
        elevation="0"
        class="rounded-lg px-6 font-weight-bold text-none"
        size="large"
        :component="Link"
        :href="route('instructor.support.create')"
      >
        <VIcon icon="mdi-plus" start />
        Yeni Talep Oluştur
      </VBtn>
    </header>

    <VCard class="rounded-xl border shadow-sm" elevation="0">
      <VTable v-if="tickets.length > 0" hover class="modern-table text-no-wrap">
        <thead>
          <tr>
            <th class="text-overline font-weight-black px-6">ID</th>
            <th class="text-overline font-weight-black">KONU</th>
            <th class="text-overline font-weight-black">DURUM</th>
            <th class="text-overline font-weight-black">OLUŞTURULMA</th>
            <th class="text-overline font-weight-black">GÜNCELLEME</th>
            <th class="text-overline font-weight-black text-center">DETAY</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ticket in tickets" :key="ticket.id" class="table-row">
            <td class="px-6 font-weight-bold text-slate-400">#{{ ticket.id }}</td>
            <td class="font-weight-bold text-slate-800">{{ ticket.subject }}</td>
            <td>
              <VChip
                :color="getStatusChipColor(ticket.status)"
                size="small"
                variant="flat"
                class="font-weight-black px-3"
              >
                {{ getStatusTranslation(ticket.status) }}
              </VChip>
            </td>
            <td class="text-body-2 text-slate-500">{{ ticket.created_at }}</td>
            <td class="text-body-2 text-slate-500">{{ ticket.updated_at }}</td>
            <td class="text-center">
              <VBtn
                icon="mdi-eye-outline"
                variant="tonal"
                size="small"
                color="primary"
                class="rounded-lg"
                @click="openShowModal(ticket)"
              />
            </td>
          </tr>
        </tbody>
      </VTable>

      <VAlert v-else variant="flat" color="slate-50" class="ma-8 text-center rounded-xl border-dashed">
        <VIcon icon="mdi-message-off-outline" size="48" color="slate-300" class="mb-2" />
        <div class="text-h6 text-slate-400 font-weight-bold">Henüz bir destek talebiniz bulunmuyor.</div>
      </VAlert>
    </VCard>
  </VContainer>

  <VDialog v-model="dialogShow" max-width="750px" transition="dialog-bottom-transition">
    <VCard v-if="ticketToShow" class="rounded-xl overflow-hidden">
      <VCardTitle class="pa-6 d-flex justify-space-between align-center border-b">
        <div class="d-flex align-center">
            <VAvatar color="primary" variant="tonal" size="40" class="me-3">
                <VIcon icon="mdi-ticket-outline" />
            </VAvatar>
            <div>
                <span class="text-h6 font-weight-bold d-block">Talep Detayı: #{{ ticketToShow.id }}</span>
                <span class="text-caption text-slate-400">{{ ticketToShow.created_at }} tarihinde oluşturuldu</span>
            </div>
        </div>
        <VBtn icon="mdi-close" variant="text" color="slate-400" @click="dialogShow = false" />
      </VCardTitle>
      
      <VCardText class="pa-6 bg-slate-50">
        <VRow class="mb-6">
            <VCol cols="12" md="8">
                <div class="text-overline text-slate-400 font-weight-black">TALEP KONUSU</div>
                <div class="text-h6 font-weight-bold text-slate-800">{{ ticketToShow.subject }}</div>
            </VCol>
            <VCol cols="12" md="4" class="text-md-right">
                <div class="text-overline text-slate-400 font-weight-black">GÜNCEL DURUM</div>
                <VChip :color="getStatusChipColor(ticketToShow.status)" variant="flat" class="font-weight-black px-4">
                    {{ getStatusTranslation(ticketToShow.status) }}
                </VChip>
            </VCol>
        </VRow>

        <div class="d-flex flex-column gap-6">
            <div class="message-bubble sent align-self-end">
                <div class="text-caption font-weight-bold text-indigo-lighten-3 mb-1">SİZİN MESAJINIZ</div>
                <div class="text-body-1 leading-relaxed">{{ ticketToShow.message }}</div>
            </div>

            <div v-if="ticketToShow.admin_reply" class="message-bubble received align-self-start">
                <div class="text-caption font-weight-bold text-slate-400 mb-1">ADMİN YANITI</div>
                <div class="text-body-1 leading-relaxed">{{ ticketToShow.admin_reply }}</div>
            </div>
            <VAlert v-else border="start" border-color="warning" class="bg-warning-lighten-5 rounded-lg text-warning-darken-3">
                <VIcon icon="mdi-clock-outline" start />
                Talebiniz inceleniyor. Admin tarafından yanıt verildiğinde burada görebilirsiniz.
            </VAlert>
        </div>
      </VCardText>
      
      <VCardActions class="pa-6 bg-white border-t">
        <VSpacer />
        <VBtn color="slate-500" variant="text" class="font-weight-bold" @click="dialogShow = false">KAPAT</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="bottom right" elevation="24" rounded="pill">
    <div class="d-flex align-center font-weight-bold">
      <VIcon :icon="snackbarColor === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle'" class="me-3" />
      {{ snackbarMessage }}
    </div>
  </VSnackbar>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:deep(*) {
  font-family: 'Plus Jakarta Sans', sans-serif !important;
}

.text-slate-900 { color: #0f172a; }
.text-slate-800 { color: #1e293b; }
.text-slate-500 { color: #64748b; }
.text-slate-400 { color: #94a3b8; }
.letter-spacing-1 { letter-spacing: 1px; }

.modern-table { background: transparent !important; }
.modern-table thead th { background: #f8fafc !important; height: 56px !important; }

.table-row { transition: background 0.2s ease; }
.table-row:hover { background: #f1f5f9 !important; }

/* Mesaj Balonları */
.message-bubble {
    max-width: 85%;
    padding: 16px 20px;
    border-radius: 20px;
    position: relative;
}

.message-bubble.sent {
    background: #6366f1;
    color: white;
    border-bottom-right-radius: 4px;
}

.message-bubble.received {
    background: white;
    color: #1e293b;
    border-bottom-left-radius: 4px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}

.gap-6 { gap: 24px; }
.leading-relaxed { line-height: 1.6; }
</style>