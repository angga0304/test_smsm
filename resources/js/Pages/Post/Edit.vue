<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiChartTimelineVariant,
  mdiFile,
  mdiClockTimeEight,
  mdiComment,
  mdiDatabaseEdit
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { useForm, Link } from "@inertiajs/vue3";
import { ref, reactive } from 'vue';
import BaseIcon from '@/Components/BaseIcon.vue'

const props = defineProps({
    tags: {
        type: Array,
        default: () => [],
    },
    post: {
        type: Object,
        default: () => [],
    },
});

const fileInput = ref(null);
const fileErrorMsg = ref("");

const form = useForm({
  title: props.post.title,
  body: props.post.body,
  story: props.post.story,
  tag_id: props.post.tag_id,
  active: props.post.active == 1,
  file_id: null,
  publish: props.post.publish,
});

const showerrors = reactive({
    'title': false,
    'body': false,
    'tag_id': false,
    'file_id': false,
    'publish': false,
});

const onFileChange = () => {
    form.file_id = fileInput.value.files[0];
};

const submit = () => {
  form.put(`/admin/posts/${props.post.id}`, {
    onError: (errors) => {
        console.log(errors)
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
            showerrors.publish = true;
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

const deleteComment = (id) => {
    if(confirm("Are you sure?")){
        form.delete(`/admin/comment/${id}`);
    }
};
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Edit Post" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiDatabaseEdit"
        title="Edit Post"
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
                                placeholder="Enter Synopsis"></textarea>
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
                                for="title" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                Publish:</label>
                            <input 
                                type="datetime-local" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                placeholder="Enter Title" 
                                id="title"
                                v-model="form.publish" />
                            <span v-if="showerrors.publish" class="error text-red-800"> This field required</span>
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
                            <BaseIcon
                                v-if="props.post.file_id != 0"
                                :path="mdiFile"
                                class="mr-2"
                                size="20"
                            />
                            <div v-if="props.post.file_id != 0" v-html="props.post.asset">
                            </div>
                            <label 
                                for="body" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                File:</label>
                                <input type="file" accept="application/pdf" @change="onFileChange" ref="fileInput" /><br>
                            <span v-if="showerrors.file" class="error text-red-800"> File must be pdf and below 300kb</span>
                        </div>

                        <div class="mb-4">
                            <label 
                                for="file" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                Active:</label>
                            <input type="checkbox" v-model="form.active">
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3 text-white">
                            Submit
                        </button>

                    </form>

                </div>
            </div>
        </div>

        <div v-if="props.post.comments" class="py-12">
          <SectionTitleLineWithButton
            :icon="mdiComment"
            title="Comments"
            main
          >
          </SectionTitleLineWithButton>
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  <table>
                    <thead>
                      <td>Date</td>
                      <td>user</td>
                      <td>body</td>
                      <td>status</td>
                      <td>action</td>
                    </thead>
                    <tr v-for="comment in props.post.comments">
                      <td>{{ comment.timeline }}</td>
                      <td>{{ comment.author }}</td>
                      <td>{{ comment.body }}</td>
                      <td>{{ comment.blocked }}</td>
                      <td>
                        <button v-if="!comment.blocked"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2" 
                            @click="deleteComment(comment.id)"
                        >
                            Block
                        </button>
                        <button v-if="comment.blocked"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2" 
                            @click="deleteComment(comment.id)"
                        >
                            Unblock
                        </button>
                      </td>
                    </tr>
                  </table>
                </div>
            </div>
          </div>
        </div>

        <div v-if="props.post.activities" class="py-12">
          <SectionTitleLineWithButton
            :icon="mdiClockTimeEight"
            title="History & Note"
            main
          >
          </SectionTitleLineWithButton>
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  <table>
                    <thead>
                      <td>Date</td>
                      <td>Action</td>
                      <td>user</td>
                      <td>Note</td>
                    </thead>
                    <tr v-for="log in props.post.activities">
                      <td>{{ log.time }}</td>
                      <td>{{ log.action }}</td>
                      <td>{{ log.user }}</td>
                      <td>{{ log.note }}</td>
                    </tr>
                  </table>
                </div>
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
