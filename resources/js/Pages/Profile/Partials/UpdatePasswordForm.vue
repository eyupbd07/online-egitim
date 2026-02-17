<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Parola güncelleme formunu başlatıyoruz
const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// Hata durumunda inputlara odaklanmak (focus) için referanslar
const passwordInput = ref(null);
const currentPasswordInput = ref(null);

// Formu gönderme fonksiyonu
const updatePassword = () => {
    // 'password.update' rotasına (routes/auth.php içinde tanımlı) PUT isteği atar
    form.put(route('password.update'), {
        preserveScroll: true, // Sayfa kaydırma konumunu koru
        onSuccess: () => form.reset(), // Başarılı olursa formu temizle
        onError: () => {
            // Hata durumunda ilgili inputa odaklan
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <VForm @submit.prevent="updatePassword">
            <VRow>
                <!-- Mevcut Parola -->
                <VCol cols="12">
                    <VTextField
                        v-model="form.current_password"
                        ref="currentPasswordInput"
                        label="Mevcut Parola"
                        type="password"
                        :error-messages="form.errors.current_password"
                        autocomplete="current-password"
                    />
                </VCol>

                <!-- Yeni Parola -->
                <VCol cols="12">
                    <VTextField
                        v-model="form.password"
                        ref="passwordInput"
                        label="Yeni Parola"
                        type="password"
                        :error-messages="form.errors.password"
                        autocomplete="new-password"
                    />
                </VCol>

                <!-- Yeni Parola (Tekrar) -->
                <VCol cols="12">
                    <VTextField
                        v-model="form.password_confirmation"
                        label="Yeni Parola (Tekrar)"
                        type="password"
                        :error-messages="form.errors.password_confirmation"
                        autocomplete="new-password"
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

