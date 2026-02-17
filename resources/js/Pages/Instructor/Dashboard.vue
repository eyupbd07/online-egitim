<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { usePage, Head } from '@inertiajs/vue3'
import VueApexCharts from 'vue3-apexcharts'

const page = usePage()
const userName = computed(() => page.props.auth.user.name)

const stats = ref({
    totalCourses: 0,
    totalStudents: 0,
    totalEarnings: 0,
    series: [{ name: 'Kayıtlar', data: [0, 0, 0, 0, 0, 0, 0] }],
    recentActivities: []
})

const isLoading = ref(true)

// Modern SaaS Estetiğine Uygun Grafik Ayarları
const chartOptions = computed(() => ({
    chart: { 
        type: 'area', 
        toolbar: { show: false }, 
        fontFamily: 'Plus Jakarta Sans, sans-serif',
        sparkline: { enabled: false }
    },
    stroke: { curve: 'smooth', width: 3, colors: ['#6366F1'] },
    fill: { 
        type: 'gradient', 
        gradient: { 
            shadeIntensity: 1, 
            opacityFrom: 0.4, 
            opacityTo: 0.05, 
            stops: [0, 90, 100] 
        } 
    },
    dataLabels: { enabled: false },
    xaxis: { 
        categories: ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'], 
        axisBorder: { show: false }, 
        axisTicks: { show: false },
        labels: { style: { colors: '#94a3b8', fontSize: '12px', fontWeight: 600 } }
    },
    yaxis: { show: false },
    grid: { show: true, borderColor: '#f1f5f9', strokeDashArray: 4 },
    tooltip: { theme: 'light', x: { show: false } },
    colors: ['#6366F1'],
    markers: { size: 4, colors: ['#6366F1'], strokeWidth: 2, hover: { size: 6 } }
}))

const fetchStats = async () => {
    try {
        const response = await axios.get('/api/dashboard-stats')
        if (response.data) stats.value = response.data
    } catch (error) { 
        console.error("İstatistikler yüklenirken hata oluştu:", error) 
    } finally { 
        isLoading.value = false 
    }
}

onMounted(() => fetchStats())
</script>

<template>
    <Head title="Eğitmen Paneli" />
    <VContainer fluid class="py-8 px-6 bg-slate-50 min-h-screen">
        
        <header class="d-flex flex-wrap justify-space-between align-center mb-10 gap-4">
            <div>
                <div class="d-flex align-center mb-1">
                    <VIcon icon="mdi-shield-check-outline" color="primary" class="me-2" size="small" />
                    <span class="text-overline font-weight-black text-primary letter-spacing-1">EĞİTMEN YÖNETİM MERKEZİ</span>
                </div>
                <h2 class="text-h3 font-weight-black text-slate-900">Merhaba, {{ userName }} 👋</h2>
                <p class="text-subtitle-1 text-slate-500 mt-1">İşte bugün eğitim platformundaki performansın.</p>
            </div>
            <VBtn 
                color="primary" 
                elevation="0" 
                prepend-icon="mdi-plus-circle-outline" 
                size="large" 
                class="rounded-xl px-8 font-weight-bold text-none shadow-indigo" 
                :href="route('instructor.courses.create')"
            >
                Yeni Kurs Oluştur
            </VBtn>
        </header>

        <VRow class="mb-10">
            <VCol v-for="(card, index) in [
                { label: 'TOPLAM KAZANÇ', value: stats.totalEarnings + ' ₺', color: 'success', icon: 'mdi-wallet-membership' },
                { label: 'TOPLAM ÖĞRENCİ', value: stats.totalStudents, color: 'info', icon: 'mdi-account-group-outline' },
                { label: 'AKTİF KURSLAR', value: stats.totalCourses, color: 'primary', icon: 'mdi-book-open-page-variant-outline' }
            ]" :key="index" cols="12" sm="6" md="4">
                <VCard elevation="0" border class="stat-card rounded-xl pa-2">
                    <VCardText class="d-flex justify-space-between align-center">
                        <div>
                            <div class="text-caption font-weight-black text-slate-400 mb-1 text-uppercase letter-spacing-1">
                                {{ card.label }}
                            </div>
                            <h3 class="text-h4 font-weight-black text-slate-900">{{ card.value }}</h3>
                        </div>
                        <VAvatar :color="card.color" variant="tonal" size="56" rounded="lg">
                            <VIcon :icon="card.icon" size="28" />
                        </VAvatar>
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>

        <VRow>
            <VCol cols="12" md="8">
                <VCard elevation="0" border class="rounded-xl h-100 overflow-hidden">
                    <VCardItem class="pa-6 border-b bg-white">
                        <template #title>
                            <div class="text-h6 font-weight-black text-slate-800">Haftalık Kayıt Analizi</div>
                        </template>
                        <template #subtitle>
                            <span class="text-slate-400">Son 7 günün öğrenci kayıt grafiği</span>
                        </template>
                    </VCardItem>
                    <VCardText class="pa-6 bg-white">
                         <div v-if="isLoading" class="d-flex justify-center align-center" style="height: 300px;">
                            <VProgressCircular indeterminate color="primary" width="3" />
                        </div>
                        <VueApexCharts v-else type="area" height="300" :options="chartOptions" :series="stats.series" />
                    </VCardText>
                </VCard>
            </VCol>

            <VCol cols="12" md="4">
                <VCard elevation="0" border class="rounded-xl h-100 bg-white">
                    <VCardItem class="pa-6 border-b">
                        <template #title>
                            <div class="text-h6 font-weight-black text-slate-800">Son Aktiviteler</div>
                        </template>
                    </VCardItem>
                    <VCardText class="pa-0">
                        <VList class="bg-transparent pa-0">
                            <VListItem v-if="stats.recentActivities.length === 0" class="py-12">
                                <div class="text-center">
                                    <VAvatar color="slate-50" size="64" class="mb-4">
                                        <VIcon icon="mdi-bell-off-outline" size="32" color="slate-300" />
                                    </VAvatar>
                                    <p class="text-slate-400 font-weight-bold">Henüz bir bildirim yok</p>
                                </div>
                            </VListItem>
                            <VListItem 
                                v-for="(act, i) in stats.recentActivities" 
                                :key="i" 
                                class="rounded-lg ma-2 activity-item border"
                            >
                                <template #prepend>
                                    <VAvatar color="primary" variant="tonal" size="36" class="me-3">
                                        <VIcon icon="mdi-lightning-bolt-outline" size="18" />
                                    </VAvatar>
                                </template>
                                <VListItemTitle class="font-weight-bold text-slate-700 text-body-2">{{ act }}</VListItemTitle>
                                <VListItemSubtitle class="text-caption text-slate-400 mt-1">Az önce</VListItemSubtitle>
                            </VListItem>
                        </VList>
                    </VCardText>
                </VCard>
            </VCol>
        </VRow>
    </VContainer>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:deep(*) {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
}

.bg-slate-50 { background-color: #f8fafc; }
.text-slate-900 { color: #0f172a; }
.text-slate-800 { color: #1e293b; }
.text-slate-700 { color: #334155; }
.text-slate-500 { color: #64748b; }
.text-slate-400 { color: #94a3b8; }
.letter-spacing-1 { letter-spacing: 1px; }

.stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: white !important;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.1) !important;
    border-color: #6366f1 !important;
}

.activity-item {
    background-color: #ffffff;
    transition: all 0.2s ease;
}

.activity-item:hover {
    border-color: #6366f1 !important;
    background-color: #f5f3ff !important;
}

.shadow-indigo {
    box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.3) !important;
}
</style>