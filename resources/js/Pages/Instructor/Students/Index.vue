<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  students: Array,
});

const search = ref('');

const filteredStudents = computed(() => {
  if (!search.value) return props.students;
  const term = search.value.toLowerCase();
  return props.students.filter(student => 
    student.name.toLowerCase().includes(term) || student.email.toLowerCase().includes(term)
  );
});
</script>

<template>
  <Head title="Öğrenci Yönetimi" />

  <VContainer class="py-8 px-6 bg-slate-50 min-h-screen" fluid>
    <header class="mb-10 d-flex flex-wrap justify-space-between align-center gap-6">
      <div>
        <div class="d-flex align-center mb-1 text-primary font-weight-black text-overline">
          <VIcon icon="mdi-account-star-outline" class="me-2" size="small" />
          Eğitmen Portalı
        </div>
        <h1 class="text-h3 font-weight-black text-slate-900">Öğrencilerim</h1>
        <p class="text-subtitle-1 text-slate-500 mt-1">Öğrenci başarılarını ve sertifika durumlarını anlık takip edin.</p>
      </div>

      <div class="search-wrapper">
        <VTextField
          v-model="search"
          prepend-inner-icon="mdi-magnify"
          label="Öğrenci Ara..."
          variant="solo"
          flat
          hide-details
          class="modern-search rounded-xl"
        />
      </div>
    </header>

    <VCard elevation="0" border class="rounded-xl overflow-hidden shadow-sm">
      <VTable class="modern-table">
        <thead>
          <tr>
            <th class="text-overline font-weight-black px-8">KİMLİK BİLGİLERİ</th>
            <th class="text-overline font-weight-black text-center">BAŞARI (SERTİFİKA)</th>
            <th class="text-overline font-weight-black">KAYITLI KURSLAR</th>
            <th class="text-overline font-weight-black">KATILIM</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in filteredStudents" :key="student.id" class="student-row">
            <td class="px-8 py-4">
              <div class="d-flex align-center">
                <VAvatar color="indigo-lighten-5" class="me-4 shadow-sm" size="48">
                  <span class="text-h6 font-weight-bold text-indigo">{{ student.name.charAt(0).toUpperCase() }}</span>
                </VAvatar>
                <div>
                  <div class="text-subtitle-1 font-weight-bold text-slate-800 leading-tight">{{ student.name }}</div>
                  <div class="text-caption text-slate-400 mt-1">{{ student.email }}</div>
                </div>
              </div>
            </td>

            <td class="text-center">
              <div class="d-inline-flex align-center pa-2 rounded-pill shadow-sm border" 
                   :class="student.certificate_count > 0 ? 'bg-amber-lighten-5 border-amber-lighten-3' : 'bg-slate-50 border-slate-200'">
                <VIcon :icon="student.certificate_count > 0 ? 'mdi-medal' : 'mdi-medal-outline'" 
                       :color="student.certificate_count > 0 ? 'amber-darken-2' : 'slate-300'" 
                       size="20" class="me-2" />
                <span class="text-body-2 font-weight-black" :class="student.certificate_count > 0 ? 'text-amber-darken-4' : 'text-slate-400'">
                  {{ student.certificate_count }} Sertifika
                </span>
              </div>
            </td>

            <td>
              <div class="d-flex flex-wrap gap-2 py-2" style="max-width: 300px;">
                <VChip v-for="course in student.courses" :key="course.id"
                       size="x-small" variant="flat" color="indigo-lighten-5" 
                       class="text-indigo-darken-3 font-weight-bold px-3">
                  {{ course.title }}
                </VChip>
              </div>
            </td>

            <td class="text-slate-500 font-weight-medium text-body-2">
              <VIcon icon="mdi-calendar-check" size="16" class="me-2 text-slate-300" />
              {{ student.joined_at }}
            </td>
          </tr>

          <tr v-if="filteredStudents.length === 0">
            <td colspan="4" class="text-center pa-16">
              <VIcon icon="mdi-account-search-outline" size="80" color="slate-200" class="mb-4" />
              <div class="text-h6 text-slate-400 font-weight-bold">Aradığınız öğrenci bulunamadı.</div>
            </td>
          </tr>
        </tbody>
      </VTable>
    </VCard>
  </VContainer>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:deep(*) { font-family: 'Plus Jakarta Sans', sans-serif !important; }

.bg-slate-50 { background-color: #f8fafc; }
.text-slate-900 { color: #0f172a; }
.text-slate-800 { color: #1e293b; }
.text-slate-500 { color: #64748b; }
.text-slate-400 { color: #94a3b8; }
.letter-spacing-1 { letter-spacing: 1px; }

.search-wrapper { width: 350px; }
.modern-search { border: 1px solid #e2e8f0; }

.modern-table thead th {
  background: #f8fafc !important;
  height: 64px !important;
  border-bottom: 2px solid #f1f5f9 !important;
  color: #64748b !important;
}

.student-row { transition: all 0.2s ease; cursor: default; }
.student-row:hover { background-color: #f1f5f9 !important; }

.gap-2 { gap: 8px; }
</style>