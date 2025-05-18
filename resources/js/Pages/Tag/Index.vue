<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiChartTimelineVariant,
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { useForm, Link } from "@inertiajs/vue3";

defineProps({
    tags: {
        type: Array,
        default: () => [],
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
    <Head title="Dashboard" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Create Tag"
        main
      >
      </SectionTitleLineWithButton>
      
        <div class="py-12">
            <div v-if="$page.props.flash.message" class="fixed top-0 right-0 m-6">
                <div
                    class="bg-yellow-200 text-black-900 rounded-lg shadow-md p-6 pr-10"
                    style="min-width: 240px"
                    >
                    <button
                        class="opacity-75 cursor-pointer absolute top-0 right-0 py-2 px-3 hover:opacity-100"
                    >
                        ×
                    </button>
                    <div class="flex items-center">
                        {{ $page.props.flash.message }}
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="$page.props.flash.message" class="fixed top-0 right-0 m-6">
                            <div
                                class="bg-yellow-200 text-black-900 rounded-lg shadow-md p-6 pr-10"
                                style="min-width: 240px"
                                >
                                <button
                                    class="opacity-75 cursor-pointer absolute top-0 right-0 py-2 px-3 hover:opacity-100"
                                >
                                    ×
                                </button>
                                <div class="flex items-center">
                                    {{ $page.props.flash.message }}
                                </div>
                            </div>
                        </div>
                        <Link href="tag/create"><button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Create New Tag</button></Link>
                        <a target="_blank" href="tag/export"><button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded my-3 float-right">Export Tag</button></a>
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
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2" @click="edittag(props.rowData.id)">Edit</button>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" @click="deletetag(props.rowData.id)">Delete</button>
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