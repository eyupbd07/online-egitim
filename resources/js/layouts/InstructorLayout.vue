<script setup>
// Bu dosya artık SADECE Vuetify bileşenlerinin çalışması için bir kılıftır.
// Kendi sidebar'ı YOKTUR. Ana sistemin sidebar'ı otomatik olarak görünecektir.
</script>

<template>
    <VApp style="background: transparent; min-height: 0;">
        <VMain class="pa-0">
            <slot />
        </VMain>
    </VApp>
</template>

<style>
/* Vuetify'ın ekranı zorla kaplama özelliğini devre dışı bırakıyoruz */
.v-application__wrap {
    min-height: auto !important;
}
/* Eğer ana tema ile çakışırsa arka planı temizle */
.v-application {
    background: transparent !important;
}
</style>