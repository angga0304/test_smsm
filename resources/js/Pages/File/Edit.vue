<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiDatabaseEdit,
  mdiFile,
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { useForm, Link } from "@inertiajs/vue3";
import { ref, reactive } from 'vue';
import BaseIcon from '@/Components/BaseIcon.vue'

const props = defineProps({
    file: {
        type: Array,
        default: () => [],
    },
});

const fileInput = ref(null);
const fileErrorMsg = ref("");

const form = useForm({
  file_id: null,
});

const showerrors = reactive({
    'file_id': false,
});

const onFileChange = () => {
    form.file_id = fileInput.value.files[0];
};

const submit = () => {
  console.log(form.file_id);
  form.put(`/admin/files/${props.file.id}`, {
    onError: (errors) => {
      console.log(errors);
        if(errors.file_id) {
            showerrors.file_id = true;
            fileErrorMsg.value = errors.file_id;
        } else {
            showerrors.file = false;
        }
    }
  });
};
</script>

<template>
  <LayoutAuthenticated>
    <Head title="File" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiDatabaseEdit"
        title="File"
        main
      >
      </SectionTitleLineWithButton>
      
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form @submit.prevent="submit" enctype="multipart/form-data">
                        <div class="mb-4">
                            <BaseIcon
                                v-if="props.file.file_id != 0"
                                :path="mdiFile"
                                class="mr-2"
                                size="20"
                            />
                            <span v-if="props.file.file_id != 0" v-html="props.file.original_name">
                            </span>
                        </div>
                        <span v-html="props.file.asset"></span>

                    </form>

                </div>
            </div>
        </div>
    </div>
        
    <div v-if="props.file.activities" class="py-12">
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
                <tr v-for="log in props.file.activities">
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
    </SectionMain>
  </LayoutAuthenticated>
</template>
<style>
@import "vue-select/dist/vue-select.css";
</style>
