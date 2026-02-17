<script setup>
import { Head, Link } from '@inertiajs/vue3';

// Controller'dan gelen veriyi alıyoruz
defineProps({
  courses: Array,
});

// YouTube Video Resmini Çeken Fonksiyon
const getYouTubeThumbnail = (url) => {
  if (!url) return 'https://via.placeholder.com/640x360?text=No+Video';
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  return (match && match[2].length === 11) 
    ? `https://img.youtube.com/vi/${match[2]}/mqdefault.jpg` 
    : 'https://via.placeholder.com/640x360?text=Video';
};
</script>

<template>
  <Head title="Kurslarım" />

  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardItem>
          <VCardTitle>Kütüphanem</VCardTitle>
          <VCardSubtitle>
            Kayıt olduğunuz tüm eğitimler burada listelenir.
          </VCardSubtitle>
        </VCardItem>
      </VCard>
    </VCol>

    <template v-if="courses && courses.length > 0">
      <VCol
        v-for="course in courses"
        :key="course.id"
        cols="12"
        md="4"
      >
        <VCard height="100%" class="d-flex flex-column">
          
          <VImg
            :src="getYouTubeThumbnail(course.youtube_url)"
            height="200"
            cover
            class="align-end"
            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
          >
            <VCardTitle class="text-white" style="word-break: break-word; white-space: normal; line-height: 1.2;">
              {{ course.title }}
            </VCardTitle>
          </VImg>

          <VCardSubtitle class="pt-4 d-flex align-center">
            <VIcon icon="ri-user-voice-line" size="16" class="me-1" />
            {{ course.instructor ? course.instructor.name : 'Eğitmen Yok' }}
          </VCardSubtitle>

          <VCardText>
            <div v-if="course.category" class="mb-2">
              <VChip size="x-small" color="primary" variant="tonal">
                {{ course.category.name }}
              </VChip>
            </div>
            
            <p class="text-body-2 text-medium-emphasis mb-0 text-truncate-3">
              {{ course.description }}
            </p>
          </VCardText>

          <VSpacer />

          <VCardActions class="pa-4 pt-0">
             <Link :href="route('student.course.show', course.slug)" class="w-100">
                <VBtn
                  color="primary"
                  variant="elevated"
                  block
                >
                  <VIcon start icon="ri-play-circle-line" />
                  Eğitime Başla
                </VBtn>
            </Link>
          </VCardActions>
          </VCard>
      </VCol>
    </template>

    <VCol v-else cols="12">
      <VAlert type="info" variant="tonal" icon="ri-information-line" class="text-center py-5">
        <h3 class="text-h6 mb-2">Henüz kayıtlı kursunuz yok.</h3>
        <p class="mb-4">Kendinizi geliştirmek için kurs kataloğuna göz atabilirsiniz.</p>
        
        <Link :href="route('student.courses.index')">
          <VBtn color="primary" variant="outlined">
            Kursları Keşfet
          </VBtn>
        </Link>
      </VAlert>
    </VCol>

  </VRow>
</template>

<style scoped>
.text-truncate-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>