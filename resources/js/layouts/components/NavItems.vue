<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
// Kullanıcı rolünü güvenli bir şekilde alıyoruz
const userRole = computed(() => page.props.auth?.user?.role || null);

const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <VList density="compact" nav class="nav-items">
    
    <template v-if="userRole === 'admin'">
      <div class="nav-heading mt-2 mb-1 pl-4 text-caption text-disabled font-weight-bold">YÖNETİM</div>

      <VListItem
        :component="Link"
        :href="route('admin.dashboard')"
        prepend-icon="mdi-home-analytics"
        title="Admin Panel"
        :active="route().current('admin.dashboard')"
        color="primary"
      />

      <VListItem
        :component="Link"
        :href="route('admin.users.index')"
        prepend-icon="mdi-account-group"
        title="Kullanıcı Yönetimi"
        :active="route().current('admin.users.*')"
        color="primary"
      />

      <VListGroup value="adminCourses">
        <template #activator="{ props }">
          <VListItem
            v-bind="props"
            prepend-icon="mdi-book-open-page-variant"
            title="Kurs Yönetimi"
          />
        </template>
        
        <VListItem
          :component="Link"
          :href="route('admin.courses.index')"
          prepend-icon="mdi-format-list-checks"
          title="Tüm Kursları Denetle"
          :active="route().current('admin.courses.*')"
          color="primary"
        />
      </VListGroup>

      <VListItem
        :component="Link"
        :href="route('admin.support.index')"
        prepend-icon="mdi-face-agent"
        title="Destek Talepleri"
        :active="route().current('admin.support.*')"
        color="primary"
      />
    </template>

    <template v-if="userRole === 'instructor'">
      <div class="nav-heading mt-2 mb-1 pl-4 text-caption text-disabled font-weight-bold">EĞİTMEN</div>

      <VListItem
        :component="Link"
        :href="route('instructor.dashboard')"
        prepend-icon="mdi-view-dashboard"
        title="Eğitmen Paneli"
        :active="route().current('instructor.dashboard')"
        color="primary"
      />

      <VListGroup value="instructorCourses">
        <template #activator="{ props }">
          <VListItem
            v-bind="props"
            prepend-icon="mdi-school"
            title="Kurs Yönetimi"
          />
        </template>
        
        <VListItem
          :component="Link"
          :href="route('instructor.courses.index')"
          prepend-icon="mdi-view-list"
          title="Kurslarım"
          :active="route().current('instructor.courses.index')"
          color="primary"
        />

        <VListItem
          :component="Link"
          :href="route('instructor.courses.create')"
          prepend-icon="mdi-plus-circle"
          title="Yeni Kurs Ekle"
          :active="route().current('instructor.courses.create')"
          color="primary"
        />
      </VListGroup>

      <VListItem
        :component="Link"
        :href="route('chat.index')"
        prepend-icon="mdi-message-text-outline"
        title="Mesajlar"
        :active="route().current('chat.*')"
        color="primary"
      />
      
      <VListItem
        :component="Link"
        :href="route('instructor.support.index')"
        prepend-icon="mdi-comment-question"
        title="Destek Taleplerim"
        :active="route().current('instructor.support.*')"
        color="primary"
      />

      <VListItem
        :component="Link"
        :href="route('instructor.students.index')"
        prepend-icon="mdi-account-school"
        title="Öğrenciler"
        :active="route().current('instructor.students.*')"
        color="primary"
      />
    </template>

    <template v-if="userRole === 'student'">
      <div class="nav-heading mt-2 mb-1 pl-4 text-caption text-disabled font-weight-bold">ÖĞRENCİ</div>

      <VListItem
        :component="Link"
        :href="route('student.dashboard')"
        prepend-icon="mdi-view-dashboard-outline"
        title="Panelim"
        :active="route().current('student.dashboard')"
        color="primary"
      />

      <VListItem
        :component="Link"
        :href="route('chat.index')"
        prepend-icon="mdi-forum-outline"
        title="Sohbetler"
        :active="route().current('chat.*')"
        color="primary"
      />
      
      <VListItem
        :component="Link"
        :href="route('student.my-courses')"
        prepend-icon="mdi-book-open-variant"
        title="Kayıtlı Kurslarım"
        :active="route().current('student.my-courses') || route().current('student.course.show')"
        color="primary"
      />
      
      <VListItem
        :component="Link"
        :href="route('student.courses.index')"
        prepend-icon="mdi-compass"
        title="Kurs Kataloğu"
        :active="route().current('student.courses.index')"
        color="primary"
      />

      <VListItem
        :component="Link"
        :href="route('student.certificates.index')"
        prepend-icon="mdi-certificate"
        title="Sertifikalarım"
        :active="route().current('student.certificates.*')"
        color="primary"
      />
    </template>

    <VDivider class="my-2" />
    
    <VListItem
      @click="logout"
      prepend-icon="mdi-logout"
      title="Çıkış Yap"
      base-color="error"
      variant="text"
      class="mt-2"
      style="cursor: pointer;"
    />
  </VList>
</template>

<style scoped>
.nav-heading {
  letter-spacing: 0.1em;
  font-size: 0.75rem;
}
</style>