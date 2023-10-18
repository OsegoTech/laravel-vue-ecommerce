<template>
    <GuestLayout title="Sign in to your Account">
        <form class="mt-8 space-y-6" @submit.prevent="login" method="POST">
            <div v-if="errorMsg" class="flex items-center justify-around py-3 px-5 bg-red-500 text-white rounded">
                {{errorMsg}}
                <span @click="errorMsg = ''" class="w-8 h-8 flex items-center justify-between rounded-full transition-colors cursor-pointer hover:bg-black/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                     </svg>
                </span>
            </div>
            <input type="hidden" name="remember" value="true" />
            <div class="-space-y-px rounded-md shadow-sm">
                <div>
                    <label for="email-address" class="sr-only">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required="" v-model="user.email"
                           class="relative block w-full appearance-none rounded-none rounded-t-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="Email address" />
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required="" v-model="user.password"
                           class="relative block w-full appearance-none rounded-none rounded-b-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" placeholder="Password" />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" v-model="user.remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                </div>

                <div class="text-sm">
                    <RouterLink :to="{name: 'requestPassword'}" href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</RouterLink>
                </div>
            </div>

            <div>
                <button type="submit"
                        :disabled="loading"
                        class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        :class="{
                            'cursor-not-allowed': loading,
                            'hover:bg-indigo-500': loading
                        }"
                >
                    <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                      <LockClosedIcon class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" aria-hidden="true" />
                    </span>
                    Sign in
                </button>
            </div>
        </form>
    </GuestLayout>
</template>

<script setup>
import { LockClosedIcon } from '@heroicons/vue/20/solid'
import GuestLayout from "../components/GuestLayout.vue";
import {ref} from "vue";
import store from "../store/index.js";
import router from "../router/index.js";
let loading = ref(false)
let errorMsg = ref("")

const user = {
    email: '',
    password: '',
    remember: false
}

function login(){
    loading.value = true;
    store.dispatch('login', user)
        .then(()=>{
            loading.value=false;
            router.push({name: 'app.dashboard'})
        })
        .catch(({response}) => {
            loading.value = false;
            errorMsg.value = response.data.message;
        })
}
</script>

<style scoped>

</style>
