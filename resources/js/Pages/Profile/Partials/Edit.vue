<script setup>
// --- DEĞİŞİKLİK BURADA ---
// Dosyaların @/Pages/Profile/Partials/ içinde olduğunu varsayıyoruz.
// Eğer dosyalarınız @/Pages/Profile/ içindeyse, './Partials/' kısmını silin.
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
// --- DEĞİŞİKLİK SONU ---
import { Head } from '@inertiajs/vue3';

// Controller'dan gelen verileri tanımlıyoruz
defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Profilim" />

    <VRow>
        <!-- 1. Kart: Profil Bilgileri -->
        <VCol cols="12">
            <VCard title="Profil Bilgileri">
                <VCardText>
                    Hesabınızın profil bilgilerini ve e-posta adresini güncelleyin.
                </VCardText>
                <VCardText>
                    <!-- Bu bileşeni bir sonraki adımda oluşturacağız -->
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    />
                </VCardText>
            </VCard>
        </VCol>

        <!-- 2. Kart: Parola Güncelleme -->
        <VCol cols="12">
            <VCard title="Parolayı Güncelle">
                <VCardText>
                    Güvende kalmak için uzun ve rastgele bir parola kullandığınızdan emin olun.
                </VCardText>
                <VCardText>
                    <!-- Bu bileşeni daha sonra oluşturacağız -->
                    <UpdatePasswordForm />
                </VCardText>
            </VCard>
        </VCol>

        <!-- 3. Kart: Hesap Silme -->
        <VCol cols="12">
            <VCard title="Hesabı Sil">
                <VCardText>
                    Hesabınız silindikten sonra, tüm kaynakları ve verileri kalıcı olarak silinecektir.
                </VCardText>
                <VCardText>
                    <!-- Bu bileşeni daha sonra oluşturacağız -->
                    <DeleteUserForm />
                </VCardText>
            </VCard>
        </VCol>
    </VRow>
</template>

