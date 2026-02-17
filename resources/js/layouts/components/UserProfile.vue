<script setup>
import { router, Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user || { name: 'Kullanıcı' })

// Link Hatasını Önlemek İçin Güvenli Rota Kontrolü
const resolveRoute = (name) => {
  try {
    return route(name)
  } catch (e) {
    return '#' // Rota yoksa boş link ver (Hata vermez)
  }
}

const logout = () => {
  router.post(route('logout'))
}
</script>

<template>
  <VMenu width="230" location="bottom end" offset="14px">
    <template #activator="{ props }">
      <VAvatar v-bind="props" color="primary" variant="tonal" class="cursor-pointer" size="38">
        <span class="font-weight-bold">{{ user.name.charAt(0).toUpperCase() }}</span>
      </VAvatar>
    </template>

    <VList>
      <VListItem class="pb-2">
        <template #prepend>
           <VAvatar color="primary" variant="tonal" size="40">
             <span>{{ user.name.charAt(0).toUpperCase() }}</span>
           </VAvatar>
        </template>
        <VListItemTitle class="font-weight-bold">{{ user.name }}</VListItemTitle>
        <VListItemSubtitle>Aktif</VListItemSubtitle>
      </VListItem>
      
      <VDivider class="my-2" />

      <VListItem :href="resolveRoute('profile.edit')" component="a" title="Profilim" prepend-icon="mdi-account-outline" />
      <VListItem href="#" title="Ayarlar" prepend-icon="mdi-cog-outline" />
      
      <VDivider class="my-2" />

      <VListItem title="Çıkış Yap" prepend-icon="mdi-logout" base-color="error" @click="logout" class="cursor-pointer" />
    </VList>
  </VMenu>
</template>