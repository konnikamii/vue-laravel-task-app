<script setup lang="ts">
import SideLines from './SideLines.vue';

// No setup
</script>

<template>
  <SideLines />
  <div
    class="flex flex-col items-center justify-center min-h-screen bg-gray-100 text-black dark:bg-gray-900 dark:text-white/95">
    <div class="text-center">
      <h1 class="text-6xl font-extrabold text-red-600">404</h1>
      <h2 class="mt-4 text-2xl font-bold text-gray-800 dark:text-gray-100">
        Page Not Found
      </h2>
      <p class="mt-2 text-gray-600 dark:text-gray-400">
        Sorry, we couldn't find the page you were looking for.
      </p>
      <div class="mt-6">
        <RouterLink to="/" class="px-4 py-2 text-white bg-red-600 rounded-lg hover:bg-red-700">
          Home Page
        </RouterLink>
      </div>
    </div>
    <div class="flex flex-col justify-center items-center mt-10">
      <h3 class="text-lg font-semibold text-black dark:text-gray-100">
        Quick Links
      </h3>
      <div class="flex gap-4 mt-4">
        <RouterLink to="/login" class="text-gray-800 dark:text-gray-400 hover:text-red-600">
          Login
        </RouterLink>
        <RouterLink to="/register" class="text-gray-800 dark:text-gray-400 hover:text-red-600">
          Register
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add any additional styles here if needed */
</style>