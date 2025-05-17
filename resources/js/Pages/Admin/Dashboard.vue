<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiAccountMultiple,
  mdiChartTimelineVariant,
  mdiChat,
  mdiPost
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import CardBoxWidget from '@/Components/CardBoxWidget.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'

defineProps({
  users: Number,
  posts: Number,
  comments: Number,
});
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Dashboard" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Overview"
        main
      >
      </SectionTitleLineWithButton>
      
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
        <CardBoxWidget
          trend-type="up"
          color="text-emerald-500"
          :icon="mdiAccountMultiple"
          :number=users
          label="Users"
        />
        <CardBoxWidget
          trend-type="down"
          color="text-blue-500"
          :icon="mdiPost"
          :number=posts
          label="Posts"
        />
        <CardBoxWidget
          trend-type="alert"
          color="text-red-500"
          :icon="mdiChat"
          :number=comments
          label="Comments"
        />
      </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>