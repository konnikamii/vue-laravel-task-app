<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { message } from 'ant-design-vue';
import { LoginErrors, LoginValues } from '../utils_other/types';
import Password from '../components/svgs/password.vue';
import UserNotOutlined from '../components/svgs/userNotOutlined.vue';
import BubblesBg from '../components/app/BubblesBg.vue';
import { rippleAnimation } from '../utils_other/helperFunctions';
import { BACKEND_URL, btnClassPrimaryDarkBlue } from "../utils_other/defaults";
import SideLines from '../components/app/SideLines.vue';
import { getInitialTheme } from '../hooks/themeUtils';
import { event } from 'vue-gtag'



const defaultValues: LoginValues = { username: null, password: null };
const defaultErrors: LoginErrors = {
  username: null,
  password: null,
  global: null,
  google: null,
};

const router = useRouter();
const messageApi = message;


const theme = ref<'light' | 'dark'>(getInitialTheme());
const isLight = computed(() => theme.value === 'light');
const loadingState = ref(false);

const isDisabled = computed(() => {
  return (
    !!errors.username ||
    !!errors.password ||
    !!errors.global ||
    loadingState.value
  );
});
const values = reactive<LoginValues>({ ...defaultValues });
const errors = reactive<LoginErrors>({ ...defaultErrors });

const handleEnterSubmit = (e: KeyboardEvent) => {
  if (e.key === 'Enter') {
    handleSubmit();
  }
};
const handleSubmit = () => {
  loadingState.value = true;

  if (!values.username) {
    errors.username = "Please provide your username!";
  }
  if (!values.password) {
    errors.password = "Please provide your password!";
  }

  if (
    values.username &&
    values.password &&
    !errors.username &&
    !errors.password
  ) {
    const formData = new FormData();
    formData.append('username', values.username);
    formData.append('password', values.password);
    axios
      .post(`${BACKEND_URL}api/login/`, formData)
      .then((response: AxiosResponse<{ access_token: string, token_type: string }>) => {
        event('login', { method: 'Local' })
        messageApi.success('Login successful', 1.5);
        localStorage.setItem('access_token', response.data.access_token);
        setTimeout(() => {
          messageApi.loading('Redirecting you to the application', .8);
        }, 200);
        setTimeout(() => {
          loadingState.value = false;
        }, 500);
        setTimeout(() => {
          router.push('/app');
        }, 1000);
      })
      .catch((error: AxiosError<Record<string, string>>) => {
        const res = error.response;
        if (res) {
          messageApi.error('Validation Error');
          const newErr = res.data.detail;
          if (newErr.includes('assword')) {
            errors.password = newErr;
          } else if (newErr.includes('sername')) {
            errors.username = newErr;
          } else {
            errors.global = newErr;
          }
        } else {
          messageApi.error('An unexpected error occurred.');
          errors.global = 'An unexpected error occurred.';
        }
        setTimeout(() => {
          loadingState.value = false;
        }, 1000);
      });
  } else {
    setTimeout(() => {
      loadingState.value = false;
    }, 500);
  }
};

const handleInputChangeUsername = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value.replace(/\s+/g, ''); // removes whitespaces
  values.username = value ? value : null;
  errors.username = null;
  errors.global = null;
};
const handleInputChangePassword = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value.replace(/\s+/g, ''); // removes whitespaces
  values.password = value ? value : null;
  errors.password = null;
  errors.global = null;
};

</script>

<template>

  <BubblesBg />
  <div
    class="flex flex-col items-center justify-center gap-10 py-[90px] px-2 w-full h-fit min-h-screen transition-colors duration-300 overflow-y-auto relative bg-gray-900/20 dark:bg-gray-800/60">
    <SideLines />

    <div
      class="flex flex-col px-10 py-4 rounded-xl border shadow-lg transition-all duration-300 backdrop-blur-lg relative overflow-hidden border-gray-400 bg-gray-200/60 shadow-gray-500 hover:shadow-gray-400 dark:border-gray-600 dark:bg-white/45 dark:shadow-gray-500/40 xs:w-[430px] w-auto">
      <div class="w-full flex flex-col items-center py-6 font-bold text-3xl text-black">
        Login
      </div>
      <div class="flex flex-col gap-3 mb-6">
        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base text-black" style="letter-spacing: 0.01em;">
            Username
          </div>
          <div class="relative size-full">
            <a-input v-model:value="values.username" class="rounded-xl" size="large" placeholder="Username" id="fname"
              name="fname" allow-clear max-length="200" :status="errors.username || errors.global ? 'error' : ''"
              @keydown="handleEnterSubmit" @input="handleInputChangeUsername">
              <template #prefix>
                <UserNotOutlined class="size-4 stroke-black opacity-40" />
              </template>
            </a-input>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.username || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.username ?? errors.global }}
          </div>
        </div>

        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base text-black">
            Password
          </div>
          <div class="relative size-full">
            <a-input-password v-model:value="values.password" class="rounded-xl" size="large" placeholder="Password"
              type="password" allow-clear max-length="200" :status="errors.password || errors.global ? 'error' : ''"
              @keydown="handleEnterSubmit" @input="handleInputChangePassword">
              <template #prefix>
                <Password class="size-5 stroke-black opacity-40" />
              </template>
            </a-input-password>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.password || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.password ?? errors.global }}
          </div>
        </div>

      </div>

      <button :class="[
        btnClassPrimaryDarkBlue,
        'py-[6px] w-full flex justify-center mb-1 mt-4 dark:shadow-gray-500 dark:border-gray-600 cursor-pointer'
      ]" style="overflow: hidden;" :disabled=isDisabled @click="(e) => {
          rippleAnimation(e, isLight, 1300);
          handleSubmit();
        }">
        <div class="relative text-white">
          <div
            :class="['loader loader-dark dark:loader-light absolute top-0 left-[-32px] size-6', loadingState ? 'opacity-100' : 'opacity-0']">
          </div>
          Log In
        </div>
      </button>

      <div class="text-sm text-gray-900 text-center mb-6 mt-1">
        Don't have an account? -----
        <RouterLink to="/register" draggable="false">
          <span class="text-blue-700 hover:underline underline-offset-2 cursor-pointer">
            Sign up!
          </span>
        </RouterLink>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
