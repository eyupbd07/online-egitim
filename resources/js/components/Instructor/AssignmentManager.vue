<script setup>
import { ref } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';

// Parent'tan (Edit.vue) gelen veriler
// Veritabanında değişiklik olduğunda burası otomatik güncellenir.
const props = defineProps({
    courseId: Number,
    assignments: Array 
});

// Modal ve Düzenleme Durumları
const dialog = ref(false);
const isEditing = ref(false);
const editingId = ref(null);

const form = useForm({
    title: '',
    description: '',
    due_date: ''
});

// Ekleme Modalini Aç
const openCreateDialog = () => {
    isEditing.value = false;
    form.reset();
    dialog.value = true;
};

// Düzenleme Modalini Aç
const openEditDialog = (assignment) => {
    isEditing.value = true;
    editingId.value = assignment.id;
    form.title = assignment.title;
    form.description = assignment.description;
    form.due_date = assignment.due_date;
    dialog.value = true;
};

// KAYDETME İŞLEMİ (Ekle veya Güncelle)
const saveAssignment = () => {
    if (isEditing.value) {
        // Güncelleme
        form.put(route('instructor.assignments.update', editingId.value), {
            preserveScroll: true, // Sayfanın kaydırmasını koru
            onSuccess: () => {
                dialog.value = false;
                form.reset();
            }
        });
    } else {
        // Yeni Ekleme
        form.post(route('instructor.assignments.store', props.courseId), {
            preserveScroll: true, // Sayfa yenilenmez, sadece veri güncellenir
            onSuccess: () => {
                dialog.value = false;
                form.reset();
            }
        });
    }
};

// SİLME İŞLEMİ
const deleteAssignment = (id) => {
    if (confirm('Bu ödevi silmek istediğinize emin misiniz?')) {
        router.delete(route('instructor.assignments.destroy', id), {
            preserveScroll: true // Silince listeyi anlık günceller
        });
    }
};
</script>

<template>
    <div>
        <VBtn 
            color="teal-lighten-5" 
            class="text-teal-darken-2 font-weight-bold mb-4 border-dashed" 
            block 
            variant="flat" 
            @click="openCreateDialog"
        >
            <VIcon start icon="mdi-plus" /> Yeni Ödev Ekle
        </VBtn>

        <div v-if="assignments && assignments.length > 0">
            <div 
                v-for="(assignment, index) in assignments" 
                :key="assignment.id" 
                class="d-flex align-center justify-space-between pa-3 mb-2 rounded-lg bg-grey-lighten-5 border assignment-card"
            >
                <div class="d-flex align-center">
                    <VAvatar color="white" size="32" class="mr-3 text-caption font-weight-bold elevation-1 text-teal">
                        {{ index + 1 }}
                    </VAvatar>
                    <div>
                        <div class="font-weight-bold text-body-2 text-grey-darken-3">{{ assignment.title }}</div>
                        <div class="text-caption text-grey" v-if="assignment.due_date">
                            Son: {{ new Date(assignment.due_date).toLocaleDateString('tr-TR') }}
                        </div>
                    </div>
                </div>

                <div class="d-flex align-center">
                    
                    <Link :href="route('instructor.assignments.submissions', assignment.id)">
                        <VTooltip text="Teslimleri Gör" location="top">
                            <template v-slot:activator="{ props }">
                                <VBtn v-bind="props" icon="mdi-eye" size="small" variant="text" color="indigo" class="mr-1" />
                            </template>
                        </VTooltip>
                    </Link>

                    <VBtn icon="mdi-pencil" size="small" variant="text" color="primary" class="mr-1" @click="openEditDialog(assignment)" />

                    <VBtn icon="mdi-delete" size="small" variant="text" color="error" @click="deleteAssignment(assignment.id)" />
                </div>
            </div>
        </div>

        <div v-else class="text-center py-6 text-grey-lighten-1">
            <VIcon icon="mdi-file-document-outline" size="40" class="mb-2 opacity-50" />
            <div class="text-caption">Henüz ödev eklenmemiş.</div>
        </div>

        <VDialog v-model="dialog" max-width="500">
            <VCard class="rounded-xl">
                <VCardTitle class="pa-4 bg-teal-lighten-5 text-teal-darken-3 font-weight-bold d-flex align-center">
                    <VIcon :icon="isEditing ? 'mdi-pencil' : 'mdi-plus'" class="mr-2" />
                    {{ isEditing ? 'Ödevi Düzenle' : 'Yeni Ödev Ekle' }}
                </VCardTitle>
                
                <VCardText class="pa-4 pt-6">
                    <VTextField v-model="form.title" label="Ödev Başlığı" variant="outlined" density="comfortable" class="mb-3" />
                    <VTextarea v-model="form.description" label="Talimatlar" variant="outlined" rows="3" auto-grow class="mb-3" />
                    <VTextField v-model="form.due_date" label="Son Teslim Tarihi" type="date" variant="outlined" density="comfortable" />
                </VCardText>

                <VCardActions class="pa-4 border-t">
                    <VSpacer />
                    <VBtn variant="text" color="grey" @click="dialog = false">İptal</VBtn>
                    <VBtn color="teal" variant="elevated" class="px-6 text-capitalize" :loading="form.processing" @click="saveAssignment">
                        Kaydet
                    </VBtn>
                </VCardActions>
            </VCard>
        </VDialog>
    </div>
</template>

<style scoped>
.border-dashed { border-style: dashed !important; }
.assignment-card { transition: all 0.2s; }
.assignment-card:hover { border-color: #b2dfdb !important; background-color: #f0fdfa !important; }
</style>