<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import Logo from '../svgs/navbar/logo.vue';
import ThemeToggleLight from '../svgs/themeToggleLight.vue';
import ThemeToggleDark from '../svgs/themeToggleDark.vue';
import { getInitialTheme, useDims } from '../../hooks/themeUtils';
import { RouterLink, RouterView, useRoute } from 'vue-router';
import Dashboard from '../svgs/navbar/dashboard.vue';
import Tasks from '../svgs/navbar/tasks.vue';
import Settings from '../svgs/navbar/settings.vue';
import User from '../svgs/navbar/user.vue';
import Logout from '../svgs/navbar/logout.vue';
import LeftArrow from '../svgs/navbar/leftArrow.vue';
import Sandwich from '../svgs/navbar/sandwich.vue';
import axios from 'axios';
import { useQuery } from '@tanstack/vue-query';
import { userQueryOptions } from '../../utils_fetch/fetch';
import { authQueryOptions } from '../../utils_fetch/auth'; 

const allLinksNames = [
  {
    to: "/app",
    name: "Home",
    welcome_message: `Hello there diligent person!`,
    tooltip:
      "Welcome to the Taskify Web Application! Here you can learn more about its capabilities and how to use it.",
  },
  {
    to: "/app/user",
    name: "Settings",
    welcome_message: `Welcome to your Profile!`,
    tooltip:
      "Here you can look at your profile, change your password or modify your account.",
  },
  {
    to: "/app/dashboard",
    name: "Dashboard",
    welcome_message: `Welcome to your Dashboard!`,
    tooltip: "Here you can have a good overview of your overall activity.",
  },
  {
    to: "/app/tasks",
    name: "Tasks",
    welcome_message: `Welcome to the Tasks section!`,
    tooltip:
      "Here you can review, update or delete tasks. Use filters for more specific selection.",
  },
  {
    to: "/app/tasks/$taskid",
    name: "Single Task",
    welcome_message: `Welcome to the Single Task section!`,
    tooltip: "Here you can review each individual Task and modify it.",
  },
];
const navbarMainLinks = [
  {
    to: "/app/dashboard",
    name: "Dashboard",
    icon: Dashboard,
  },
  {
    to: "/app/tasks",
    name: "Tasks",
    icon: Tasks,
  },
];
const navbarToolLinks = [
  {
    to: "/app/user",
    name: "Settings",
    icon: Settings,
  },
];
const navbarTopLinks = [
  { to: "/app", name: "Home" },
  { to: "/app/user", name: "Settings" },
];
const delayClasses = [
  "",
  "delay-[50ms]",
  "delay-[100ms]",
  "delay-[150ms]",
  "delay-[200ms]",
  "delay-[250ms]",
];
// const themeBackgroundColors = {
//   light: "#ffffff",
//   dark: "#000000",
// };

const route = useRoute();


const theme = ref<'light' | 'dark'>(getInitialTheme());
const isLight = computed(() => theme.value === 'light');
const dims = useDims();

const toggleTheme = () => {
  theme.value = isLight.value ? 'dark' : 'light';
  localStorage.setItem('theme', theme.value);
  document.getElementById('parent-div')?.classList.toggle('dark', theme.value === 'dark');
};

const leftNavOpen = ref(true);
const dropdownOpen = ref(false);
const matchRoute = (options: { to: string }) => {
  return route.path === options.to;
};

const handleLogout = () => {
  localStorage.removeItem('access_token');
  window.location.href = "/";
};
useQuery(authQueryOptions())
const userQ = useQuery(userQueryOptions());
const userData = computed(() => !userQ.data.value || axios.isAxiosError(userQ.data.value) ? null : userQ.data.value);

const ref1 = ref<HTMLElement | null>(null);
const ref2 = ref<HTMLElement | null>(null);
const ref3 = ref<HTMLElement | null>(null);
const ref5 = ref<HTMLElement | null>(null);
const ref7 = ref<HTMLElement | null>(null);
const ref9 = ref<HTMLElement | null>(null);
const loading = ref(true);

