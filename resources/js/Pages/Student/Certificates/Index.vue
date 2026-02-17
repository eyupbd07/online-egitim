<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    certificates: Array
});

// PDF İndirme Linki
const getDownloadLink = (code) => route('student.certificates.download', code);
</script>

<template>
    <Head title="Sertifikalarım" />

    <VContainer class="py-10">
        <h2 class="text-h4 mb-6 font-weight-bold text-primary">
            <VIcon icon="ri-award-fill" start /> Sertifikalarım
        </h2>

        <VRow v-if="certificates && certificates.length > 0">
            <VCol v-for="cert in certificates" :key="cert.id" cols="12" md="6" lg="4">
                <VCard elevation="3" class="rounded-lg border">
                    <VCardItem class="bg-primary text-white">
                        <VCardTitle class="text-h6 font-weight-bold">
                            {{ cert.course ? cert.course.title : 'Eğitim Sertifikası' }}
                        </VCardTitle>
                        <VCardSubtitle class="text-white opacity-80">
                            {{ new Date(cert.issued_at).toLocaleDateString('tr-TR') }}
                        </VCardSubtitle>
                    </VCardItem>

                    <VCardText class="pt-4">
                        <VChip color="success" class="mb-3" label>
                            <VIcon start icon="ri-checkbox-circle-line"></VIcon>
                            Başarıyla Tamamlandı
                        </VChip>
                        <div class="text-body-2 text-grey-darken-1">
                            <strong>Sertifika Kodu:</strong> {{ cert.certificate_code }}
                        </div>
                    </VCardText>

                    <VCardActions class="pa-4">
                        <VBtn 
                            :href="getDownloadLink(cert.certificate_code)"
                            color="primary" 
                            variant="elevated" 
                            block
                            prepend-icon="ri-download-cloud-2-line"
                        >
                            PDF Görüntüle / İndir
                        </VBtn>
                    </VCardActions>
                </VCard>
            </VCol>
        </VRow>

        <VAlert v-else type="info" variant="tonal" border="start" class="mt-4" icon="ri-information-line">
            <div class="text-h6">Henüz sertifikanız yok.</div>
            <div>Sertifika kazanmak için kurslarınızdaki sınavları başarıyla tamamlamanız gerekmektedir.</div>
        </VAlert>
    </VContainer>
</template>