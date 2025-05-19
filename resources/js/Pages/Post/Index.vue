<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiNewspaper,
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { useForm, Link } from "@inertiajs/vue3";
import NotificationBar from "@/Components/NotificationBar.vue"

defineProps({
    posts: {
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
    { data: 'title' },
    { data: 'author' },
    { data: 'status' },
    { data: 'action', orderable: false, className: 'dt-center' }
];


const deletePost = (id) => {
    if(confirm("Do you really want to delete?")){
        form.delete(`/admin/posts/${id}`);
    }
};

const editPost = (id) => {
    window.location.href = `/admin/posts/` + id + '/edit';
};
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Post List" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiNewspaper"
        title="Post List"
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
                        <Link v-if="can" href="posts/create"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Post</button></Link>
                        <a v-if="can" href="posts/export"><button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded my-3 float-right">Request Export</button></a>
                        <div class="p-6 text-gray-900">
                        <DataTable
                            :data="posts"
                            :columns="columns"
                        >
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Active</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <template #column-3="props">
                                <button v-if="can" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2" @click="editPost(props.rowData.id)">Edit</button>
                                <button v-if="can" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" @click="deletePost(props.rowData.id)">Delete</button>
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