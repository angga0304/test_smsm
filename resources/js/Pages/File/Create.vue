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
  file_id: null,
});

const showerrors = reactive({
    'file_id': false,
});

const onFileChange = () => {
    form.file_id = fileInput.value.files[0];
};

const submit = () => {
  form.post("/admin/files", {
    onError: (errors) => {
        if(errors.file_id) {
            showerrors.file = true;
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
    <Head title="Upload asset" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiChartTimelineVariant"
        title="Upload asset"
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
                                for="body" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                File:</label>
                                <input type="file" accept="application/pdf" @change="onFileChange" ref="fileInput" /><br>
                            <span v-if="showerrors.file" class="error text-red-800"> {{ fileErrorMsg }}</span>
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
