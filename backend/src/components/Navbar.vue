<template>
    <header class="flex justify-between p-3 items-center h-14 shadow bg-white">
        <button @click="emit('toggle-sidebar')"  class="p-4 rounded transition-colors flex items-center justify-center text-gray-700 hover:bg-black/10">
            <Bars4Icon class="w-6"></Bars4Icon>
        </button>
        <div>
            <Menu as="div" class="relative inline-block text-left">
                <div>
                    <MenuButton class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/5.jpg" class="rounded-full w-8 mr-2" alt="profile-pic">
                        <small>{{ currentUser.name}}</small>
                        <ChevronDownIcon
                            class="h-5 w-5 text-violet-200 hover:text-violet-100"
                            aria-hidden="true"
                        />
                    </MenuButton>
                </div>

                <transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                >
                    <MenuItems
                        class="absolute right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                    >
                        <div class="px-1 py-1">
                            <MenuItem v-slot="{ active }">
                                <button
                                    :class="[
                  active ? 'bg-violet-500 text-white' : 'text-gray-900',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                                >
                                    <UserIcon
                                        :active="active"
                                        class="mr-2 h-5 w-5 text-violet-400"
                                        aria-hidden="true"
                                    />
                                    Profile
                                </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                                <button
                                    @click="logout"
                                    :class="[
                  active ? 'bg-violet-500 text-white' : 'text-gray-900',
                  'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                ]"
                                >
                                    <ArrowLeftOnRectangleIcon
                                        :active="active"
                                        class="mr-2 h-5 w-5 text-violet-400"
                                        aria-hidden="true"
                                    />
                                    Logout
                                </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </header>
</template>

<script setup>
import { Bars4Icon } from '@heroicons/vue/20/solid'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { ChevronDownIcon, ArrowLeftOnRectangleIcon, UserIcon } from '@heroicons/vue/20/solid'
import store from "../store/index.js";
import router from "../router/index.js";
import {computed} from "vue";

const emit =defineEmits([
    'toggle-sidebar'
])

const currentUser = computed(() => store.state.user.data)

function logout() {
    store.dispatch('logout')
        .then(()=>{
            router.push({name: 'login'})
        })
}
</script>

<style scoped>

</style>
