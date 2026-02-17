<script setup>
// Inertia'nın form yönetim aracını (useForm) import ediyoruz.
// Bu, v-model, validasyon hataları ve 'loading' durumlarını
// bizim için otomatik olarak yönetir.
import { useForm } from '@inertiajs/vue3';

// Formumuzun verilerini tutacak bir 'form' nesnesi oluşturuyoruz.
// Alanlar, veritabanı sütunlarımızla eşleşmelidir.
const form = useForm({
  title: '',
  description: '',
  // category_id: null, // (Bunu daha sonra ekleyeceğiz)
  // status: 'draft',   // (Bunu daha sonra ekleyeceğiz)
});

// "Kaydet" butonuna basıldığında çalışacak fonksiyon.
const submit = () => {
  // Form verilerini, Adım 2'de oluşturduğumuz rotaya (POST /admin/courses) gönder.
  // Bu rota, CourseController'daki 'store' metodunu tetikleyecek.
  form.post(route('admin.courses.store'), {
    onSuccess: () => {
      // Başarıyla kaydedilirse formu temizle
      form.reset();
      // (Buraya bir "Başarılı!" bildirimi de ekleyebiliriz)
    },
    // onError: (errors) => { ... } // Hata yönetimi (Inertia hallediyor)
  });
};
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Yeni Kurs Oluştur">
        <VCardText>
          <!-- 
            Formu @submit.prevent ile 'submit' fonksiyonumuza bağlıyoruz.
            .prevent, sayfanın yeniden yüklenmesini engeller.
          -->
          <form @submit.prevent="submit">
            <VRow>
              <!-- Kurs Başlığı Alanı -->
              <VCol cols="12">
                <VTextField
                  v-model="form.title"
                  label="Kurs Başlığı"
                  placeholder="Örn: Başlangıç Seviye PHP"
                  :error-messages="form.errors.title" 
                />
              </VCol>

              <!-- Kurs Açıklaması Alanı -->
              <VCol cols="12">
                <VTextarea
                  v-model="form.description"
                  label="Kurs Açıklaması"
                  placeholder="Kursa ait kısa bir açıklama girin..."
                  :error-messages="form.errors.description"
                />
              </VCol>

              <!-- Kaydet Butonu -->
              <VCol cols="12" class="d-flex gap-4">
                <VBtn type="submit" :disabled="form.processing">
                  Kaydet
                </VBtn>
                
                <VBtn
                  type="reset"
                  color="secondary"
                  variant="tonal"
                  @click="form.reset()"
                >
                  Temizle
                </VBtn>
              </VCol>
            </VRow>
          </form>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

