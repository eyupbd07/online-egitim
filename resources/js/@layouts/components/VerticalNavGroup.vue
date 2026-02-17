<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3' // Inertia eklendi
import VerticalNavLink from '@layouts/components/VerticalNavLink.vue'
import VerticalNavGroup from '@layouts/components/VerticalNavGroup.vue'

const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
})

const isOpen = ref(false)
const isGroupActive = ref(false)

// --- KRİTİK DÜZELTME BAŞLANGICI ---
// Vue Router yerine Inertia'nın sayfa bilgisini alıyoruz
const page = usePage()

// Grubun açık olup olmadığını (içindeki link aktif mi?) kontrol ediyoruz
const updateGroupActive = () => {
  if (!props.item.children) return

  // Mevcut sayfa URL'i (page.url), grubun içindeki herhangi bir linkle eşleşiyor mu?
  isGroupActive.value = props.item.children.some(child => {
    // child.to bir string olmalı (örn: '/instructor/courses')
    if (!child.to) return false
    return page.url === child.to || page.url.startsWith(child.to + '/')
  })
}

// Sayfa her değiştiğinde bu kontrolü yap
watch(() => page.url, updateGroupActive, { immediate: true })

// Açık/Kapalı durumu aktif duruma göre senkronize et
watch(isGroupActive, val => {
  isOpen.value = val
}, { immediate: true })
// --- KRİTİK DÜZELTME BİTİŞİ ---

</script>

<template>
  <li
    class="nav-group"
    :class="{
      'open': isOpen,
      'active': isGroupActive,
      'disabled': item.disable, // Hata veren satır burasıydı, artık düzeldi
    }"
  >
    <div
      class="nav-group-label"
      @click="isOpen = !isOpen"
    >
      <VIcon
        v-if="item.icon"
        :icon="item.icon"
        class="nav-item-icon"
      />
      <span class="nav-item-title">{{ item.title }}</span>
      <span
        v-if="item.badgeContent"
        class="nav-item-badge"
        :class="item.badgeClass"
      >
        {{ item.badgeContent }}
      </span>
      <VIcon
        icon="mdi-chevron-right"
        class="nav-group-arrow"
      />
    </div>

    <ul
      v-show="isOpen"
      class="nav-group-children"
    >
      <template
        v-for="child in item.children"
        :key="child.title"
      >
        <VerticalNavGroup
          v-if="child.children"
          :item="child"
        />
        <VerticalNavLink
          v-else
          :item="child"
        />
      </template>
    </ul>
  </li>
</template>

<style lang="scss">
.nav-group {
  .nav-group-label {
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    cursor: pointer;
    border-radius: 6px;
    transition: background-color 0.2s;

    &:hover {
      background-color: rgba(var(--v-theme-on-surface), 0.04);
    }

    .nav-item-icon {
      margin-right: 0.75rem;
      font-size: 1.375rem;
    }

    .nav-item-title {
      flex-grow: 1;
      font-size: 0.9375rem;
    }

    .nav-group-arrow {
      font-size: 1.25rem;
      transition: transform 0.2s;
    }
  }

  &.open {
    > .nav-group-label .nav-group-arrow {
      transform: rotate(90deg);
    }
  }

  .nav-group-children {
    list-style: none;
    padding: 0;
    margin: 0;
    // Alt menü girintisi
    .nav-link {
        padding-inline-start: 1rem; 
    }
  }
}
</style>