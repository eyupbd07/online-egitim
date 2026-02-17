<script setup>
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

// Onay modalının (dialog) açık olup olmadığını takip eder
const confirmingUserDeletion = ref(false);
const passwordInput = ref(null); // Parola inputuna odaklanmak için

// Hesap silme formunu başlatıyoruz (sadece parola gerekli)
const form = useForm({
    password: '',
});

// "Hesabı Sil" butonuna tıklandığında bu fonksiyon çalışır
const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true; // Modalı aç
    
    // Modal açıldıktan sonra parola inputuna odaklan
    nextTick(() => passwordInput.value.focus());
};

// Modal içindeki "Hesabı Sil" onay butonuna tıklandığında bu çalışır
const deleteUser = () => {
    // 'profile.destroy' rotasına (routes/auth.php içinde tanımlı) DELETE isteği atar
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(), // Başarılı olursa modalı kapat
        onError: () => passwordInput.value.focus(), // Hata olursa parolaya odaklan
        onFinish: () => form.reset(), // İşlem bitince formu temizle
    });
};

// Modalı kapatma fonksiyonu
const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <!-- Ana "Hesabı Sil" butonu -->
        <VBtn color="error" @click="confirmUserDeletion">Hesabı Sil</VBtn>

        <!-- Onay Modalı (VDialog) -->
        <VDialog v-model="confirmingUserDeletion" max-width="500" persistent>
            <VCard>
                <VCardTitle class="text-h5">
                    Hesabınızı silmek istediğinizden emin misiniz?
                </VCardTitle>
                <VCardText>
                    Hesabınız silindikten sonra, tüm kaynakları ve verileri kalıcı olarak silinecektir. Lütfen hesabınızı kalıcı olarak silmek istediğinizi onaylamak için parolanızı girin.
                </VCardText>
                
                <!-- Parola Giriş Alanı -->
                <VCardText>
                    <VTextField
                        v-model="form.password"
                        ref="passwordInput"
                        label="Parola"
                        type="password"
                        :error-messages="form.errors.password"
                        @keyup.enter="deleteUser"
                    />
                </VCardText>

                <!-- Modal Eylem Butonları -->
                <VCardActions>
                    <VSpacer />
                    <VBtn color="secondary" variant="tonal" @click="closeModal">İptal</VBtn>
                    <VBtn
                        color="error"
                        variant="elevated"
                        :loading="form.processing"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Hesabı Sil
                    </VBtn>
                </VCardActions>
            </VCard>
        </VDialog>
    </section>
</template>

