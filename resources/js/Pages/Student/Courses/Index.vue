<script setup>
import { Head, useForm } from '@inertiajs/vue3';

// Controller'dan gelen kurs listesi
defineProps({
  courses: Array,
});

// Kayıt işlemi için form
const form = useForm({});

// Kursa kaydolma fonksiyonu
const enroll = (courseId) => {
  // 'student.courses.enroll' rotasına POST isteği atıyoruz
  form.post(route('student.courses.enroll', courseId), {
    preserveScroll: true,
  });
};

// YouTube Video ID'sini çıkaran yardımcı fonksiyon (Küçük resim için)
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
  <Head title="Kurs Kataloğu" />

  <VRow>
    <VCol cols="12">
      <VCard title="Kurs Kataloğu">
        <VCardText>
          Mevcut tüm kursları buradan inceleyebilir ve eğitim yolculuğunuza hemen başlayabilirsiniz.
        </VCardText>
      </VCard>
    </VCol>

    <!-- Kurs Kartları -->
    <VCol
      v-for="course in courses"
      :key="course.id"
      cols="12"
      md="4"
    >
      <VCard height="100%" class="d-flex flex-column">
        <!-- Kurs Görseli (YouTube Küçük Resmi) -->
        <VImg
          :src="getYouTubeThumbnail(course.youtube_url)"
          height="200"
          cover
          class="align-end"
          gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
        >
          <VCardTitle class="text-white" style="word-break: break-word; white-space: normal;">
            {{ course.title }}
          </VCardTitle>
        </VImg>

        <VCardSubtitle class="pt-4 d-flex align-center">
          <VIcon icon="ri-user-voice-line" size="16" class="me-1" />
          {{ course.instructor_name }}
        </VCardSubtitle>

        <VCardText>
          <div class="d-flex align-center mb-2">
            <VIcon icon="ri-file-list-3-line" size="16" class="me-1" />
            <span class="text-caption">{{ course.lessons_count }} Ders</span>
          </div>
          <p class="text-body-2 text-medium-emphasis mb-0 text-truncate-3">
            {{ course.description }}
          </p>
        </VCardText>

        <VSpacer />

        <VCardActions class="pa-4 pt-0">
          <!-- Eğer zaten kayıtlıysa butonu pasif yap -->
          <VBtn
            v-if="course.is_enrolled"
            color="success"
            variant="tonal"
            block
            disabled
            prepend-icon="ri-checkbox-circle-line"
          >
            Kayıtlısınız
          </VBtn>

          <!-- Değilse Kaydol butonu göster -->
          <VBtn
            v-else
            color="primary"
            variant="elevated"
            block
            :loading="form.processing"
            :disabled="form.processing"
            @click="enroll(course.id)"
          >
            Hemen Kaydol
          </VBtn>
        </VCardActions>
      </VCard>
    </VCol>
    
    <!-- Hiç kurs yoksa -->
    <VCol v-if="courses.length === 0" cols="12">
        <VAlert type="info" variant="tonal" icon="ri-information-line">
          Şu anda yayında olan bir kurs bulunmamaktadır. Lütfen daha sonra tekrar kontrol ediniz.
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