<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    assignment: Object, // Controller'dan gelecek
    submission: Object  // Eğer öğrenci yüklediyse burası dolu gelir
});

const form = useForm({
    file: null,
    student_note: ''
});

// Tarih Kontrolü
const isLate = computed(() => new Date() > new Date(props.assignment.due_date));
const formattedDate = new Date(props.assignment.due_date).toLocaleString('tr-TR');

const submit = () => {
    form.post(route('student.assignments.submit', props.assignment.id), {
        onSuccess: () => {
            form.reset();
            alert('Ödeviniz başarıyla gönderildi!');
        }
    });
};
</script>

<template>
    <VCard class="border-thin rounded-lg elevation-2 mt-6">
        <VCardItem class="bg-deep-purple-lighten-5">
            <div class="d-flex justify-space-between align-center">
                <div>
                    <h3 class="text-h6 font-weight-bold text-deep-purple-darken-4">
                        <VIcon icon="ri-task-fill" start/> {{ assignment.title }}
                    </h3>
                    <div class="text-caption text-grey-darken-1 mt-1">
                        {{ assignment.description }}
                    </div>
                </div>
                <VChip :color="isLate ? 'error' : 'warning'" size="small" variant="flat">
                    <VIcon start icon="ri-time-line"/> Son: {{ formattedDate }}
                </VChip>
            </div>
        </VCardItem>

        <VCardText class="pt-4">
            
            <div v-if="submission" class="text-center pa-4 bg-green-lighten-5 rounded border border-success border-dashed">
                <VIcon icon="ri-checkbox-circle-fill" color="success" size="40" class="mb-2"/>
                <h4 class="text-h6 text-success font-weight-bold">Ödev Teslim Edildi!</h4>
                <p class="text-body-2 text-grey">
                    Teslim Tarihi: {{ new Date(submission.submitted_at).toLocaleString('tr-TR') }}
                </p>
                <VBtn 
                    :href="`/storage/${submission.file_path}`" 
                    target="_blank"
                    color="success" 
                    variant="outlined" 
                    class="mt-3" 
                    prepend-icon="ri-download-line"
                >
                    Dosyamı İndir / Gör
                </VBtn>
            </div>

            <div v-else-if="!isLate">
                <VAlert color="info" variant="tonal" class="mb-4" density="compact" icon="ri-information-line">
                    Lütfen ödev dosyanızı (PDF, Word veya ZIP) yükleyiniz.
                </VAlert>

                <VFileInput
                    v-model="form.file"
                    label="Dosya Seçiniz"
                    variant="outlined"
                    prepend-icon="ri-upload-cloud-2-line"
                    show-size
                    accept=".pdf,.doc,.docx,.zip,.rar"
                ></VFileInput>

                <VTextarea
                    v-model="form.student_note"
                    label="Eğitmene Notunuz (Opsiyonel)"
                    variant="outlined"
                    rows="2"
                    placeholder="Ödevimle ilgili eklemek istediklerim..."
                ></VTextarea>

                <VBtn 
                    block 
                    color="deep-purple-accent-3" 
                    size="large"
                    @click="submit"
                    :loading="form.processing"
                    prepend-icon="ri-send-plane-fill"
                >
                    ÖDEVİ TESLİM ET
                </VBtn>
            </div>

            <VAlert v-else type="error" variant="tonal" border="start" class="mt-2">
                <strong>Süre Doldu!</strong> Bu ödevin son teslim tarihi geçtiği için dosya yükleyemezsiniz.
            </VAlert>

        </VCardText>
    </VCard>
</template>