<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from "@inertiajs/vue3";
import {
  mdiFile,
} from '@mdi/js'
import BaseIcon from '@/Components/BaseIcon.vue'

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    uid: Object,
    post: Object,
});

const form = useForm({
  user_id: props.uid,
  body: "",
});

const submit = () => {
  form.post(`/post/${props.post.slug}/post-comment`, {
    onError: (errors) => {
        console.log(errors);
    }
  });
};
</script>

<template>
    <Head :title="post.title" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div v-if="props.canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Log in</Link
                >

                <Link
                    v-if="props.canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Register</Link
                >
            </template>
        </div>

        <div class="max-w-7xl mx-auto">
            <div>
                <section class="bg-cyan lg:grid lg:h-screen lg:place-content-center">
                    <div class="mx-auto w-screen max-w-screen-xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8 lg:py-32">
                        <div class="max-w-prose text-left">
                            <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">
                                {{ props.post.title }}
                            </h1>

                            <p class="mt-4 text-base text-pretty text-gray-700 sm:text-lg/relaxed">
                                {{ props.post.body }}
                            </p>
                            <strong class="text-indigo-600"> {{ props.post.tag_name }} </strong> - (<strong class="text-gray-500"> {{ props.post.author }} </strong>)
                        </div>
                    </div>
                </section>
            </div>


            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <p class="py-2" v-for="paragraph in props.post.story" v-html="paragraph"></p>
                        </div>
    
                        <div class="p-6 text-gray-900 dark:text-gray-100"> 
                            <BaseIcon
                                v-if="props.post.file_id != 0"
                                :path="mdiFile"
                                class="mr-2"
                                size="20"
                            />
                            <span v-if="props.post.file_id != 0" v-html="props.post.asset">
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- comment list -->
            <div class="py-12">
                <div class="row max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="form-group">

                        <div v-for="comment in props.post.comments" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100 flex gap-3 items-center">
                                <img src="https://www.rattanhospital.in/wp-content/uploads/2020/03/user-dummy-pic.png" class="object-cover w-8 h-8 rounded-full 
                                        border-2 border-emerald-400  shadow-emerald-400
                                        ">
                                {{ comment.author }}
                                <br>
                                {{ comment.timeline }}
                            </div>
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                {{ comment.body }}
                            </div>
                        </div>
                        <br>

                    </div>
                </div>
            </div>

            <!-- comment form -->
            <div class="py-12" v-if="props.uid">
                <div class="row max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="form-group">
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="row max-w-7xl mx-auto sm:px-6 lg:px-8">
                                <div class="form-group flex gap-3 items-center">
                                    <img src="https://www.rattanhospital.in/wp-content/uploads/2020/03/user-dummy-pic.png" class="object-cover w-8 h-8 rounded-full 
                                                border-2 border-emerald-400  shadow-emerald-400
                                                ">
                                    <h3 class="font-bold">{{ props.uid.name }}</h3>
                                </div>
                            </div>
                            <div class="row max-w-7xl mx-auto sm:px-6 lg:px-8">
                                <div class="form-group flex items-center justify-center">
                                    <textarea 
                                        class="bg-gray-100 rounded border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white"
                                        v-model="form.body"
                                        value=""
                                        placeholder="Enter Body">
                                    </textarea>
                                </div>
                                <div class="w-full flex justify-end px-3">
                                <button type="submit" class="px-2.5 py-1.5 rounded-md text-white text-sm bg-indigo-500">
                                    Submit
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>