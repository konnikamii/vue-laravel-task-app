<script setup lang="ts">
import { computed, onMounted, ref } from "vue";
import TopNavbar from "./components/app/TopNavbar.vue";
import { getInitialTheme } from "./hooks/themeUtils";
import { VueQueryDevtools } from "@tanstack/vue-query-devtools";
import { useRoute } from "vue-router";
import AppNavbar from "./components/app/AppNavbar.vue";
import Cookies from "./components/app/Cookies.vue";

const theme = ref<"light" | "dark">(getInitialTheme());
const route = useRoute();

const showTopNavbar = computed(() => {
  return !route.path.startsWith("/app");
});

onMounted(() => {
  const parentDiv = document.getElementById("parent-div");
  if (parentDiv) {
    if (theme.value === "dark") {
      parentDiv.classList.add("dark");
    } else {
      parentDiv.classList.remove("dark");
    }
  }
});
</script>

<template>
  <div id="parent-div" class="text-black dark:text-white">
    <TopNavbar v-if="showTopNavbar" />
    <AppNavbar v-else />
  </div>
  <Cookies />
  <VueQueryDevtools />
</template>

<style scoped></style>
