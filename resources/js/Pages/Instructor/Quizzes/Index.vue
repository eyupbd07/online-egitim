<script setup>
import { router } from '@inertiajs/vue3';
const props = defineProps({
    quizzes: Array // Veritabanından gelen test listesi
});
</script>

<template>
  <VContainer>
    <VRow>
      <VCol v-for="quiz in quizzes" :key="quiz.id" cols="12" md="6">
        <VCard border elevation="3">
          <VCardItem>
            <VCardTitle class="text-primary">{{ quiz.title }}</VCardTitle>
            <VCardSubtitle>Kurs: {{ quiz.course?.title }}</VCardSubtitle>
          </VCardItem>

          <VCardText>
            <div class="d-flex align-center font-weight-bold mb-4">
              <VIcon start icon="ri-timer-line" color="info"></VIcon>
              Süre: {{ quiz.duration }} Dakika
            </div>
            
            <VBtn color="primary" block @click="router.visit(route('student.quizzes.show', quiz.id))">
              Sınava Başla
            </VBtn>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </VContainer>
</template>