<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiChartTimelineVariant,
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { useForm, Link } from "@inertiajs/vue3";
import { ref, reactive } from 'vue';

const props = defineProps({
    tags: {
        type: Array,
        default: () => [],
    },
    options: {
        type: Array,
        default: () => [],
    },
});

const fileInput = ref(null);
const fileErrorMsg = ref("");

const form = useForm({
  title: "",
  body: "",
  story: "",
  tag_id: "",
  active: false,
  file_id: null,
});

const showerrors = reactive({
    'title': false,
    'body': false,
    'tag_id': false,
    'file_id': false,
    'story': false,
});

const onFileChange = () => {
    // console.log(fileInput.value.files[0]);
    form.file_id = fileInput.value.files[0];
};

const submit = () => {
  form.post("/admin/posts", {
    onError: (errors) => {
        console.log(errors);
        if(errors.title) {
            showerrors.title = true;
        } else {
            showerrors.title = false;
        }
        if(errors.body) {
            showerrors.body = true;
        } else {
            showerrors.body = false;
        }
        if(errors.tag_id) {
            showerrors.tag_id = true;
        } else {
            showerrors.tag_id = false;
        }
        if(errors.story) {
            showerrors.story = true;
        } else {
            showerrors.story = false;
        }
        if(errors.file_id) {
            showerrors.file = true;
            fileErrorMsg.value = errors.file;
        } else {
            showerrors.file = false;
        }
    }
  });
};
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Dashboard" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Create Post"
        main
      >
      </SectionTitleLineWithButton>
      
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label 
                                for="title" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                Title:</label>
                            <input 
                                type="text" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                placeholder="Enter Title" 
                                id="title"
                                v-model="form.title" />
                            <span v-if="showerrors.title" class="error text-red-800"> This field required</span>


                        </div>

                        <div class="mb-4">
                            <label 
                                for="body" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                Synopsis:</label>
                            <textarea 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="body" 
                                v-model="form.body" 
                                placeholder="Enter Body"></textarea>
                            <span v-if="showerrors.body" class="error text-red-800"> This field required</span>
                        </div>

                        <div class="mb-4">
                            <label 
                                for="body" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                Body:</label>
                            <textarea 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="body" 
                                v-model="form.story" 
                                placeholder="Enter Body"></textarea>
                            <span v-if="showerrors.story" class="error text-red-800"> This field required</span>
                        </div>

                        

                        <div class="mb-4">
                            <label 
                                for="body" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                Tag:</label>
                                <v-select :options="props.tags" v-model="form.tag_id" :reduce="(option) => option.id"></v-select>
                            <span v-if="showerrors.tag_id" class="error text-red-800"> This field required</span>
                        </div>

                        <div class="mb-4">
                            <label 
                                for="body" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                File:</label>
                                <input type="file" @change="onFileChange" ref="fileInput" /><br>
                            <span v-if="showerrors.file" class="error text-red-800"> File must be pdf and below 300kb</span>
                        </div>

                        <div class="mb-4">
                            <label 
                                for="file" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                Active:</label>
                            <input type="checkbox" v-model="form.active" :checked="form.active">
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3 text-white">
                            Submit
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
    </SectionMain>
  </LayoutAuthenticated>
</template>
<style>
@import "vue-select/dist/vue-select.css";
</style>
