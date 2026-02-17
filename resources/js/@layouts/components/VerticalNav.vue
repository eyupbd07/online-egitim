<script setup>
import { ref, onMounted } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
import { useDisplay } from 'vuetify'
import { usePage } from '@inertiajs/vue3' // Inertia eklendi

const props = defineProps({
  tag: {
    type: [String, null],
    required: false,
    default: 'aside',
  },
  isOverlayNavActive: {
    type: Boolean,
    required: true,
  },
  toggleIsOverlayNavActive: {
    type: Function,
    required: true,
  },
})

const { mdAndDown } = useDisplay()
const refNav = ref()

// --- KRİTİK DÜZELTME BAŞLANGICI ---
// Eski 'useRoute' yerine Inertia'nın 'usePage' hook'unu kullanıyoruz.
const page = usePage()

// Temanın hata vermemesi için sahte bir 'route' objesi oluşturuyoruz.
// 'path' istendiğinde Inertia'nın güncel URL'ini veriyoruz.
const route = {
    get path() { return page.url }, 
    get name() { return '' },
    meta: {}
}
// --- KRİTİK DÜZELTME BİTİŞİ ---

const isScrollable = ref(false)
const handleScroll = e => {
  isScrollable.value = e.target.scrollTop > 0
}
</script>

<template>
  <component
    :is="props.tag"
    ref="refNav"
    class="layout-vertical-nav"
    :class="[
      {
        'overlay-nav': mdAndDown,
        'visible': isOverlayNavActive,
        'scrolled': isScrollable,
      },
    ]"
  >
    <div class="nav-header">
      <slot name="nav-header">
        <a href="/" class="app-logo app-title-wrapper">
          </a>
      </slot>
    </div>

    <slot name="before-nav-items" />

    <PerfectScrollbar
      :options="{ wheelPropagation: false }"
      class="nav-items"
      @ps-scroll-y="handleScroll"
    >
      <slot />
    </PerfectScrollbar>

    <slot name="after-nav-items" />
  </component>
</template>

<style lang="scss">
.layout-vertical-nav {
  position: fixed;
  z-index: 1001;
  display: flex;
  flex-direction: column;
  block-size: 100%;
  inline-size: 260px;
  inset-block-start: 0;
  inset-inline-start: 0;
  transition: transform 0.2s ease-in-out, inline-size 0.2s ease-in-out;
  will-change: transform, inline-size;
  border-right: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
  background-color: rgb(var(--v-theme-surface));

  &.overlay-nav {
    transform: translateX(-110%); // Gizle
    &.visible {
      transform: translateX(0); // Göster
    }
  }

  .nav-header {
    display: flex;
    align-items: center;
    padding: 0 1rem;
    min-height: 64px;
  }

  .nav-items {
    flex-grow: 1;
    overflow-y: auto;
    padding: 0.5rem 0;
  }
}
</style>