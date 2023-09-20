<template>
    <div v-if="currentUser.id" class="flex min-h-full bg-gray-200">
        <!--        Sidebar-->
        <Sidebar :class="{ '-ml-[200px]': !sidebarOpened }" />
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
        <Spinner />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import Sidebar from "./Sidebar.vue";
import Navbar from "./Navbar.vue";
import state from "../store/index.js";
import Spinner from "./core/Spinner.vue";
import store from "../store/index.js";

const { title } = defineProps({
    title: String,
});

const sidebarOpened = ref(true);
const currentUser = computed(() => store.state.user.data);

function toggleSidebar() {
    sidebarOpened.value = !sidebarOpened.value;
}

onMounted(() => {
    store.dispatch("getUser");
    handleSidebarOpened();
    window.addEventListener("resize", handleSidebarOpened);
});

onUnmounted(() => {
    window.removeEventListener("resize", handleSidebarOpened);
});

function handleSidebarOpened() {
    sidebarOpened.value = window.outerWidth > 768;
}
</script>

<style scoped></style>
