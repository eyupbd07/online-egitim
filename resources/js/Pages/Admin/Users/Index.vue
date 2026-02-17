<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
  users: Array,
  flash: Object,
});

const roleTranslations = {
  admin: 'Yönetici',
  instructor: 'Eğitmen',
  student: 'Öğrenci',
};

const getRoleTranslation = (role) => {
  return roleTranslations[role] || role; 
};

const getRoleChipColor = (role) => {
  if (role === 'admin') return 'error';
  if (role === 'instructor') return 'warning';
  if (role === 'student') return 'success';
  return 'secondary';
};
const dialogDelete = ref(false); // Onay kutusu açık mı?
const userToDelete = ref(null); // Hangi kullanıcıyı siliyoruz?

const openDeleteConfirm = (user) => {
  userToDelete.value = user; // Hangi kullanıcının silineceğini hatırla
  dialogDelete.value = true; // Onay kutusunu aç
};

const deleteUserConfirmed = () => {


  const deleteUrl = `/admin/users/${userToDelete.value.id}`;
  
  router.post(deleteUrl, {
    _method: 'delete', // Laravel'e bunun bir DELETE isteği olduğunu söylüyoruz
  }, {
    onSuccess: () => {
       dialogDelete.value = false; // Onay kutusunu kapat
       userToDelete.value = null; // Hafızayı temizle
    },
    onError: () => {
       dialogDelete.value = false;
       userToDelete.value = null;
    }
  });
};

const snackbar = ref(false); // Bildirim açık mı?
const snackbarMessage = ref(''); // Bildirim mesajı
const snackbarColor = ref('success'); // Bildirim rengi (success/error)
const page = usePage();


watch(() => page.props.flash, (flash) => {
  if (flash && flash.message) {
    snackbarMessage.value = flash.message;
    snackbarColor.value = 'success';
    snackbar.value = true;
  } else if (flash && flash.error) {
    snackbarMessage.value = flash.error;
    snackbarColor.value = 'error';
    snackbar.value = true;
  }
}, { 
  immediate: true, 
  deep: true 
});

</script>

<template>
  <Head title="Kullanıcı Yönetimi" />

  <!-- Vuetify Kart Bileşeni -->
  <VCard>
    <VCardTitle>
      Kullanıcı Yönetimi
    </VCardTitle>
    <VCardText>
      Sistemdeki tüm kullanıcıları buradan yönetebilirsiniz.
    </VCardText>

    <!-- Vuetify Tablo Bileşeni -->
    <VTable
      hover
      class="text-no-wrap"
    >
      <thead>
        <tr>
          <th class="text-uppercase">ID</th>
          <th class="text-uppercase">İsim</th>
          <th class="text-uppercase">Email</th>
          <th class="text-uppercase">Rol</th>
          <th class="text-uppercase">Kayıt Tarihi</th>
          <th class="text-uppercase text-center">Eylemler</th>
        </tr>
      </thead>

      <tbody>
        <!-- 
          Controller'dan gelen 'users' dizisi içinde v-for döngüsü 
          ile her bir kullanıcı için bir tablo satırı (<tr>) oluşturuyoruz.
        -->
        <tr
          v-for="user in users"
          :key="user.id"
        >
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>
            <VChip
              :color="getRoleChipColor(user.role)"
              size="small"
              label
            >
              {{ getRoleTranslation(user.role) }}
            </VChip>
          </td>
          <td>{{ user.created_at }}</td>
          <td class="text-center">
            <!-- Düzenle Butonu -->
            <VBtn
              :component="Link"
              :href="route('admin.users.edit', user.id)"
              color="primary"
              size="small"
              class="me-2"
            >
              Düzenle
            </VBtn>
            
            <VBtn
              @click="openDeleteConfirm(user)"
              color="error"
              size="small"
            >
              Sil
            </VBtn>
          </td>
        </tr>
      </tbody>
    </VTable>
  </VCard>

  <VDialog
    v-model="dialogDelete"
    max-width="500px"
    persistent
  >
    <VCard>
      <VCardTitle class="text-h5">
        Kullanıcıyı Sil
      </VCardTitle>
      <VCardText>
        <span class="text-primary">{{ userToDelete?.name }}</span>
        adlı kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
      </VCardText>
      <VCardActions>
        <VSpacer />
        <VBtn
          color="secondary"
          variant="tonal"
          @click="dialogDelete = false"
        >
          İptal
        </VBtn>
        <VBtn
          color="error"
          variant="elevated"
          @click="deleteUserConfirmed"
        >
          Evet, Sil
        </VBtn>
        <VSpacer />
      </VCardActions>
    </VCard>
  </VDialog>

  <VSnackbar
    v-model="snackbar"
    :color="snackbarColor"
    location="top right"
    :timeout="3000"
  >
    {{ snackbarMessage }}
    <template #actions>
      <VBtn
        color="white"
        variant="text"
        @click="snackbar = false"
      >
        Kapat
      </VBtn>
    </template>
  </VSnackbar>
</template>

