<script setup lang="ts">
import { ref, computed } from 'vue';
import Logo from '../svgs/navbar/logo.vue';
import ThemeToggleLight from '../svgs/themeToggleLight.vue';
import ThemeToggleDark from '../svgs/themeToggleDark.vue';
import { getInitialTheme } from '../../hooks/themeUtils';



const theme = ref<'light' | 'dark'>(getInitialTheme());
const isLight = computed(() => theme.value === 'light');


const toggleTheme = () => {
  theme.value = isLight.value ? 'dark' : 'light';
  localStorage.setItem('theme', theme.value);
  document.getElementById('parent-div')?.classList.toggle('dark', theme.value === 'dark');
};
</script>

<template>
  <div
    class="fixed w-full h-[80px] transition-all duration-300 border-b flex justify-between items-center gap-2 backdrop-blur z-10 text-black bg-gray-300/60 shadow-lg shadow-gray-400/40 border-gray-400/80 dark:text-white dark:bg-black/80 dark:shadow-2xl dark:shadow-gray-700/60 dark:border-gray-600/80 lg:pl-[150px] lg:pr-[40px] pl-[25px] pr-[25px]">
    <a class="flex gap-4 items-center cursor-pointer" href="/">
      <Logo class="transition-all duration-300 active:scale-90 2xl:size-12 lg:size-11 size-10" :isLight="isLight" />
      <div
        class="font-bold transition-all duration-300 text-black dark:text-white 2xl:text-3xl lg:text-2xl text-xl 2xs:block hidden"
        style="font-family: ''">
        Taskify
      </div>
    </a>

    <div class="flex items-center gap-6">
      <div
        class="w-12 h-6 px-[6px] py-[3px] bg-gray-400 shadow-inner shadow-gray-700 border border-gray-400 rounded-full cursor-pointer relative"
        @click="toggleTheme">
        <ThemeToggleLight
          class="absolute left-1 size-4 bg-white fill-yellow-500 rounded-full transition-all duration-300 opacity-100 rotate-[-360deg] translate-x-0 dark:translate-x-6 dark:opacity-0 dark:rotate-0" />
        <ThemeToggleDark
          class="absolute left-1 size-4 bg-white fill-black rounded-full transition-all duration-300 opacity-0 rotate-[-360deg] translate-x-0 dark:translate-x-6 dark:opacity-100 dark:rotate-0" />
      </div>

      <div class="group-fast">
        <RouterLink to="/contact"
          class="relative underline-animation font-medium text-lg hover:text-blue-500 transition duration-300"
          draggable="false">
          Contact Us
        </RouterLink>
      </div>
      <div class="group-fast">
        <RouterLink to="/login"
          class="relative underline-animation font-medium text-lg hover:text-blue-500 transition duration-300"
          draggable="false">
          Login
        </RouterLink>
      </div>
      <RouterLink to="/register"
        class="popup-button bg-gradient-to-r from-[#6ccf54] via-[#1c8ecf] to-[#322fff] from-[10%] to-[90%] text-white font-bold text-center uppercase rounded-lg py-[10px] px-6 shadow-lg cursor-pointer hover:scale-[102%]"
        draggable="false">
        Sign Up
      </RouterLink>
    </div>
  </div>

  <RouterView />
</template>

<style scoped>
/* Add your styles here */
</style>