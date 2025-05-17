<script setup>
import { Head } from '@inertiajs/vue3'
import {
  mdiChartTimelineVariant,
} from '@mdi/js'
import SectionMain from '@/Components/SectionMain.vue'
import LayoutAuthenticated from '@/Layouts/Admin/LayoutAuthenticated.vue'
import SectionTitleLineWithButton from '@/Components/SectionTitleLineWithButton.vue'
import { useForm, Link } from "@inertiajs/vue3";
import { reactive } from 'vue';


const form = useForm({
  name: "",
});

const showerrors = reactive({
    'name': false,
});

const submit = () => {
  form.post("/admin/tag", {
    onError: (errors) => {
      if(errors.name) {
        showerrors.name = true;
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
        title="Create Tag"
        main
      >
      </SectionTitleLineWithButton>
      
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label 
                                for="title" 
                                class="block text-gray-700 text-sm font-bold mb-2">
                                Name:</label>
                            <input 
                                type="text" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                placeholder="Enter Title" 
                                id="title"
                                v-model="form.name" />
                            <span v-if="showerrors.name" class="error text-red-800"> This field required</span>
                            

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
