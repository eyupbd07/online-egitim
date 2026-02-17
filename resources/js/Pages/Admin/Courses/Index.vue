<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
  courses: Array,
  flash: Object,
});

// --- MODAL & AKSİYON DURUMLARI ---
const dialogView = ref(false);
const lessonToView = ref(null);
const dialogDelete = ref(false);
const courseToDelete = ref(null);

const openViewModal = (lesson) => {
  lessonToView.value = lesson;
  dialogView.value = true;
};

const openDeleteConfirm = (course) => {
  courseToDelete.value = course;
  dialogDelete.value = true;
};

const deleteCourseConfirmed = () => {
  router.post(route('admin.courses.destroy', courseToDelete.value.id), {
    _method: 'delete',
  }, {
    onSuccess: () => {
       dialogDelete.value = false;
       courseToDelete.value = null;
    }
  });
};

// --- BİLDİRİM (SNACKBAR) MANTIĞI ---
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
  <Head title="Kurs Yönetimi" />

  <VContainer class="py-6 px-6" fluid>
    <header class="mb-8 d-flex justify-space-between align-center">
      <div>
        <div class="d-flex align-center mb-1">
          <VIcon icon="mdi-shield-check-outline" color="primary" class="me-2" size="small" />
          <span class="text-overline font-weight-black text-primary letter-spacing-1">YÖNETİCİ PANELİ</span>
        </div>
        <h1 class="text-h4 font-weight-black text-slate-900">Eğitim Kataloğu</h1>
        <p class="text-body-1 text-slate-500">Platformdaki içerikleri ve müfredat akışını buradan denetleyin.</p>
      </div>
      <VBtn :to="route('admin.courses.create')" color="primary" elevation="0" class="rounded-lg font-weight-bold px-6 text-none" size="large">
         <VIcon start icon="mdi-plus" /> Yeni Kurs Oluştur
      </VBtn>
    </header>

    <VRow class="mb-8">
        <VCol v-for="(stat, index) in [
            { label: 'Aktif Kurslar', value: courses.length, icon: 'mdi-book-multiple', color: '#6366F1' },
            { label: 'Toplam Eğitmen', value: new Set(courses.map(c => c.instructor_name)).size, icon: 'mdi-account-tie', color: '#10B981' },
            { label: 'Yayınlanan Dersler', value: courses.reduce((acc, c) => acc + c.lessons.length, 0), icon: 'mdi-play-circle-outline', color: '#F59E0B' }
        ]" :key="index" cols="12" md="4">
            <VCard class="stat-card-mini" elevation="0" border>
                <div class="pa-4 d-flex align-center">
                    <div class="stat-icon-mini mr-4" :style="{ backgroundColor: stat.color + '15', color: stat.color }">
                        <VIcon :icon="stat.icon" size="24" />
                    </div>
                    <div>
                        <div class="text-caption font-weight-bold text-slate-400 text-uppercase">{{ stat.label }}</div>
                        <div class="text-h5 font-weight-black text-slate-800">{{ stat.value }}</div>
                    </div>
                </div>
            </VCard>
        </VCol>
    </VRow>

    <div v-if="courses.length > 0">
      <VExpansionPanels variant="inset" class="custom-panels">
        <VExpansionPanel
          v-for="course in courses"
          :key="course.id"
          class="course-panel mb-4"
        >
          <VExpansionPanelTitle class="py-5">
            <VRow align="center" no-gutters>
              <VCol cols="12" md="5" class="d-flex align-center">
                <VAvatar color="primary-lighten-5" rounded="lg" size="44" class="me-4 text-primary">
                  <VIcon icon="mdi-book-open-variant" />
                </VAvatar>
                <div>
                  <div class="text-h6 font-weight-bold text-slate-800 line-clamp-1">{{ course.title }}</div>
                  <div class="text-caption text-slate-400">Kurs ID: #{{ course.id }}</div>
                </div>
              </VCol>
              
              <VCol cols="12" md="5" class="d-flex gap-3">
                <div class="info-badge">
                  <VIcon icon="mdi-account-circle-outline" size="14" class="me-1" />
                  <span>{{ course.instructor_name }}</span>
                </div>
                <div class="info-badge success">
                  <VIcon icon="mdi-layers-outline" size="14" class="me-1" />
                  <span>{{ course.lessons.length }} Ders</span>
                </div>
              </VCol>
              
              <VCol cols="12" md="2" class="text-right pr-4">
                <VBtn
                  :href="route('admin.courses.edit', course.id)"
                  icon="mdi-pencil-outline"
                  variant="text"
                  color="slate-400"
                  class="rounded-lg mr-1"
                  size="small"
                />
                <VBtn
                  @click.stop="openDeleteConfirm(course)"
                  icon="mdi-trash-can-outline"
                  variant="text"
                  color="error"
                  class="rounded-lg"
                  size="small"
                />
              </VCol>
            </VRow>
          </VExpansionPanelTitle>

          <VExpansionPanelText class="bg-slate-50 pa-4 rounded-b-xl border-t">
            <VCard flat class="rounded-xl pa-5 mb-5 border bg-white">
              <div class="text-overline font-weight-black text-primary mb-1">KURS TANIMI</div>
              <p class="text-body-2 text-slate-600 leading-relaxed">{{ course.description }}</p>
            </VCard>
            
            <div class="text-overline font-weight-black text-slate-400 mb-3 px-1 letter-spacing-1">MÜFREDAT AKIŞI</div>
            <VList class="lesson-list rounded-xl border pa-2 bg-white">
              <VListItem
                v-for="(lesson, idx) in course.lessons"
                :key="lesson.id"
                class="rounded-lg mb-1 lesson-item"
              >
                <template #prepend>
                  <div class="order-box me-4">{{ lesson.order }}</div>
                </template>
                
                <VListItemTitle class="font-weight-bold text-slate-700">
                  {{ lesson.title }}
                </VListItemTitle>

                <VListItemSubtitle class="mt-1 d-flex align-center">
                  <VChip
                    size="x-small"
                    :color="lesson.is_premium ? 'amber-darken-2' : 'teal-darken-1'"
                    variant="tonal"
                    class="font-weight-black px-2"
                  >
                    <VIcon :icon="lesson.is_premium ? 'mdi-crown' : 'mdi-lock-open-outline'" size="10" class="me-1" />
                    {{ lesson.is_premium ? 'PREMIUM' : 'ÜCRETSİZ' }}
                  </VChip>
                </VListItemSubtitle>
                
                <template #append>
                  <VBtn
                    icon="mdi-play-circle-outline"
                    variant="tonal"
                    size="small"
                    color="primary"
                    @click="openViewModal(lesson)" 
                  />
                </template>
              </VListItem>
            </VList>
          </VExpansionPanelText>
        </VExpansionPanel>
      </VExpansionPanels>
    </div>
    
    <VCard v-else variant="flat" border class="rounded-xl text-center pa-12 bg-slate-50 border-dashed">
        <VIcon icon="mdi-cloud-off-outline" size="64" color="slate-300" class="mb-4" />
        <div class="text-h6 text-slate-400">Sistemde henüz bir kurs bulunmuyor.</div>
    </VCard>
  </VContainer>

  <VDialog v-model="dialogView" max-width="1000px" transition="dialog-bottom-transition">
    <VCard class="rounded-xl bg-slate-900 border-0 overflow-hidden">
      <VCardTitle class="pa-5 d-flex justify-space-between align-center border-b border-slate-800">
        <div class="d-flex align-center">
            <div class="order-box me-3 bg-primary text-white border-0">{{ lessonToView?.order }}</div>
            <span class="text-h6 text-white font-weight-bold">{{ lessonToView?.title }}</span>
        </div>
        <VBtn icon="mdi-close" variant="tonal" color="white" size="small" @click="dialogView = false" />
      </VCardTitle>
      
      <VCardText class="pa-0">
        <div v-if="lessonToView?.youtube_embed_url" class="video-frame shadow-2xl">
          <iframe
            :src="lessonToView.youtube_embed_url"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            referrerpolicy="strict-origin-when-cross-origin"
          ></iframe>
        </div>
        <div v-else class="text-center py-16 bg-slate-800">
            <VIcon icon="mdi-video-off-outline" size="64" color="white" class="mb-4 opacity-50" />
            <p class="text-white text-h6 font-weight-bold">Video kaynağına erişilemedi.</p>
            <p class="text-slate-400">Lütfen YouTube URL'sini denetleyin.</p>
        </div>
      </VCardText>
    </VCard>
  </VDialog>

  <VDialog v-model="dialogDelete" max-width="450px">
    <VCard class="rounded-xl pa-4 text-center">
      <VCardText class="pt-6">
        <VAvatar color="error-lighten-5" size="70" class="mb-4">
            <VIcon icon="mdi-alert-octagon-outline" color="error" size="40" />
        </VAvatar>
        <h3 class="text-h5 font-weight-black text-slate-800 mb-2">Kalıcı Olarak Silinsin mi?</h3>
        <p class="text-body-1 text-slate-500">
          <strong class="text-slate-900">{{ courseToDelete?.title }}</strong> kursu silinecek. Bu işlem geri alınamaz.
        </p>
        
        <VAlert v-if="courseToDelete?.lessons.length > 0" density="compact" color="error" variant="tonal" class="mt-4 rounded-lg border-0 text-left">
            İçerideki <strong>{{ courseToDelete.lessons.length }} ders</strong> de tamamen kaybolacaktır.
        </VAlert>
      </VCardText>
      
      <VCardActions class="pb-6 px-6 d-flex flex-column gap-2">
        <VBtn block variant="flat" color="error" class="rounded-lg font-weight-bold" size="large" @click="deleteCourseConfirmed">EVET, KURSU SİL</VBtn>
        <VBtn block variant="text" color="slate-400" class="rounded-lg" @click="dialogDelete = false">VAZGEÇ</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>

  <VSnackbar v-model="snackbar" :color="snackbarColor" location="bottom right" elevation="24" rounded="lg">
    <div class="d-flex align-center font-weight-bold">
        <VIcon :icon="snackbarColor === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle'" class="me-3" />
        {{ snackbarMessage }}
    </div>
  </VSnackbar>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:deep(*) { font-family: 'Plus Jakarta Sans', sans-serif !important; }
