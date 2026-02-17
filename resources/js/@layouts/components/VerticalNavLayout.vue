<script setup>
import { ref } from 'vue'
import { useDisplay } from 'vuetify'

const drawer = ref(true)
const { mdAndDown } = useDisplay()

// Mobil uyumluluk: Ekrana göre çekmeceyi aç/kapat
const toggleDrawer = () => {
  drawer.value = !drawer.value
}
</script>

<template>
  <VApp>
    <VNavigationDrawer
      v-model="drawer"
      app
      floating
      width="260"
      class="app-navigation-menu"
      :permanent="!mdAndDown"
      :temporary="mdAndDown"
    >
      <div class="d-flex align-center pa-4 pl-6" style="height: 64px;">
        <div style="max-width: 140px; overflow: hidden;">
          <slot name="vertical-nav-header"></slot>
        </div>
      </div>

      <VDivider />

      <div class="pa-2">
         <slot name="vertical-nav-content"></slot>
      </div>
    </VNavigationDrawer>

    <VAppBar app flat height="64" class="px-4" style="background: transparent;">
      <VCard class="w-100 rounded-lg" elevation="2" style="margin-top: 10px;">
        <div class="d-flex align-center px-4" style="height: 64px;">
           <VBtn icon v-if="mdAndDown" @click="toggleDrawer" class="me-2">
             <VIcon>mdi-menu</VIcon>
           </VBtn>
           
           <slot name="navbar" :toggleVerticalOverlayNavActive="toggleDrawer"></slot>
        </div>
      </VCard>
    </VAppBar>

    <VMain style="background-color: #F4F5FA; min-height: 100vh;">
      <VContainer fluid class="pa-6">
        <slot />
      </VContainer>
    </VMain>

    <VFooter app absolute color="transparent" class="px-6 py-4">
      <slot name="footer"></slot>
    </VFooter>
  </VApp>
</template>

<style lang="scss" scoped>
.app-navigation-menu {
  background-color: #fff;
  border-right: none;
  box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
}
</style>