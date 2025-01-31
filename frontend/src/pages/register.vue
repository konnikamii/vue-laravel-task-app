<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { message } from 'ant-design-vue';
import { RegisterErrors, RegisterValues } from '../utils_other/types';
import Password from '../components/svgs/password.vue';
import UserNotOutlined from '../components/svgs/userNotOutlined.vue';
import Email from '../components/svgs/email.vue';
import BubblesBg from '../components/app/BubblesBg.vue';
import { rippleAnimation, validateEmail, validatePassword, validateUsername } from '../utils_other/helperFunctions';
import { BACKEND_URL, btnClassPrimaryDarkBlue } from "../utils_other/defaults";
import SideLines from '../components/app/SideLines.vue';
import { getInitialTheme } from '../hooks/themeUtils';
import { event } from 'vue-gtag'

const defaultValues = {
  username: null,
  email: null,
  password: null,
  confirm_password: null,
};

const defaultErrors = {
  username: null,
  email: null,
  password: null,
  confirm_password: null,
  global: null,
};

const router = useRouter();
const messageApi = message;


const theme = ref<'light' | 'dark'>(getInitialTheme());
const isLight = computed(() => theme.value === 'light');
const loadingState = ref(false);

const isDisabled = computed(() => {
  return (
    !!errors.username ||
    !!errors.email ||
    !!errors.password ||
    !!errors.confirm_password ||
    !!errors.global ||
    loadingState.value
  );
});
const values = reactive<RegisterValues>({ ...defaultValues });
const errors = reactive<RegisterErrors>({ ...defaultErrors });

const handleEnterSubmit = (e: KeyboardEvent) => {
  if (e.key === 'Enter') {
    handleSubmit();
  }
};
const handleSubmit = () => {
  loadingState.value = true;
  const usernameErr = validateUsername(values.username);
  const emailErr = validateEmail(values.email);
  const passwordErr = validatePassword(values.password);
  const confirmPasswordErr = values.confirm_password
    ? values.password === values.confirm_password
      ? null
      : 'The passwords do not match!'
    : 'Please confirm your password!';

  if (usernameErr) {
    errors.username = usernameErr;
  }
  if (emailErr) {
    errors.email = emailErr;
  }
  if (passwordErr) {
    errors.password = passwordErr;
  }
  if (confirmPasswordErr) {
    errors.confirm_password = confirmPasswordErr;
  }

  if (
    values.username &&
    values.email &&
    values.password &&
    values.confirm_password &&
    !usernameErr &&
    !emailErr &&
    !passwordErr &&
    !confirmPasswordErr
  ) {
    const formData = new FormData();
    formData.append('username', values.username);
    formData.append('email', values.email);
    formData.append('password', values.password);
    axios
      .post(`${BACKEND_URL}api/register/`, formData)
      .then((response: AxiosResponse<string>) => {
        event('register', { method: 'Local' })
        messageApi.success(response.data, 2);
        setTimeout(() => {
          messageApi.loading('Redirecting you to the login page', 1);
        }, 700);
        setTimeout(() => {
          loadingState.value = false;
        }, 1700);
        setTimeout(() => {
          router.push('/login');
        }, 2100);
      })
      .catch((error: AxiosError<Record<string, string>>) => {
        const res = error.response;
        if (res) {
          messageApi.error('Validation Error');
          const newErr = res.data.detail;
          if (newErr.includes('email')) {
            errors.email = newErr;
          } else if (newErr.includes('assword')) {
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
const handleInputChangeEmail = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value.replace(/\s+/g, ''); // removes whitespaces
  values.email = value ? value : null;
  errors.email = null;
  errors.global = null;
};
const handleInputChangePassword = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value.replace(/\s+/g, ''); // removes whitespaces
  values.password = value ? value : null;
  errors.password = null;
  errors.global = null;
};
const handleInputChangePasswordConfirm = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value.replace(/\s+/g, ''); // removes whitespaces
  values.confirm_password = value ? value : null;
  errors.confirm_password = null;
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
        Sign Up
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
            Email
          </div>
          <div class="relative size-full">
            <a-input v-model:value="values.email" class="rounded-xl" size="large" placeholder="Email" type="email"
              allow-clear max-length="200" :status="errors.email || errors.global ? 'error' : ''"
              @keydown="handleEnterSubmit" @input="handleInputChangeEmail">
              <template #prefix>
                <Email class="size-5 stroke-black opacity-40" />
              </template>
            </a-input>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.email || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.email ?? errors.global }}
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

        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base text-black">
            Confirm Password
          </div>
          <div class="relative size-full">
            <a-input-password v-model:value="values.confirm_password" class="rounded-xl" size="large"
              placeholder="Confirm Password" type="password" allow-clear max-length="200"
              :status="errors.confirm_password || errors.global ? 'error' : ''" @keydown="handleEnterSubmit"
              @input="handleInputChangePasswordConfirm">
              <template #prefix>
                <Password class="size-5 stroke-black opacity-40" />
              </template>
            </a-input-password>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.confirm_password || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.confirm_password ?? errors.global }}
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
          Create Account
        </div>
      </button>

      <div class="text-sm text-gray-900 text-center mb-6 mt-1">
        Already have an account? -----
        <RouterLink to="/login" draggable="false">
          <span class="text-blue-700 hover:underline underline-offset-2 cursor-pointer">
            Log in!
          </span>
        </RouterLink>
      </div>
      <div class="text-xs text-gray-600 text-justify text-pretty mb-3">
        By creating an account, you agree to Taskify's
        <RouterLink to="/tos" class="text-black hover:underline cursor-pointer" draggable="false">
          Terms of Service
        </RouterLink>
        and confirm that you have read our
        <RouterLink to="/privacy" class="text-black hover:underline cursor-pointer" draggable="false">
          Privacy Policy
        </RouterLink>
        .
      </div>
    </div>
  </div>
</template>

<style scoped></style>
