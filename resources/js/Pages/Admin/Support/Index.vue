<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// Controller'dan gönderilen 'tickets' ve 'flash' verileri
const props = defineProps({
  tickets: Array,
  flash: Object,
});

// Durumları renklendirmek için modern renk paleti
const getStatusChipColor = (status) => {
  if (status === 'closed') return 'error';
  if (status === 'pending') return 'warning';
  if (status === 'open') return 'success';
  return 'secondary';
};

// Durumları Türkçeleştirme
const getStatusTranslation = (status) => {
  const translations = {
    open: 'Açık',
    closed: 'Kapalı',
    pending: 'Beklemede'
  };
  return translations[status] || status;
};

// Başarı/Hata Mesajları (Snackbar)
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
  <Head title="Destek Talepleri Yönetimi" />

  <VContainer class="py-8 px-6" fluid>
    <header class="mb-8">
      <div class="d-flex align-center mb-2">
        <VIcon icon="mdi-face-agent" color="primary" class="me-2" size="small" />
        <span class="text-overline font-weight-bold text-primary letter-spacing-1">Yönetici Paneli</span>
      </div>
      <h1 class="text-h4 font-weight-black text-slate-900">Destek Talepleri</h1>
      <p class="text-subtitle-1 text-slate-500 mt-1">Eğitmenlerden gelen tüm yardım ve destek taleplerini buradan denetleyebilirsiniz.</p>
    </header>

    <VRow class="mb-8">
      <VCol cols="12" md="4">
        <VCard class="stat-card-mini" elevation="0" border>
          <div class="pa-4 d-flex align-center">
            <VAvatar color="success-lighten-5" class="me-4" rounded="lg">
              <VIcon icon="mdi-email-check-outline" color="success" />
            </VAvatar>
            <div>
              <div class="text-caption font-weight-bold text-slate-400">TOPLAM TALEP</div>
              <div class="text-h5 font-weight-black">{{ tickets.length }}</div>
            </div>
          </div>
        </VCard>
      </VCol>
      <VCol cols="12" md="4">
        <VCard class="stat-card-mini" elevation="0" border>
          <div class="pa-4 d-flex align-center">
            <VAvatar color="warning-lighten-5" class="me-4" rounded="lg">
              <VIcon icon="mdi-clock-outline" color="warning" />
            </VAvatar>
            <div>
              <div class="text-caption font-weight-bold text-slate-400">BEKLEYENLER</div>
              <div class="text-h5 font-weight-black text-warning">
                {{ tickets.filter(t => t.status === 'pending').length }}
              </div>
            </div>
          </div>
        </VCard>
      </VCol>
    </VRow>

    <VCard class="rounded-xl border shadow-sm" elevation="0">
      <VTable v-if="tickets.length > 0" hover class="modern-table">
        <thead>
          <tr>
            <th class="text-overline font-weight-black px-6">ID</th>
            <th class="text-overline font-weight-black">KONU</th>
            <th class="text-overline font-weight-black">GÖNDEREN EĞİTMEN</th>
            <th class="text-overline font-weight-black">DURUM</th>
            <th class="text-overline font-weight-black">TARİH</th>
            <th class="text-overline font-weight-black text-center">İŞLEM</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ticket in tickets" :key="ticket.id" class="table-row">
            <td class="px-6 font-weight-bold text-slate-400">#{{ ticket.id }}</td>
            <td class="font-weight-bold text-slate-800">{{ ticket.subject }}</td>
            <td>
              <div class="d-flex align-center">
                <VAvatar size="28" color="indigo-lighten-5" class="me-2">
                  <VIcon icon="mdi-account" size="16" color="indigo" />
                </VAvatar>
                <span class="text-body-2 font-weight-medium text-slate-600">{{ ticket.user_name }}</span>
              </div>
            </td>
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
            <td class="text-body-2 text-slate-400">{{ ticket.created_at }}</td>
            <td class="text-center">
              <VBtn
                :component="Link"
                :href="route('admin.support.show', ticket.id)"
                icon="mdi-email-open-outline"
                variant="tonal"
                size="small"
                color="primary"
                class="rounded-lg"
                v-tooltip="'İncele & Cevapla'"
              />
            </td>
          </tr>
        </tbody>
      </VTable>

      <VAlert v-else variant="flat" color="slate-50" class="ma-8 text-center rounded-xl border-dashed">
        <VIcon icon="mdi-tray-full" size="48" color="slate-300" class="mb-2" />
        <div class="text-h6 text-slate-400 font-weight-bold">Aktif destek talebi bulunamadı.</div>
      </VAlert>
    </VCard>
  </VContainer>

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="bottom right" rounded="pill">
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
.text-slate-600 { color: #475569; }
.text-slate-500 { color: #64748b; }
.text-slate-400 { color: #94a3b8; }
.letter-spacing-1 { letter-spacing: 1px; }

.stat-card-mini {
  border-radius: 20px !important;
  transition: transform 0.3s ease;
}

.stat-card-mini:hover {
  transform: translateY(-4px);
}

.modern-table {
  background: transparent !important;
}

.modern-table thead th {
  background: #f8fafc !important;
  height: 56px !important;
}

.table-row {
  transition: background 0.2s ease;
}

.table-row:hover {
  background: #f1f5f9 !important;
}

.rounded-xl {
  border-radius: 24px !important;
}
</style>