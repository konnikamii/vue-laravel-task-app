<script setup lang="ts">
import { ref, computed, onMounted, createVNode } from 'vue';
import Info from '../../components/svgs/info.vue';
import Settings from '../../components/svgs//navbar/settings.vue';
import { rippleAnimation } from '../../utils_other/helperFunctions';
import { getInitialTheme } from '../../hooks/themeUtils';
import type { TourProps } from 'ant-design-vue';
import { btnClassPrimaryDarkBlue } from '../../utils_other/defaults';
import User from '../../components/svgs/navbar/user.vue';
import Logo from '../../components/svgs/navbar/logo.vue';

const theme = ref<'light' | 'dark'>(getInitialTheme());
const isLight = computed(() => theme.value === 'light');

const props = defineProps<{
  ref1: HTMLElement;
  ref2: HTMLElement;
  ref3: HTMLElement;
  ref5: HTMLElement;
  ref7: HTMLElement;
  ref9: HTMLElement;
}>();
const current = ref(0);

const open = ref<boolean>(false);
const animate = ref(false);


const cardClassName = "group flex flex-col gap-6 pb-6 p-4 rounded-lg shadow-round border shadow-gray-300 hover:shadow-gray-400 bg-white  border-gray-300 hover:border-gray-400 dark:shadow-gray-800 dark:hover:shadow-gray-700 dark:bg-gray-700 dark:border-gray-700 dark:hover:border-gray-600 transition-all duration-300 relative overflow-hidden"


const maskOptions = computed(() => isLight.value ? {} : {
  style: { boxShadow: 'inset 0 0 15px #333' },
  color: '#2932369e',
});
const beginTour = (e: MouseEvent) => {
  rippleAnimation(e, isLight.value, 1300);
  open.value = true;
};
const handleOpen = (val: boolean): void => {
  open.value = val;
};
const steps: TourProps['steps'] = [
  {
    title: "Finding Your Way Around",
    description: "Use the Navigation Bar to quickly go from one section of the site to the other!",
    placement: "right",
    target: () => props.ref1 ,
  },
  {
    title: "Tooltips",
    description: "There are short tooltips for each section, explaining what it is for!",
    cover: createVNode(Info, { class: 'absolute top-[12px] left-[12px] size-10 stroke-indigo-600 fill-indigo-600' }),
    target: () => props.ref2 ,
  },
  {
    title: "Setting Your Settings",
    description: "Visiting the settings tab if you want to change your password.",
    placement: "right",
    cover: createVNode(Settings, { class: 'absolute top-[12px] left-[12px] size-10 stroke-indigo-600' }),
    target: () => props.ref3 ,
  },
  {
    title: "Ready to Analyse",
    description: "Dive into task management by navigating to the Tasks section! There you can create see all your task in a nice, systematic view. Next, visit your dashboard to see the most important tasks and how other users of the website perform.",
    placement: "right",
    target: () => props.ref5 ,
  },
  {
    title: "You - The User",
    description: "There is another way to reach your user settings - by clicking the user icon. You can also change the theme there.",
    placement: "left",
    cover: createVNode(User, { class: 'absolute top-[12px] left-[12px] size-10 stroke-indigo-600 fill-indigo-600' }),
    target: () => props.ref7 ,
  },
  {
    title: "Back Home",
    description: "To come back to this section you can click on our logo here or simply navigate to the home domain '.../app'.",
    placement: "right",
    cover: createVNode(Logo, { class: 'absolute top-[12px] left-[12px] size-10 transition-all duration-300 active:scale-90', isLight: isLight.value }),
    target: () => props.ref9 ,
  },
];
onMounted(() => {
  animate.value = true;
});

</script>

<template>
  <div class="flex flex-col items-center min-h-screen">
    <!-- Welcome screen -->
    <div :class="[
      'w-full bg-gradient-to-r to-[50%] py-6 shadow-lg from-[#86b817] to-[#0064d2] text-white shadow-gray-300 dark:from-[#a3d85f] dark:to-[#1e85e6] dark:text-black dark:shadow-gray-700'
    ]">
      <div class="max-w-7xl mx-auto px-6 text-center">
        <div class="text-4xl font-bold">
          Welcome to Your Task Manager
        </div>
        <div class="mt-2 text-lg">
          Track and analyze your tasks and improve your task management skills.
        </div>
      </div>
    </div>
    <!-- Tour section -->
    <div class="max-w-7xl mx-auto px-6 py-12 text-center" :style="{
      transition: 'all 0.5s ease-in-out',
      opacity: animate ? '100%' : '0%',
      transform: animate ? 'translateY(0)' : 'translateY(50px)',
      transitionDelay: animate ? '0ms' : '0ms',
    }">
      <h2 class="text-3xl font-bold mb-6">Take a Tour</h2>
      <div class="mt-3">
        Ready to take your task management skills to the next level?
      </div>
      <div class="mt-1 mb-4">
        Start by taking a quick tour to learn more.
      </div>
      <button :class="btnClassPrimaryDarkBlue + ' w-full'" @click="beginTour"
        :style="{ overflow: 'hidden', cursor: 'pointer' }">
        Begin Tour
      </button>
    </div>
    <!-- Features section -->
    <div class="max-w-7xl mx-auto px-6 py-12" :style="{
      transition: 'all 0.5s ease-in-out',
      opacity: animate ? '100%' : '0%',
      transform: animate ? 'translateY(0)' : 'translateY(50px)',
      transitionDelay: animate ? '400ms' : '0ms',
    }">
      <h2 class="text-3xl font-bold mb-6">Features</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div :class="cardClassName">
          <h3 class="text-xl font-semibold mb-2">
            Create and Manage Tasks
          </h3>
          <p>
            Create tasks, manage them, update them, and delete them with ease.
          </p>
        </div>
        <div :class="cardClassName">
          <h3 class="text-xl font-semibold mb-2">Dashboard</h3>
          <p>
            View upcoming tasks, completed tasks, and recently added tasks. Sort tasks by multiple criteria.
          </p>
        </div>
        <div :class="cardClassName">
          <h3 class="text-xl font-semibold mb-2">Top Users</h3>
          <p>
            See the top users with the most tasks and track their performance and task completion.
          </p>
        </div>
        <div :class="cardClassName">
          <h3 class="text-xl font-semibold mb-2">Settings</h3>
          <p>Update your user details and customize your experience.</p>
        </div>
        <div :class="cardClassName">
          <h3 class="text-xl font-semibold mb-2">Dark Theme</h3>
          <p>
            Switch between light and dark themes to suit your preference.
          </p>
        </div>
        <div :class="cardClassName">
          <h3 class="text-xl font-semibold mb-2">Responsive Design</h3>
          <p>
            Enjoy a seamless experience on both desktop and mobile devices.
          </p>
        </div>
      </div>
    </div>
    <a-tour v-model:current="current" :open="open" @close="handleOpen(false)" :steps="steps"
      :gap="{ offset: 14, radius: 25 }" :mask="maskOptions" />
  </div>
</template>

<style scoped></style>



 