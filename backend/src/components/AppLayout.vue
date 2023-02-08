<template>
    <div v-if="currentUser.id" class="flex min-h-full bg-gray-200">
<!--        Sidebar-->
        <Sidebar :class="{'-ml-[200px]' : !sidebarOpened}"/>
<!--        end of sidebar-->
        <div class="flex-1">
            <Navbar @toggle-sidebar="toggleSidebar"></Navbar>

<!--            Content-->
            <main class="p-6">
               <div class="p-4 rounded bg-white">
                   <router-view></router-view>
               </div>
            </main>
<!--            End of Content-->
        </div>
    </div>
    <div v-else class="min-h-full bg-gray-200 flex items-center justify-center">
        <div class="flex flex-column items-center">
            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="mt-2 ">
                Please wait.......
            </div>
        </div>

    </div>
</template>

<script setup>
import {ref, onMounted, onUnmounted, computed} from 'vue'
    import Sidebar from "./Sidebar.vue";
    import Navbar from "./Navbar.vue";
    import store from "../store/index.js";

    const { title } = defineProps({
        title: String
    })

    const sidebarOpened = ref(true)
    const currentUser = computed(() => store.state.user.data)

    function toggleSidebar(){
        sidebarOpened.value= !sidebarOpened.value
    }

    onMounted(() => {
        store.dispatch('getUser')
        handleSidebarOpened()
        window.addEventListener('resize', handleSidebarOpened)
    })

    onUnmounted(() => {
        window.removeEventListener('resize', handleSidebarOpened)
    })

    function handleSidebarOpened(){
       sidebarOpened.value = window.outerWidth > 768
    }
</script>

<style scoped>

</style>
