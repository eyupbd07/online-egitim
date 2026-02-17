<script setup>
import { useTheme } from 'vuetify'
import { computed } from 'vue'

const theme = useTheme()

// Şu anki tema karanlık mı?
const isDark = computed(() => theme.global.current.value.dark)

// Temayı Değiştirme Fonksiyonu
const toggleTheme = () => {
  theme.global.name.value = isDark.value ? 'light' : 'dark'
}
</script>

<template>
  <VBtn
    icon
    variant="text"
    color="medium-emphasis"
    @click="toggleTheme"
  >
    <VIcon size="24" :icon="isDark ? 'mdi-weather-night' : 'mdi-weather-sunny'" />
    
    <VTooltip activator="parent">
      {{ isDark ? 'Aydınlık Moda Geç' : 'Karanlık Moda Geç' }}
    </VTooltip>
  </VBtn>
</template>