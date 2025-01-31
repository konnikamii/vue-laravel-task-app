<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { message } from 'ant-design-vue';
import { ContactErrors, ContactValues } from '../utils_other/types';
import UserNotOutlined from '../components/svgs/userNotOutlined.vue';
import Email from '../components/svgs/email.vue';
import BubblesBg from '../components/app/BubblesBg.vue';
import { rippleAnimation, validateEmail } from '../utils_other/helperFunctions';
import { BACKEND_URL, btnClassPrimaryDarkBlue } from "../utils_other/defaults";
import SideLines from '../components/app/SideLines.vue';
import { getInitialTheme } from '../hooks/themeUtils';
import { event } from 'vue-gtag'


const defaultValues: ContactValues = {
  name: null,
  email: null,
  subject: null,
  message: null,
};
const defaultErrors: ContactErrors = {
  name: null,
  email: null,
  subject: null,
  message: null,
  global: null,
};
const maxMessageLength = 1500;
const messageApi = message;


const theme = ref<'light' | 'dark'>(getInitialTheme());
const isLight = computed(() => theme.value === 'light');
const loadingState = ref(false);

const isDisabled = computed(() => {
  return (
    !!errors.name ||
    !!errors.email ||
    !!errors.subject ||
    !!errors.message ||
    !!errors.global ||
    loadingState.value
  );
});
const values = reactive<ContactValues>({ ...defaultValues });
const errors = reactive<ContactErrors>({ ...defaultErrors });

const handleEnterSubmit = (e: KeyboardEvent) => {
  if (e.key === 'Enter') {
    handleSubmit();
  }
};

const handleSubmit = () => {
  loadingState.value = true;
  const nameErr = values.name ? null : 'Name is required.';
  const emailErr = validateEmail(values.email);
  const subjectErr = values.subject ? null : 'Subject is required.';
  const messageErr = values.message ? null : 'Message is required.';
  if (nameErr) {
    errors.name = nameErr;
  }
  if (emailErr) {
    errors.email = emailErr;
  }
  if (subjectErr) {
    errors.subject = subjectErr;
  }
  if (messageErr) {
    errors.message = messageErr;
  }

  if (
    values.name &&
    values.email &&
    values.subject &&
    values.message &&
    !nameErr &&
    !emailErr &&
    !subjectErr &&
    !messageErr
  ) {
    const formData = new FormData();
    formData.append('name', values.name);
    formData.append('email', values.email);
    formData.append('subject', values.subject);
    formData.append('message', values.message);
    axios
      .post(`${BACKEND_URL}api/contact/`, formData)
      .then((response: AxiosResponse<string>) => {
        event('contact')
        messageApi.success(response.data, 2);
        Object.assign(values, defaultValues);
        Object.assign(errors, defaultErrors);
        setTimeout(() => {
          loadingState.value = false;
        }, 1000);
      })
      .catch((error: AxiosError<Record<string, string>>) => {
        const res = error.response;
        if (res) {
          messageApi.error('Validation Error');
          const newErr = res.data.detail;
          if (newErr.includes('email')) {
            errors.email = newErr;
          } else if (newErr.includes('name')) {
            errors.name = newErr;
          } else if (newErr.includes('subject')) {
            errors.subject = newErr;
          } else if (newErr.includes('message')) {
            errors.message = newErr;
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

const handleInputChangeName = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value
  values.name = value ? value : null;
  errors.name = null;
  errors.global = null;
};
const handleInputChangeEmail = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value.replace(/\s+/g, ''); // removes whitespaces
  values.email = value ? value : null;
  errors.email = null;
  errors.global = null;
};
const handleInputChangeSubject = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value
  values.subject = value ? value : null;
  errors.subject = null;
  errors.global = null;
};
const handleInputChangeMessage = (e: Event) => {
  const target = e.target as HTMLInputElement;
  const value = target.value
  values.message = value ? value : null;
  errors.message = null;
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
        Contact Us
      </div>
      <div class="flex flex-col gap-3 mb-6">
        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base text-black" style="letter-spacing: 0.01em;">
            Name
          </div>
          <div class="relative size-full">
            <a-input v-model:value="values.name" class="rounded-xl" size="large" placeholder="Name" id="fname"
              name="fname" allow-clear max-length="200" :status="errors.name || errors.global ? 'error' : ''"
              @keydown="handleEnterSubmit" @input="handleInputChangeName">
              <template #prefix>
                <UserNotOutlined class="size-4 stroke-black opacity-40" />
              </template>
            </a-input>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.name || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.name ?? errors.global }}
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
            Subject
          </div>
          <div class="relative size-full">
            <a-input v-model:value="values.subject" class="rounded-xl" size="large" placeholder="Subject" type="text"
              allow-clear max-length="255" :status="errors.subject || errors.global ? 'error' : ''"
              @keydown="handleEnterSubmit" @input="handleInputChangeSubject">
            </a-input>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.subject || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.subject ?? errors.global }}
          </div>
        </div>

        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base text-black">
            Message
          </div>
          <div class="relative size-full">
            <a-textarea v-model:value="values.message" class="rounded-xl min-h-32 max-h-[600px]" size="large"
              placeholder="Message" type="text" allow-clear :maxlength="maxMessageLength"
              :status="errors.message || errors.global ? 'error' : ''" @keydown="handleEnterSubmit"
              @input="handleInputChangeMessage">
            </a-textarea>

            <p :class="['absolute top-full right-0 translate-y-[2px] text-sm text-end',
              (values.message?.length ?? 0) >= maxMessageLength ? 'text-red-600' : 'text-gray-600'
            ]">
              {{ values.message?.length ?? 0 }}/{{ maxMessageLength }}
            </p>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.message || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.message ?? errors.global }}
          </div>
        </div>
      </div>
      <br>
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
          Send
        </div>
      </button>
      <div class="mt-6"></div>
    </div>
  </div>
</template>

<style scoped></style>
