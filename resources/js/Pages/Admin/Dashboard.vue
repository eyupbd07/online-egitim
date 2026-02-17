<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue'; 
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps({
  stats: Object,
  recentUsers: Array,
  chart: Object, // { labels: [], data: [] }
});

const user = computed(() => usePage().props.auth.user);

// Grafik Konfigürasyonu (Hata Korumalı)
const chartOptions = computed(() => ({
  chart: { 
    type: 'area', 
    toolbar: { show: false },
    fontFamily: 'inherit'
  },
  stroke: { curve: 'smooth', width: 3 },
  xaxis: { 
    categories: props.chart?.labels || [], // labels undefined ise boş dizi ata
    labels: { style: { colors: '#94a3b8' } }
  },
  yaxis: { show: true, labels: { style: { colors: '#94a3b8' } } },
  colors: ['#6366F1'],
  fill: { 
    type: 'gradient', 
    gradient: { opacityFrom: 0.5, opacityTo: 0.1 } 
  },
  dataLabels: { enabled: false },
  grid: { borderColor: '#f1f5f9' },
  tooltip: { theme: 'light' }
}));

const chartSeries = computed(() => [
  { name: 'Yeni Kayıtlar', data: props.chart?.data || [] }
]);

const getRoleColor = (role) => {
  if (role === 'admin') return 'error';
  if (role === 'instructor') return 'warning';
  return 'success';
};
</script>

<template>
  <Head title="Admin Dashboard" />

  <VContainer fluid class="pa-6">
    <VRow class="mb-6">
      <VCol cols="12">
        <VCard elevation="0" border class="rounded-xl pa-2">
          <VCardItem>
            <VCardTitle class="text-h5 font-weight-bold">Yönetim Paneli</VCardTitle>
            <VCardSubtitle>
              Hoş geldin, <span class="text-primary font-weight-bold">{{ user?.name }}</span>. Sistem durumunu buradan izleyebilirsin.
            </VCardSubtitle>
          </VCardItem>
        </VCard>
      </VCol>
    </VRow>

    <VRow>
      <VCol v-for="(stat, index) in [
        { label: 'TOPLAM ÖĞRENCİ', value: stats.students, color: 'success', icon: 'mdi-account-group', route: 'admin.users.index' },
        { label: 'TOPLAM EĞİTMEN', value: stats.instructors, color: 'warning', icon: 'mdi-account-tie-voice', route: 'admin.users.index' },
        { label: 'TOPLAM KURS', value: stats.courses, color: 'info', icon: 'mdi-book-open-page-variant', route: 'admin.courses.index' }
      ]" :key="index" cols="12" md="4">
        <Link :href="route(stat.route)" class="text-decoration-none">
          <VCard elevation="0" border class="stat-card rounded-xl pa-4">
            <div class="d-flex align-center">
              <VAvatar :color="stat.color" variant="tonal" size="56" rounded="lg" class="me-4">
                <VIcon :icon="stat.icon" size="30" />
              </VAvatar>
              <div>
                <div class="text-caption font-weight-bold text-grey-darken-1">{{ stat.label }}</div>
                <div class="text-h4 font-weight-black">{{ stat.value }}</div>
              </div>
            </div>
          </VCard>
        </Link>
      </VCol>
    </VRow>

    <VRow class="mt-4">
      <VCol cols="12" md="7">
        <VCard elevation="0" border class="rounded-xl h-100">
          <VCardItem>
            <VCardTitle class="font-weight-bold">Kullanıcı Kayıt Analizi</VCardTitle>
          </VCardItem>
          <VCardText>
            <VueApexCharts 
              v-if="props.chart"
              :options="chartOptions" 
              :series="chartSeries" 
              height="300" 
            />
          </VCardText>
        </VCard>
      </VCol>

      <VCol cols="12" md="5">
        <VCard elevation="0" border class="rounded-xl h-100">
          <VCardItem>
            <VCardTitle class="font-weight-bold">Son Kayıtlar</VCardTitle>
          </VCardItem>
          <VList lines="two" class="bg-transparent pa-2">
            <VListItem 
              v-for="u in recentUsers" 
              :key="u.id" 
              class="rounded-lg mb-1"
            >
              <template #prepend>
                <VAvatar :color="getRoleColor(u.role)" variant="tonal" size="40">
                  <VIcon icon="mdi-account" size="20" />
                </VAvatar>
              </template>
              <VListItemTitle class="font-weight-bold">{{ u.name }}</VListItemTitle>
              <VListItemSubtitle class="text-caption">
                {{ u.role.toUpperCase() }} • {{ u.created_at }}
              </VListItemSubtitle>
            </VListItem>
            
            <div v-if="!recentUsers || recentUsers.length === 0" class="text-center pa-10 text-grey">
              Kayıtlı kullanıcı bulunamadı.
            </div>
          </VList>
        </VCard>
      </VCol>
    </VRow>
  </VContainer>
</template>

<style scoped>
.stat-card {
  transition: all 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-4px);
  border-color: rgb(var(--v-theme-primary)) !important;
  box-shadow: 0 10px 20px -10px rgba(0,0,0,0.1) !important;
}
</style>