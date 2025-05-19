<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiChartTimelineVariant,
  mdiTag
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { useForm, Link } from "@inertiajs/vue3";
import NotificationBar from "@/Components/NotificationBar.vue"

defineProps({
    tags: {
        type: Array,
        default: () => [],
    },
    can: {
        type: Array,
        default: () => false,
    },
});

const form = useForm({});

const columns = [
    { data: 'name' },
    { data: 'action', orderable: false, className: 'dt-center' }
];


const deletetag = (id) => {
    if(confirm("Do you really want to delete?")){
        form.delete(`/admin/tag/${id}`);
    }
};

const edittag = (id) => {
    window.location.href = `/admin/tag/` + id + '/edit';
};
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Tag List" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiTag"
        title="Tag List"
        main
      >
      </SectionTitleLineWithButton>
      
        <div class="py-12">
            <NotificationBar
                :key="Date.now()"
                v-if="$page.props.flash.message"
                color="success"
                :icon="mdiAlertBoxOutline"
            >
                {{ $page.props.flash.message }}
            </NotificationBar>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <Link v-if="can" href="tag/create"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Tag</button></Link>
                        <a v-if="can" href="tag/export"><button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded my-3 float-right">Request Export</button></a>
                        <div class="p-6 text-gray-900">
                        <DataTable
                            :data="tags"
                            :columns="columns"
                        >
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <template #column-1="props">
                                <button v-if="can" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2" @click="edittag(props.rowData.id)">Edit</button>
                                <button v-if="can" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" @click="deletetag(props.rowData.id)">Delete</button>
                            </template>
                        </DataTable>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
<style>
 .dt-layout-row:first-child {
    display: flex;
 }

 .dt-layout-cell.dt-layout-end {
    margin-left: auto;
 }
</style>