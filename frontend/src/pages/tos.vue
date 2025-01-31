<script setup lang="ts">
import { RouterLink } from "vue-router";
import SideLines from "../components/app/SideLines.vue";

</script>

<template>

  <div
    class="flex flex-col items-center justify-center gap-10 py-[90px] px-2 w-full h-fit min-h-screen transition-colors duration-300 overflow-y-auto relative bg-gray-100 dark:bg-gray-800">
    <SideLines />

    <div
      class="flex flex-col px-10 py-4 rounded-xl border shadow-lg transition-all duration-300 backdrop-blur-lg relative overflow-hidden border-gray-400 bg-gray-200/40 shadow-gray-200 hover:shadow-gray-300 dark:border-gray-600 dark:bg-white/65 dark:shadow-gray-500/40 md:w-[430px] w-auto text-black">

      <div class="w-full flex flex-col items-center py-6 font-bold text-3xl text-bl">
        ToS Page
      </div>
      <div class="flex flex-col gap-3 mb-6 text-center">
        Add your ToS here.
      </div>
      <div class="text-sm text-gray-900 text-center mb-1">
        Back to
        <RouterLink to="/">
          <span class="text-blue-700 hover:underline underline-offset-2 cursor-pointer">
            Home Page!
          </span>
        </RouterLink>
      </div>
      <div class="text-sm text-gray-900 text-center mb-4">
        Or
        <RouterLink to="/login">
          <span class="text-blue-700 hover:underline underline-offset-2 cursor-pointer">
            Log In!
          </span>
        </RouterLink>
        /
        <RouterLink to="/register">
          <span class="text-blue-700 hover:underline underline-offset-2 cursor-pointer">
            Sign Up!
          </span>
        </RouterLink>
      </div>
    </div>
  </div>
</template>