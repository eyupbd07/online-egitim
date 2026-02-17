<script setup>
// Bu bileşenleri bir sonraki adımlarda oluşturacağız
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

// Controller'dan gelen verileri tanımlıyoruz
defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});
</script>

<template>
    <Head title="Profil Ayarları" />

    <VContainer fluid class="fill-height bg-grey-lighten-5 py-10 font-inter">
        <VRow justify="center">
            <VCol cols="12" md="10" lg="8">
                
                <div class="mb-8">
                    <h1 class="text-h4 font-weight-bold text-grey-darken-4 mb-2">Hesap Ayarları</h1>
                    <p class="text-body-1 text-medium-emphasis">
                        Kişisel bilgilerinizi, güvenliğinizi ve hesap tercihlerinizi yönetin.
                    </p>
                </div>

                <VCard class="rounded-xl border-none shadow-soft mb-6 overflow-hidden" color="white">
                    <div class="d-flex flex-column flex-md-row">
                        <div class="pa-6 pa-md-8 border-b border-md-b-0 border-md-e d-flex flex-column" style="min-width: 300px; max-width: 300px;">
                            <div class="d-flex align-center gap-3 mb-4">
                                <VAvatar color="primary" variant="tonal" rounded size="48">
                                    <VIcon icon="mdi-card-account-details-outline" size="24" />
                                </VAvatar>
                                <span class="text-subtitle-1 font-weight-bold text-grey-darken-3">Profil</span>
                            </div>
                            <h3 class="text-h6 font-weight-bold text-grey-darken-4 mb-2">Kişisel Bilgiler</h3>
                            <p class="text-body-2 text-medium-emphasis mb-0">
                                Hesabınızın görünen adı ve iletişim e-posta adresini buradan güncelleyebilirsiniz.
                            </p>
                        </div>

                        <div class="pa-6 pa-md-8 flex-grow-1">
                            <UpdateProfileInformationForm
                                :must-verify-email="mustVerifyEmail"
                                :status="status"
                            />
                        </div>
                    </div>
                </VCard>

                <VCard class="rounded-xl border-none shadow-soft mb-6 overflow-hidden" color="white">
                    <div class="d-flex flex-column flex-md-row">
                        <div class="pa-6 pa-md-8 border-b border-md-b-0 border-md-e d-flex flex-column" style="min-width: 300px; max-width: 300px;">
                            <div class="d-flex align-center gap-3 mb-4">
                                <VAvatar color="warning" variant="tonal" rounded size="48">
                                    <VIcon icon="mdi-shield-key-outline" size="24" />
                                </VAvatar>
                                <span class="text-subtitle-1 font-weight-bold text-grey-darken-3">Güvenlik</span>
                            </div>
                            <h3 class="text-h6 font-weight-bold text-grey-darken-4 mb-2">Parolayı Güncelle</h3>
                            <p class="text-body-2 text-medium-emphasis mb-0">
                                Hesabınızın güvenliğini sağlamak için uzun ve karmaşık bir parola kullanın.
                            </p>
                        </div>

                        <div class="pa-6 pa-md-8 flex-grow-1">
                            <UpdatePasswordForm />
                        </div>
                    </div>
                </VCard>

                <VCard class="rounded-xl border border-error-thin shadow-soft overflow-hidden" color="white">
                    <div class="d-flex flex-column flex-md-row">
                        <div class="pa-6 pa-md-8 border-b border-md-b-0 border-md-e bg-red-lighten-5 d-flex flex-column" style="min-width: 300px; max-width: 300px;">
                            <div class="d-flex align-center gap-3 mb-4">
                                <VAvatar color="error" variant="tonal" rounded size="48">
                                    <VIcon icon="mdi-alert-octagon-outline" size="24" />
                                </VAvatar>
                                <span class="text-subtitle-1 font-weight-bold text-error">Tehlikeli Bölge</span>
                            </div>
                            <h3 class="text-h6 font-weight-bold text-grey-darken-4 mb-2">Hesabı Sil</h3>
                            <p class="text-body-2 text-medium-emphasis mb-0">
                                Hesabınızı sildiğinizde tüm verileriniz kalıcı olarak yok edilecektir. Bu işlem geri alınamaz.
                            </p>
                        </div>

                        <div class="pa-6 pa-md-8 flex-grow-1">
                            <DeleteUserForm />
                        </div>
                    </div>
                </VCard>

            </VCol>
        </VRow>
    </VContainer>
</template>

<style scoped>
/* Modern Font */
.font-inter { font-family: 'Inter', system-ui, -apple-system, sans-serif !important; }

/* Yumuşak Gölgeler */
.shadow-soft { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important; }

/* İnce Kenarlıklar */
.border-error-thin { border-color: rgba(var(--v-theme-error), 0.2) !important; }

/* Boşluk Yardımcısı */
.gap-3 { gap: 12px; }

/* Responsive Düzenlemeler */
@media (max-width: 960px) {
    .border-md-e { border-right: none !important; }
    .border-md-b-0 { border-bottom: 1px solid rgba(0,0,0,0.12) !important; }
}
</style>