<script setup lang="ts">
import { RouterLink } from "vue-router";
import SideLines from "../components/app/SideLines.vue";

</script>

<template>
  <div
    class="flex flex-col items-center justify-center gap-10 py-[90px] px-2 w-full h-fit min-h-screen transition-colors duration-300 overflow-y-auto relative bg-gray-100 dark:bg-gray-800">
    <SideLines />

    <div
      class="flex flex-col px-10 py-4 rounded-xl border shadow-lg transition-all duration-300 backdrop-blur-sm relative overflow-hidden border-gray-400 bg-gray-200/40 shadow-gray-200 hover:shadow-gray-300 dark:border-gray-600 dark:bg-white/65 dark:shadow-gray-500/40 md:w-[730px] w-auto text-black">
      <div
        class="grow-left absolute top-0 left-0 translate-x-[-45%] -translate-y-[95%] w-[200%] aspect-square bg-[#0064d2] rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-60">
      </div>
      <div
        class="scaling-up absolute bottom-0 left-0 translate-y-[90%] w-[400%] aspect-square bg-[#f5af02] rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-60">
      </div>
      <div class="w-full flex flex-col items-center py-6 font-bold text-3xl">
        Home page
      </div>
      <div class="flex flex-col gap-3 mb-6">Hello there!</div>
      <div class="flex flex-col gap-3 mb-6">
        This is an application that manages and generates tasks and deadlines for individual users.
      </div>
      <div class="flex flex-col gap-3 mb-6">
        **Key Features of the Application:**
        <ul class="list-disc pl-5">
          <li>
            <strong>Task Creation:</strong> Easily create tasks by providing a name, description, and deadline.
          </li>
          <li>
            <strong>Task Management:</strong> Edit, update, or delete tasks as your priorities change.
          </li>
          <li>
            <strong>Automatic Deadlines:</strong> Generate deadlines based on predefined rules to save time.
          </li>
          <li>
            <strong>Reminders:</strong> Stay on top of your tasks with automated reminders and notifications.
          </li>
          <li>
            <strong>Progress Tracking:</strong> Visualize your task completion rate and identify areas for improvement.
          </li>
          <li>
            <strong>Priority System:</strong> Assign priority levels to tasks to focus on what matters most.
          </li>
          <li>
            <strong>User-Friendly Interface:</strong> Navigate the app easily with an intuitive and responsive design.
          </li>
          <li>
            <strong>Secure Login:</strong> Keep your tasks and data secure with account-based access.
          </li>
        </ul>
      </div>

      <div class="flex flex-col gap-3 mb-6">
        The application is ideal for individuals who want to organize their day-to-day activities or manage
        project-based deadlines. Whether you're a student, a professional, or someone who simply enjoys staying
        productive, this tool is tailored to help you succeed.
      </div>
      <div class="text-sm text-gray-900 text-center mb-6">
        Don't have an account? -----
        <RouterLink to="/register">
          <span class="text-blue-700 hover:underline underline-offset-2 cursor-pointer">
            Sign up!
          </span>
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
