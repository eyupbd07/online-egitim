<template>
  <div classm="h-100 d-flex align-center justify-space-between">
    <!-- 👉 Footer: Left Content -->
    <span classm="d-flex align-center">
      <!-- Değişiklik: Telif hakkı metni güncellendi -->
      &copy;
      {{ new Date().getFullYear() }}
      Online Eğitim Platformu. Tüm Hakları Saklıdır.
    </span>
    
    <!-- 👉 Footer: Right Content (Tüm linkler kaldırıldı) -->
    <span class="d-md-flex gap-x-4 text-primary d-none">
      <!-- "License", "More Themes", "Documentation", "Support" linkleri kaldırıldı -->
    </span>
  </div>
</template>
