<script setup>
// Nokta atışı import: Yanındaki components klasörüne bak diyoruz
import VerticalNavLayout from './components/VerticalNavLayout.vue'
import NavItems from './components/NavItems.vue'
import NavbarThemeSwitcher from './components/NavbarThemeSwitcher.vue'
import UserProfile from './components/UserProfile.vue'
</script>

<template>
  <VerticalNavLayout>
    
    <template #vertical-nav-header>
       <h2 class="font-weight-bold text-primary" style="font-size: 1.5rem; line-height: 1.2;">
          Online<br/>Eğitim
       </h2>
    </template>

    <template #vertical-nav-content>
      <NavItems />
    </template>

    <template #navbar>
      <div class="d-flex w-100 align-center">
        <VSpacer />
        <div class="d-flex align-center gap-2">
          <NavbarThemeSwitcher />
          <UserProfile />
        </div>
      </div>
    </template>

    <slot />
    
  </VerticalNavLayout>
</template>