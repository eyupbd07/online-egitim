<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

// NOT: Eğer projenizde global bir Layout tanımlı değilse, 
// Sidebar'ın düzgün görünmesi için Layout dosyanızı import edip buraya ekleyin.
// import InstructorLayout from '@/Layouts/InstructorLayout.vue';

// defineOptions({ layout: InstructorLayout }) 

const props = defineProps({
  courses: {
    type: Object,
    default: () => ({ data: [] }),
  }
})

const search = ref('')

// Veriyi hem Pagination (Laravel paginate) hem de Collection (get) ile uyumlu hale getirme
const courseList = computed(() => {
    return Array.isArray(props.courses) ? props.courses : props.courses.data || [];
})

// Tablo Başlıkları
const headers = [
  { title: 'KURS BİLGİSİ', key: 'title', width: '350px' },
  { title: 'FİYAT', key: 'price', align: 'center' },
  { title: 'DURUM', key: 'status', align: 'center' },
  { title: 'ÖĞRENCİ', key: 'students_count', align: 'center' },
  { title: 'TARİH', key: 'created_at', align: 'end' },
  { title: 'İŞLEMLER', key: 'actions', sortable: false, align: 'end' },
]

// Silme İşlemi
const deleteCourse = (id) => {
  if (confirm('Bu kursu ve tüm içeriğini silmek istediğinize emin misiniz?')) {
    router.delete(route('instructor.courses.destroy', id), {
        onSuccess: () => {
            // İsteğe bağlı: Başarılı silme bildirimi (Toast) tetiklenebilir
        }
    })
  }
}

// Düzenleme Sayfasına Git
const editCourse = (id) => {
    router.get(route('instructor.courses.edit', id));
}

// Renk ve Metin Yardımcıları
const getStatusColor = (status) => {
  // Hem boolean (1/0) hem string ('published') kontrolü
  if (status === 'published' || status === 1 || status === true) return 'success'
  return 'warning'
}

const getStatusText = (status) => {
  if (status === 'published' || status === 1 || status === true) return 'Yayında'
  return 'Taslak'
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('tr-TR', { day: 'numeric', month: 'long', year: 'numeric' }).format(date)
}
</script>

