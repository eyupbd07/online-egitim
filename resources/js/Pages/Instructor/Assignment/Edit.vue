<script setup>
import { useForm, Head, Link } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/default.vue';

defineOptions({ layout: DefaultLayout });

const props = defineProps({ assignment: Object, course_id: Number });

const form = useForm({
    title: props.assignment.title,
    description: props.assignment.description,
    due_date: props.assignment.due_date,
    _method: 'PUT'
});

const submit = () => {
    form.post(route('instructor.assignments.update', props.assignment.id));
};
</script>

<template>
    <Head title="Ödevi Düzenle" />
    <VContainer>
        <div class="d-flex align-center mb-6">
            <Link :href="route('instructor.courses.edit', course_id)">
                <VBtn icon="mdi-arrow-left" variant="text" class="mr-2" />
            </Link>
            <h1 class="text-h5 font-weight-bold">Ödevi Düzenle</h1>
        </div>

        <VCard class="pa-6">
            <VForm @submit.prevent="submit">
                <VTextField v-model="form.title" label="Ödev Başlığı" variant="outlined" class="mb-4" />
                <VTextarea v-model="form.description" label="Açıklama" variant="outlined" class="mb-4" />
                <VTextField v-model="form.due_date" type="date" label="Son Tarih" variant="outlined" class="mb-4" />
                <VBtn type="submit" color="teal" class="text-white" block :loading="form.processing">Değişiklikleri Kaydet</VBtn>
            </VForm>
        </VCard>
    </VContainer>
</template>