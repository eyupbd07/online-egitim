<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';

const props = defineProps({ quiz: Object, questions: Array });

// SÜRE HESABI: duration (dk) * 60 saniye
const timeLeft = ref((Number(props.quiz.duration) || 30) * 60);
let timer = null;

const startTimer = () => {
    timer = setInterval(() => {
        if (timeLeft.value > 0) timeLeft.value--;
        else { clearInterval(timer); alert('Süre bitti!'); }
    }, 1000);
};

const formatTime = (seconds) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m}:${s < 10 ? '0' : ''}${s}`;
};

onMounted(() => startTimer());
onUnmounted(() => clearInterval(timer));
</script>

<template>
    <VContainer>
        <VCard color="primary" class="mb-6 pa-4 d-flex justify-space-between align-center text-white">
            <h2 class="text-h5 font-weight-bold">{{ quiz.title }}</h2>
            <VChip color="white" size="large" variant="elevated" class="text-error font-weight-black px-6">
                KALAN SÜRE: {{ formatTime(timeLeft) }}
            </VChip>
        </VCard>
        </VContainer>
</template>