const checkRefsAndRoute = () => {
  if (route.path === '/app') {
    if (ref1.value && ref2.value && ref3.value && ref5.value && ref7.value && ref9.value) {
      console.log('Refs initialized...');
      setTimeout(() => { loading.value = false; }, 100); 
    } 
  } else {
    loading.value = false;
  }
};

onMounted(() => {
  checkRefsAndRoute();
});

watch(route, () => {
  checkRefsAndRoute();
});
</script>

<template>
  <div v-if="dims.plus1024">

    <!-- Side Navbar -->
    <div ref="ref1" :class="[
      'z-[30] fixed h-screen flex flex-col bg-gradient-to-b transition-all duration-300 text-black bg-white/80 dark:text-white dark:from-gray-800 dark:to-gray-800',
      leftNavOpen ? '2xl:w-[250px] w-[200px]' : '2xl:w-[80px] w-[60px]',
    ]" @mousedown.prevent>
      <!-- Logo -->
      <div ref="ref9" :class="[
        'flex w-full min-h-[90px] items-center border-r-2 border-b-2 relative border-gray-300 dark:border-gray-600'
      ]">
        <RouterLink :class="[
          'flex w-full justify-start overflow-hidden gap-2 relative transition-all duration-100 2xl:pl-4',
          leftNavOpen ? 'pl-2' : 'pl-1'
        ]" to="/app">
          <Logo class="shrink-0 transition-all duration-300 active:scale-90 2xl:size-12 size-11" :isLight="isLight" />
          <div :class="[
            'absolute top-1/2 transition-all duration-100 font-medium 2xl:text-3xl 2xl:left-[90px] 2xl:translate-y-[-50%] text-2xl left-[72px] translate-y-[-50%]',
            leftNavOpen ? '' : 'translate-x-20',
          ]" style="font-family: 'Pacifico'">
            Taskify
          </div>
        </RouterLink>
        <div
          class=" group absolute top-1/2 right-0 translate-x-1/2 -translate-y-1/2 py-[6px] px-[3px] rounded-xl border transition-all duration-300 active:scale-95 cursor-pointer bg-black hover:bg-white border-gray-700 dark:bg-white dark:hover:bg-black dark:border-gray-300 "
          @click="leftNavOpen = !leftNavOpen">
          <LeftArrow :class="['2xl:size-4 size-3 transition-all duration-300 stroke-white group-hover:stroke-black dark:stroke-black dark:group-hover:stroke-white ',
            leftNavOpen ? 'translate-x-[3px] translate-y-[1px]' : 'rotate-180 translate-x-[-2px]',
          ]" />
        </div>
      </div>

      <!-- Links -->
      <div :class="[
        'flex flex-col flex-1 pt-4 border-r-2 transition-all duration-300 border-gray-300 dark:border-gray-600',
        dims.plus768h ? 'gap-3' : dims.plus500h ? 'gap-1' : 'gap-0',
        leftNavOpen ? '2xl:pl-12 2xl:pr-11 px-8' : '2xl:px-3 px-2'
      ]">
        <!-- Navigation links MAIN -->
        <div ref="ref5" :class="[
          'flex flex-col',
          dims.plus768h ? 'gap-3 pt-4' : dims.plus500h ? 'gap-1' : 'gap-0'
        ]">
          <div v-if="dims.plus500h" :class="[
            'font-bold transition-all duration-300 text-gray-600 dark:text-gray-300 2xl:text-sm text-xs',
            leftNavOpen ? '' : '2xl:pl-[9px] pl-[6px]',
          ]">
            MAIN
          </div>
          <RouterLink v-for="link in navbarMainLinks" :key="link.name" :to="link.to" :class="[
            'group group-w-full flex items-center gap-3 rounded-md transition-all duration-300 active:scale-95 overflow-hidden',
            dims.plus768h ? 'py-3' : 'py-2',
            leftNavOpen ? 'px-4' : '2xl:px-[15px] 2xl:hover:w-fit px-[11px] hover:w-fit',
            isLight ? (matchRoute({ to: link.to }) ? 'bg-blue-500' : 'hover:bg-blue-500') : `font-medium ${matchRoute({ to: link.to }) ? 'bg-blue-400' : 'hover:bg-blue-400'}`
          ]">
            <component :is="link.icon" :class="[
              'shrink-0 fill-none transition-all duration-100 2xl:size-6 size-5',
              isLight ? `group-hover:stroke-white ${matchRoute({ to: link.to }) ? 'stroke-white' : 'stroke-black'}` : `group-hover:stroke-black ${matchRoute({ to: link.to }) ? 'stroke-black' : 'stroke-white'}`
            ]" />
            <div :class="[
              'relative transition-all duration-300 2xl:text-base text-sm',
              leftNavOpen ? 'opacity-100' : 'slide-right-and-back opacity-0 group-hover:opacity-100',
            ]">
              <span :class="[
                'relative underline-animation',
                isLight ? `${matchRoute({ to: link.to }) ? 'text-white' : 'group-hover:text-white'}` : `${matchRoute({ to: link.to }) ? 'text-black' : 'group-hover:text-black'}`
              ]">
                {{ link.name }}
              </span>
            </div>
          </RouterLink>
        </div>

        <!-- Navigation links TOOLS -->
        <div ref="ref3" :class="[
          'flex flex-col',
          dims.plus768h ? 'gap-3 pt-8' : dims.plus500h ? 'gap-1 pt-2' : 'gap-0'
        ]">
          <div v-if="dims.plus500h" :class="[
            'font-bold transition-all duration-300 2xl:text-sm text-xs text-gray-600 dark:text-gray-300',
            leftNavOpen ? '' : '2xl:pl-[9px] pl-[6px]',
          ]">
            TOOLS
          </div>
          <RouterLink v-for="link in navbarToolLinks" :key="link.name" :to="link.to" :class="[
            'group group-w-full flex items-center gap-3 rounded-md transition-all duration-300 active:scale-95 overflow-hidden',
            dims.plus768h ? 'py-3' : 'py-2',
            leftNavOpen ? 'px-4' : '2xl:px-[15px] 2xl:hover:w-fit px-[11px] hover:w-fit',
            isLight ? (matchRoute({ to: link.to }) ? 'bg-blue-500' : 'hover:bg-blue-500') : `font-medium ${matchRoute({ to: link.to }) ? 'bg-blue-400' : 'hover:bg-blue-400'}`
          ]">
            <component :is="link.icon" :class="[
              'shrink-0 fill-none transition-all duration-100 2xl:size-6 size-5',
              isLight ? `group-hover:stroke-white ${matchRoute({ to: link.to }) ? 'stroke-white' : 'stroke-black'}` : `group-hover:stroke-black ${matchRoute({ to: link.to }) ? 'stroke-black' : 'stroke-white'}`
            ]" />
            <div :class="[
              'relative transition-all duration-300 2xl:text-base text-sm',
              leftNavOpen ? 'opacity-100' : 'slide-right-and-back opacity-0 group-hover:opacity-100',
            ]">
              <span :class="[
                'relative underline-animation',
                isLight ? `${matchRoute({ to: link.to }) ? 'text-white' : 'group-hover:text-white'}` : `${matchRoute({ to: link.to }) ? 'text-black' : 'group-hover:text-black'}`
              ]">
                {{ link.name }}
              </span>
            </div>
          </RouterLink>
        </div>
      </div>

      <!-- Logout -->
      <div :class="[
        'flex flex-col justify-center border-r-2 border-t-2 transition-all duration-300 border-gray-300 dark:border-gray-600',
        dims.plus768h ? 'min-h-[75px]' : 'min-h-[60px]',
        leftNavOpen ? '2xl:px-12 px-8' : '2xl:px-3 px-2',
      ]">
        <div :class="[
          'group group-w-full flex items-center gap-3 rounded-md transition-all duration-300 active:scale-95 overflow-hidden cursor-pointer',
          dims.plus768h ? 'py-3' : 'py-2',
          leftNavOpen ? 'px-4' : 'px-[15px] hover:w-fit',
          isLight ? 'hover:bg-blue-500' : 'font-medium hover:bg-blue-400'
        ]" @click="handleLogout">
          <Logout :class="[
            'shrink-0 fill-none transition-all duration-100 2xl:size-6 size-5 group-hover:stroke-white stroke-black dark:group-hover:stroke-black dark:stroke-white'
          ]" />
          <div :class="[
            'relative transition-all duration-300 2xl:text-base text-sm',
            leftNavOpen ? 'opacity-100' : 'slide-right-and-back opacity-0 group-hover:opacity-100',
          ]">
            <span :class="[
              'relative underline-animation',
              isLight ? 'text-black group-hover:text-white' : 'text-white group-hover:text-black'
            ]">
              Logout
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Navbar -->
    <div :class="['fixed flex items-center justify-between w-full min-h-[90px] max-h-[90px] shadow-lg border-b-2 bg-gradient-to-r transition-all duration-300 text-black bg-white/80 border-gray-300 shadow-gray-300/30 dark:text-white dark:from-gray-800 dark:to-gray-800 dark:border-gray-600 dark:shadow-gray-600/30 z-[20] backdrop-blur-md',
      leftNavOpen ? '2xl:ml-[250px] ml-[200px]' : '2xl:ml-[80px] ml-[60px]',
    ]"
      :style="{ width: `calc(100% - ${leftNavOpen ? (dims.plus1440 ? '250px' : '200px') : dims.plus1440 ? '80px' : '60px'})` }"
      @mousedown.prevent>
      <div ref="ref2" class=" ml-12 2xl:w-3/4 2xl:text-xl w-2/3 text-lg ">
        <div class="font-bold">
          {{ allLinksNames.find(route => matchRoute({ to: route.to }))?.welcome_message }}
        </div>
        <div class=" 2xl:text-base text-sm mt-2 ">
          {{ allLinksNames.find(route => matchRoute({ to: route.to }))?.tooltip }}
        </div>
      </div>

      <div ref="ref7" class=" flex justify-end items-center gap-6 pr-4 2xl:w-1/4 w-1/3 ">
        <div
          class="w-12 h-6 px-[6px] py-[3px] bg-gray-400 shadow-inner shadow-gray-700 border border-gray-400 rounded-full cursor-pointer relative"
          @click="toggleTheme">
          <ThemeToggleLight :class="[
            'absolute left-1 size-4 bg-white fill-yellow-500 rounded-full transition-all duration-300',
            isLight ? 'opacity-100 rotate-[-360deg] translate-x-0' : 'translate-x-6 opacity-0 rotate-0'
          ]" />
          <ThemeToggleDark :class="[
            'absolute left-1 size-4 bg-white fill-black rounded-full transition-all duration-300',
            isLight ? 'opacity-0 rotate-[-360deg] translate-x-0' : 'translate-x-6 opacity-100 rotate-0'
          ]" />
        </div>
        <RouterLink class="group flex items-center gap-2" to="/app/user">
          <div v-if="userData">
            <div class=" 2xl:text-base text-xs text-gray-600 dark:text-white ">
              {{ userData.username }}
            </div>
            <div class="2xl:text-xs text-[10px] text-gray-400 dark:text-gray-300 ">
              Plan: free
            </div>
          </div>
          <User :class="[
            'rounded-2xl active:scale-95 transition-all duration-200 2xl:size-12 size-10',
            isLight ? `${matchRoute({ to: '/app/user' }) ? 'stroke-white fill-white bg-black' : 'fill-black stroke-black group-hover:fill-white group-hover:stroke-white group-hover:bg-black'}` : `${matchRoute({ to: '/app/user' }) ? 'stroke-black fill-black bg-white' : 'fill-white stroke-white group-hover:fill-black group-hover:stroke-black group-hover:bg-white'}`
          ]" />
        </RouterLink>
      </div>
    </div>

    <!-- Main content -->
    <div :class="[
      'absolute w-full h-fit min-h-screen pt-[90px] bg-gradient-to-b transition-all duration-300 text-black from-gray-100 to-gray-200 dark:text-white dark:from-gray-900 dark:to-gray-900',
      leftNavOpen ? '2xl:ml-[250px] ml-[200px]' : '2xl:ml-[80px] ml-[60px]',
    ]"
      :style="{ width: `calc(100% - ${leftNavOpen ? (dims.plus1440 ? '250px' : '200px') : dims.plus1440 ? '80px' : '60px'})` }">
      <div class="absolute w-full overflow-hidden pointer-events-none" :style="{ height: 'calc(100% - 90px)' }">
        <div class="w-full h-full relative overflow-hidden">
          <div :class="[
            'scaling-up-side absolute top-0 left-0 translate-x-[-75%] h-full bg-[#c83bf3] roundeds-full filter blur-[30px]',
            isLight ? 'opacity-60 w-[3px] mix-blend-darken' : 'opacity-20 w-[7px] mix-blend-lighten'
          ]"></div>
          <div :class="[
            'scaling-up-side absolute top-0 right-0 translate-x-[75%] h-full bg-[#c83bf3] roundeds-full filter blur-[30px]',
            isLight ? 'opacity-60 w-[3px] mix-blend-darken' : 'opacity-20 w-[7px] mix-blend-lighten'
          ]"></div>
        </div>
      </div>
      <div v-if="route.path === '/app'">
        <div v-if="loading">
          <div class="flex items-center justify-center w-full h-full">
            <div class="loader"></div>
          </div>
        </div>
        <div v-else> 
          <RouterView v-slot="{ Component }">
            <component :is="Component" :ref1="ref1" :ref2="ref2" :ref3="ref3" :ref5="ref5" :ref7="ref7" :ref9="ref9" />
          </RouterView>
        </div>
      </div>
      <div v-else> 
        <RouterView/> 
      </div> 
    </div>

  </div>
  <div v-else>
    <!-- Top Navbar -->
    <div :class="[
      'fixed top-0 left-0 z-[20] w-screen min-h-[65px] border-b-2 shadow-sm backdrop-blur-sm transition-all duration-300 overflow-hidden select-none border-gray-300 dark:border-gray-700 dark:bg-gradient-to-r dark:from-gray-800 dark:to-gray-800 dark:text-white',
      dropdownOpen ? 'max-h-screen' : 'max-h-[65px] delay-[100ms]',
    ]" >
      <div class="flex flex-col items-center gap-2 pb-4">
        <div class="flex justify-between items-center w-full px-4 h-[65px]">
          <RouterLink class="flex-1" to="/app">
            <Logo class="shrink-0 transition-all duration-100 active:scale-90 2xs:size-12 size-11" :isLight="isLight" />
          </RouterLink>
          <div class="flex-0 font-semibold 2xs:text-xl text-lg">
            {{ allLinksNames.find(route => matchRoute({ to: route.to }))?.name }}
          </div>
          <div class="flex flex-1 justify-end items-center gap-2">
            <RouterLink to="/app/user" @click="dropdownOpen = false">
              <User :class="[
                'rounded-lg transition-all duration-300 2xs:size-7 size-6',
                isLight ? (matchRoute({ to: '/app/user' }) ? 'stroke-white fill-white bg-black' : 'stroke-black fill-black') : (matchRoute({ to: '/app/user' }) ? 'stroke-black fill-black bg-white' : 'stroke-white fill-white')
              ]" />
            </RouterLink>
            <button @click="dropdownOpen = !dropdownOpen">
              <Sandwich :class="[
                'transition-transform duration-200 delay-0 2xs:size-8 size-7 stroke-black dark:stroke-white',
                dropdownOpen ? 'sandwich-open scale-[.8]' : 'delay-[100ms]',
              ]" />
            </button>
          </div>
        </div>

        <!-- Navigation links TOOLS -->
        <RouterLink v-for="(link, index) in navbarTopLinks" :key="link.name" :to="link.to" :class="[
          'transition-all duration-300',
          dropdownOpen ? delayClasses[index] : 'delay-[100ms] opacity-0 ' + (index % 2 === 0 ? '-' : '') + 'translate-x-10',
          matchRoute({ to: link.to }) ? 'underline-animation-active' : ''
        ]" @click="dropdownOpen = false">
          <div class="relative transition-all duration-300">
            <span :class="[
              'relative underline-animation 2xs:text-base text-sm',
              isLight ? (matchRoute({ to: link.to }) ? 'text-black' : 'group-hover:text-white') : (matchRoute({ to: link.to }) ? 'text-white' : 'group-hover:text-black')
            ]">
              {{ link.name }}
            </span>
          </div>
        </RouterLink>

        <!-- Logout button -->
        <div :class="[
          'transition-all duration-300 2xs:text-base text-sm',
          dropdownOpen ? 'delay-[200ms]' : 'delay-[100ms] opacity-0 -translate-x-10'
        ]" @click="handleLogout">
          Logout
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div
      class=" absolute w-full h-fit min-h-screen pt-[65px] bg-gradient-to-b transition-all duration-300 text-black from-gray-100 to-gray-200 dark:text-white dark:from-gray-900 dark:to-gray-900 ">
      <div class="absolute w-full h-full overflow-hidden pointer-events-none" :style="{ height: 'calc(100% - 65px)' }">
        <div class="w-full h-full relative overflow-hidden">
          <div
            class=" scaling-up-side absolute top-0 left-0 translate-x-[-75%] h-full bg-[#c83bf3] roundeds-full filter blur-[30px] opacity-60 w-[4px] mix-blend-darken dark:opacity-20 dark:w-[6px] dark:mix-blend-lighten ">
          </div>
          <div
            class=" scaling-up-side absolute top-0 right-0 translate-x-[75%] h-full bg-[#c83bf3] roundeds-full filter blur-[30px] opacity-60 w-[4px] mix-blend-darken dark:opacity-20 dark:w-[6px] dark:mix-blend-lighten ">
          </div>
        </div>
      </div>
      <RouterView />
    </div>

    <!-- Navbar Bottom -->
    <div class="fixed bottom-5 w-screen z-[10]">
      <div 
        class=" absolute bottom-0 left-1/2 -translate-x-1/2 w-fit flex py-1 px-2 gap-1 items-center rounded-full shadow-inner border-2 transition-all duration-300 border-gray-400 shadow-[#474747] bg-gray-200 dark:border-gray-400 dark:shadow-[#2c2c2c] dark:bg-gradient-to-br dark:from-gray-700 dark:to-gray-900 ">
        <div v-for="link in navbarMainLinks" :key="link.name" :class="[
          'overflow-hidden transition-all duration-300 ease-in-out rounded-full shadow-md',
          isLight ? (matchRoute({ to: link.to }) ? 'max-w-[130px] shadow-slate-800 bg-black' : 'max-w-[46px] bg-transparent shadow-transparent') : (matchRoute({ to: link.to }) ? 'max-w-[130px] shadow-gray-500 bg-blue-400' : 'max-w-[46px] bg-transparent shadow-transparent')
        ]">
          <RouterLink :to="link.to" :class="[
            'flex items-center text-sm font-bold gap-2 px-2 py-[6px] overflow-hidden',
            isLight ? (matchRoute({ to: link.to }) ? 'text-white' : 'text-black') : (matchRoute({ to: link.to }) ? 'text-black' : 'text-white')
          ]" @click="dropdownOpen = false">
            <div :class="[
              'p-1 border rounded-full transition-all duration-500 ease-in-out',
              isLight ? (matchRoute({ to: link.to }) ? 'scale-[1.15] shadow-sm shadow-gray-700 border-white' : 'scale-100 border-black') : (matchRoute({ to: link.to }) ? 'scale-[1.15] shadow-sm shadow-gray-600 border-black' : 'scale-100 border-white')
            ]">
              <component :is="link.icon" :class="[
                'shrink-0 size-5 fill-none',
                isLight ? (matchRoute({ to: link.to }) ? 'stroke-white' : 'stroke-black') : (matchRoute({ to: link.to }) ? 'stroke-black' : 'stroke-gray-300')
              ]" />
            </div>
            <div :class="[
              'overflow-hidden transition-all duration-150 ease-in-out',
              matchRoute({ to: link.to }) ? 'translate-x-0 delay-150' : 'translate-x-20'
            ]">
              <div>{{ link.name }}</div>
            </div>
          </RouterLink>
        </div>
      </div>
    </div>

  </div>
</template>

<style scoped>
/* Add your styles here */
</style>