.text-slate-900 { color: #0f172a; }
.text-slate-800 { color: #1e293b; }
.text-slate-500 { color: #64748b; }
.text-slate-400 { color: #94a3b8; }
.letter-spacing-1 { letter-spacing: 1px; }

/* Stat Kartları */
.stat-card-mini { background: #ffffff; border-radius: 16px !important; border-color: #f1f5f9 !important; transition: all 0.3s ease; }
.stat-card-mini:hover { transform: translateY(-3px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04) !important; }
.stat-icon-mini { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }

/* Kurs Panelleri */
.course-panel { border-radius: 20px !important; border: 1px solid #f1f5f9 !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05) !important; overflow: hidden; transition: all 0.3s ease; }
.course-panel:hover { border-color: #6366f1 !important; box-shadow: 0 15px 25px -5px rgba(0,0,0,0.08) !important; }

.info-badge { background: #f8fafc; padding: 5px 10px; border-radius: 8px; display: flex; align-items: center; font-size: 0.8rem; color: #64748b; font-weight: 700; border: 1px solid #e2e8f0; }
.info-badge.success { background: #f0fdf4; color: #16a34a; border-color: #dcfce7; }

.lesson-item { transition: all 0.2s ease; cursor: default; }
.lesson-item:hover { background: #f8fafc !important; transform: translateX(5px); }

.order-box { width: 26px; height: 26px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800; color: #64748b; }

.video-frame { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; background: #000; }
.video-frame iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }

.leading-relaxed { line-height: 1.6; }
.line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.gap-2 { gap: 8px; }
.gap-3 { gap: 12px; }
</style>