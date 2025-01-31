<script setup lang="ts">
import { email_address } from '../../utils_other/defaults'; 
</script>

<template>
  <div
    class="flex flex-col items-center justify-center min-h-screen bg-gray-100 text-black dark:bg-gray-900 dark:text-white/95">
    <div class="text-center max-w-[600px]">
      <div class="text-6xl font-extrabold text-red-600">Error</div>
      <div class="mt-4 text-2xl font-bold text-gray-800 dark:text-gray-100">
        Opps!
      </div>
      <div class="flex flex-col gap-2 mt-3 text-gray-600 dark:text-gray-400">
        <div>Something went wrong. Please try again.</div>
        <div>
          If the error persists please contact us with a description of the
          problem at
          <a :href="`mailto:${email_address}`" class="text-blue-600 hover:underline">
            {{ email_address }}
          </a>
        </div>
        <div>We will be happy to help!</div>
      </div>
      <div class="mt-6">
        <RouterLink to="/app" class="px-4 py-2 mr-4 text-white bg-red-600 rounded-lg hover:bg-red-700">
          Home Page
        </RouterLink>
        <RouterLink to="/app/dashboard" class="px-4 py-2 text-red-600 border border-red-600 rounded-lg hover:bg-red-50">
          Dashboard
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add any additional styles here if needed */
</style>