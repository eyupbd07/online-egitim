<script setup>
import { Link, usePage, router } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
// Kullanıcı rolünü güvenli bir şekilde alıyoruz
const userRole = computed(() => page.props.auth?.user?.role || null)

const logout = () => {
  router.post(route('logout'));
}
</script>

<template>
  <VList density="compact" nav>
    
    <template v-if="userRole === 'admin'">
      <VListItem
        :component="Link"
        :href="route('admin.dashboard')"
        prepend-icon="ri-home-smile-line"
        title="Admin Panel"
        :active="route().current('admin.dashboard')"
        color="primary"
      />

      <VListItem
        :component="Link"
        :href="route('admin.users.index')"
        prepend-icon="ri-team-line"
        title="Kullanıcı Yönetimi"
        :active="route().current('admin.users.*')"
        color="primary"
      />

      <VListGroup value="adminCourses">
        <template #activator="{ props }">
          <VListItem
            v-bind="props"
            prepend-icon="ri-book-read-line"
            title="Kurs Yönetimi (Admin)"
          />
        </template>
        
        <VListItem
          :component="Link"
          :href="route('admin.courses.index')"
          prepend-icon="ri-list-check-2"
          title="Tüm Kursları Denetle"
          :active="route().current('admin.courses.*')"
          color="primary"
        />
      </VListGroup>

       <VListItem
        :component="Link"
        :href="route('admin.support.index')"
        prepend-icon="ri-customer-service-2-line"
        title="Destek Talepleri"
        :active="route().current('admin.support.*')"
        color="primary"
      />
    </template>

    <template v-if="userRole === 'instructor'">
      <VListItem
        :component="Link"
        :href="route('instructor.dashboard')"
        prepend-icon="ri-user-voice-line"
        title="Eğitmen Paneli"
        :active="route().current('instructor.dashboard')"
        color="primary"
      />

      <VListGroup value="instructorCourses">
        <template #activator="{ props }">
          <VListItem
            v-bind="props"
            prepend-icon="ri-book-3-line"
            title="Kurs Yönetimi"
          />
        </template>
        
        <VListItem
          :component="Link"
          :href="route('instructor.courses.index')"
          prepend-icon="ri-layout-grid-line"
          title="Kurslarımı Yönet"
          :active="route().current('instructor.courses.index')"
          color="primary"
        />

        <VListItem
          :component="Link"
          :href="route('instructor.courses.create')"
          prepend-icon="ri-add-circle-line"
          title="Yeni Kurs Ekle"
          :active="route().current('instructor.courses.create')"
          color="primary"
        />
      </VListGroup>
      
      <VListItem
        :component="Link"
        :href="route('instructor.support.index')"
        prepend-icon="ri-question-answer-line"
        title="Destek Taleplerim"
        :active="route().current('instructor.support.*')"
        color="primary"
      />

      <VListItem
        :component="Link"
        :href="route('instructor.students.index')"
        prepend-icon="ri-group-line"
        title="Öğrenciler"
        :active="route().current('instructor.students.*')"
        color="primary"
      />
    </template>

    <template v-if="userRole === 'student'">
      <VListItem
        :component="Link"
        :href="route('student.dashboard')"
        prepend-icon="ri-dashboard-line"
        title="Panelim"
        :active="route().current('student.dashboard')"
        color="primary"
      />
      
      <VListItem
        :component="Link"
        :href="route('student.my-courses')"
        prepend-icon="ri-book-open-line"
        title="Kurslarım"
        :active="route().current('student.my-courses') || route().current('student.course.show')"
        color="primary"
      />
      
      <VListItem
        :component="Link"
        :href="route('student.courses.index')"
        prepend-icon="ri-search-line"
        title="Kurs Kataloğu"
        :active="route().current('student.courses.index')"
        color="primary"
      />

      <VListItem
        :component="Link"
        :href="route('student.certificates.index')"
        prepend-icon="ri-award-line"
        title="Sertifikalarım"
        :active="route().current('student.certificates.*')"
        color="primary"
      />
    </template>

    <VDivider class="my-2" />
    
    <VListItem
      @click="logout"
      prepend-icon="ri-logout-box-line"
      title="Çıkış Yap"
      base-color="error"
      variant="text"
      style="cursor: pointer;"
    />
  </VList>
</template>