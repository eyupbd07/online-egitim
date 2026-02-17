<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// Controller'dan gelen prop'ları tanımlıyoruz
defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

// Inertia'nın usePage() hook'u ile paylaşılan 'auth.user' verisine erişiyoruz
const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const verificationLinkSent = ref(false);

// Formu gönderme fonksiyonu
const submit = () => {
    // 'profile.update' rotasına (routes/auth.php içinde tanımlı) PATCH isteği atar
    form.patch(route('profile.update'), {
        preserveScroll: true, // Sayfa kaydırma konumunu koru
        onSuccess: () => {
            verificationLinkSent.value = false;
        },
    });
};

const sendVerification = () => {
    verificationLinkSent.value = true;
};
</script>

<template>
    <section>
        <VForm @submit.prevent="submit">
            <VRow>
                <!-- İsim Alanı -->
                <VCol cols="12">
                    <VTextField
                        v-model="form.name"
                        label="İsim"
                        :error-messages="form.errors.name"
                        autocomplete="name"
                    />
                </VCol>

                <!-- Email Alanı -->
                <VCol cols="12">
                    <VTextField
                        v-model="form.email"
                        label="Email"
                        type="email"
                        :error-messages="form.errors.email"
                        autocomplete="email"
                    />
                </VCol>
      <!-- Kaydet Butonu ve Başarı Mesajı -->
                <VCol cols="12" class="d-flex align-center gap-4">
                    <VBtn type="submit" :loading="form.processing" :disabled="form.processing">
                        Kaydet
                    </VBtn>
                    <VAlert 
                        v-if="form.recentlySuccessful" 
                        type="success" 
                        density="compact"
                        variant="tonal"
                    >
                        Kaydedildi.
                    </VAlert>
                </VCol>
            </VRow>
        </VForm>
    </section>
</template>