<template>
  <Head title="Kurs Yönetimi" />

  <VContainer fluid class="py-8 px-6 h-100 bg-grey-lighten-5">
    
    <VRow align="center" justify="space-between" class="mb-6">
      <VCol cols="12" md="8">
        <div>
          <h2 class="text-h4 font-weight-bold text-high-emphasis mb-1">
            Kurs Yönetimi
          </h2>
          <p class="text-body-1 text-medium-emphasis mb-0">
            Eğitim içeriklerinizi buradan yönetebilir, düzenleyebilir ve yayın durumlarını değiştirebilirsiniz.
          </p>
        </div>
      </VCol>
      <VCol cols="12" md="4" class="text-md-end text-start">
        <VBtn
          height="48"
          class="text-none font-weight-bold text-white bg-gradient-primary rounded-lg"
          prepend-icon="mdi-plus-circle-outline"
          :href="route('instructor.courses.create')"
          elevation="4"
        >
          Yeni Kurs Oluştur
        </VBtn>
      </VCol>
    </VRow>

    <VCard elevation="0" class="rounded-xl border custom-card-shadow bg-white">
      
      <VCardText class="pa-5">
        <VRow align="center">
          <VCol cols="12" sm="4">
            <VTextField
              v-model="search"
              prepend-inner-icon="mdi-magnify"
              label="Kurslarda ara..."
              placeholder="Kurs adı yazın..."
              variant="outlined"
              density="compact"
              hide-details
              class="rounded-lg"
              bg-color="grey-lighten-5"
            />
          </VCol>
          <VCol cols="12" sm="8" class="d-flex justify-sm-end gap-2">
            <VBtn variant="tonal" color="secondary" prepend-icon="mdi-filter-variant">
              Filtrele
            </VBtn>
            <VBtn variant="tonal" color="secondary" icon="mdi-refresh" @click="router.reload()" />
          </VCol>
        </VRow>
      </VCardText>

      <VDivider />

      <VDataTable
        :headers="headers"
        :items="courseList" 
        :search="search"
        hover
        class="modern-table text-no-wrap"
      >
        <template #item.title="{ item }">
          <div class="d-flex align-center py-3">
            <VAvatar rounded="lg" size="64" class="me-4 elevation-1 bg-grey-lighten-4">
              <VImg 
                v-if="item.image" 
                :src="item.image" 
                cover 
                class="rounded-lg"
              />
              <VIcon v-else icon="mdi-image-off-outline" color="grey-darken-1" />
            </VAvatar>
            <div>
              <div 
                class="text-subtitle-1 font-weight-bold text-high-emphasis cursor-pointer hover-link text-truncate"
                style="max-width: 250px;"
                @click="editCourse(item.id)"
              >
                {{ item.title }}
              </div>
              <VChip size="x-small" label class="mt-1" color="primary" variant="tonal">
                {{ item.category?.name || 'Genel' }}
              </VChip>
            </div>
          </div>
        </template>

        <template #item.price="{ item }">
          <div class="font-weight-bold">
             <span v-if="item.price > 0">{{ item.price }} ₺</span>
             <span v-else class="text-success">Ücretsiz</span>
          </div>
        </template>

        <template #item.status="{ item }">
            <VChip
                :color="getStatusColor(item.status || item.is_published)"
                variant="flat"
                size="small"
                class="font-weight-medium"
            >
                {{ getStatusText(item.status || item.is_published) }}
            </VChip>
        </template>

        <template #item.students_count="{ item }">
            <div class="d-flex align-center justify-center gap-1 text-medium-emphasis">
                <VIcon icon="mdi-account-group" size="18" />
                <span>{{ item.students_count || 0 }}</span>
            </div>
        </template>

        <template #item.created_at="{ item }">
            <span class="text-caption text-medium-emphasis font-weight-medium">
                {{ formatDate(item.created_at) }}
            </span>
        </template>

        <template #item.actions="{ item }">
          <div class="d-flex justify-end">
             <VBtn
                icon
                variant="text"
                color="primary"
                size="small"
                class="me-1"
                @click="editCourse(item.id)"
              >
                  <VIcon icon="mdi-pencil-outline" size="22" />
                  <VTooltip activator="parent" location="top">Düzenle</VTooltip>
              </VBtn>

            <VBtn
              icon
              variant="text"
              color="error"
              size="small"
              @click="deleteCourse(item.id)"
            >
                <VIcon icon="mdi-delete-outline" size="22" />
                <VTooltip activator="parent" location="top">Sil</VTooltip>
            </VBtn>
          </div>
        </template>

        <template #no-data>
           <div class="text-center py-10">
             <VAvatar color="primary" variant="tonal" size="80" class="mb-4">
                 <VIcon icon="mdi-book-open-page-variant-outline" size="40" />
             </VAvatar>
             <h3 class="text-h6 font-weight-medium text-high-emphasis">Henüz kurs oluşturmadınız</h3>
             <p class="text-body-2 text-medium-emphasis mb-6">Bilgilerinizi paylaşmaya başlamak için ilk kursunuzu oluşturun.</p>
             <VBtn 
                color="primary" 
                variant="flat" 
                :href="route('instructor.courses.create')"
                prepend-icon="mdi-plus"
            >
               İlk Kursunu Oluştur
             </VBtn>
           </div>
        </template>

      </VDataTable>
    </VCard>
  </VContainer>
</template>

<style scoped>
/* Gradient Buton Stili (Materio Template Benzeri) */
.bg-gradient-primary {
  background: linear-gradient(98deg, #C6A7FE, #9155FD 94%);
  transition: all 0.3s ease;
  border: none;
}
.bg-gradient-primary:hover {
    box-shadow: 0 5px 15px rgba(145, 85, 253, 0.4) !important;
    transform: translateY(-2px);
}

/* Modern Tablo İyileştirmeleri */
.modern-table :deep(th) {
    font-size: 0.75rem !important;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6c757d !important; /* Standart gri */
    font-weight: 600 !important;
    background-color: #f8f9fa !important; /* Hafif gri başlık arkaplanı */
    height: 56px !important;
    border-bottom: 1px solid #e9ecef !important;
}

.modern-table :deep(td) {
    height: 80px !important; /* Görseller için daha ferah satırlar */
    border-bottom: 1px solid #f1f3f5 !important;
}

.hover-link {
    transition: color 0.2s;
}
.hover-link:hover {
    color: #9155FD !important; /* Primary renk */
    text-decoration: none;
}

/* Kart Gölgesi */
.custom-card-shadow {
    box-shadow: 0 4px 18px -4px rgba(76, 78, 100, 0.1) !important;
    border: 1px solid rgba(76, 78, 100, 0.12);
}

/* Arkaplan düzeltmesi (Vuetify temasına bağlı olarak) */
.bg-grey-lighten-5 {
    background-color: #f9fafb !important;
}
</style>