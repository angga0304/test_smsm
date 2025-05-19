<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiAccountFile,
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { useForm, Link } from "@inertiajs/vue3";
import NotificationBar from "@/Components/NotificationBar.vue"

defineProps({
    files: {
        type: Array,
        default: () => [],
    },
    can: {
        type: Boolean,
        default: () => false,
    },
});

const form = useForm({});

const columns = [
    { data: 'time' },
    { data: 'original_name' },
    { data: 'generated_name' },
    { data: 'used' },
    { data: 'action', orderable: false, className: 'dt-center' }
];


const deletePost = (id) => {
    if(confirm("Do you really want to delete?")){
        form.delete(`/admin/files/${id}`);
    }
};

const editPost = (id) => {
    window.location.href = `/admin/files/` + id + '/edit';
};
</script>

<template>
  <LayoutAuthenticated>
    <Head title="File List" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiAccountFile"
        title="List File"
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
                        <Link v-if="can" href="files/create"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Upload Asset</button></Link>
                        <a v-if="can" href="files/export"><button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded my-3 float-right">Request Export</button></a>
                        <div class="p-6 text-gray-900">
                        <DataTable
                            :data="files"
                            :columns="columns"
                            :options="{order: 0}"
                        >
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>File</th>
                                    <th>Path</th>
                                    <th>Used by posts</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <template #column-4="props">
                                <button v-if="can" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2" @click="editPost(props.rowData.id)">Show</button>
                                <span v-if="can" v-html="props.rowData.asset"></span>
                                <span  v-if="can">
                                    <button v-if="props.rowData.canDelete" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" @click="deletePost(props.rowData.id)">Delete</button>
                                </span>
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