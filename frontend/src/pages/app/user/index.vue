<script setup lang="ts"> 
import { ref  } from 'vue';
import { useQuery } from '@tanstack/vue-query';
import { message, Tooltip } from 'ant-design-vue';
import { userQueryOptions } from '../../../utils_fetch/fetch';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { BACKEND_URL, btnClassPrimaryDarkBlue } from '../../../utils_other/defaults';
import { rippleAnimation, validatePassword } from '../../../utils_other/helperFunctions';
import { getInitialTheme, useDims } from '../../../hooks/themeUtils';
import UserNotOutlined from '../../../components/svgs/userNotOutlined.vue';
import Info from '../../../components/svgs/info.vue';
import Email from '../../../components/svgs/email.vue';
import Password from '../../../components/svgs/password.vue';

interface currentUserProp {
  old_password: string | null;
  new_password: string | null;
  confirm_password: string | null;
  global: string | null;
}

const defaultUser: currentUserProp = {
  old_password: null,
  new_password: null,
  confirm_password: null,
  global: null,
};

const messageApi = message;
const dims = useDims();
const isLight = ref(getInitialTheme() === 'light');

const currentUser = ref<currentUserProp>({ ...defaultUser });
const userErr = ref<currentUserProp>({ ...defaultUser });

const { data: userQData, isFetching: userQIsFetching, isPending: userQIsPending, isError: userQIsError } = useQuery(userQueryOptions());


const loadingState = ref(false);

const userIconsClassName = `size-4 stroke-black`
const infoSvgClassName = `size-4 stroke-black fill-black opacity-45 ` 

const handleUpdate = (e: MouseEvent) => {
  rippleAnimation(e, isLight.value);
  loadingState.value = true;
  const oldPass = currentUser.value.old_password;
  const newPass = currentUser.value.new_password;
  const confPass = currentUser.value.confirm_password;
  let newErrorOldPass = userErr.value.old_password;
  let newErrorNewPass = validatePassword(newPass);
  let newErrorConfPass = userErr.value.confirm_password;
  let newErrorGlobal = userErr.value.global;
  if (!oldPass) {
    newErrorOldPass = 'Old password is required';
  }
  if (!newPass) {
    newErrorNewPass = 'New password is required';
  }
  if (!confPass) {
    newErrorConfPass = 'Please confirm your password';
  }
  if (newPass !== confPass) {
    newErrorConfPass = 'Passwords do not match';
  }
  if (!newErrorOldPass && !newErrorNewPass && !newErrorConfPass && oldPass && newPass) {
    const formData = new FormData();
    formData.append('old_password', oldPass);
    formData.append('new_password', newPass);
    axios
      .post(`${BACKEND_URL}api/change-password/`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
          'Authorization': `Bearer ${localStorage.getItem('access_token')}`,
        },
        withCredentials: true,
      })
      .then((response: AxiosResponse<{ message: string }>) => {
        messageApi.success(response.data.message);
        currentUser.value = { ...defaultUser };
        loadingState.value = false;
      })
      .catch((error: AxiosError<Record<string, string>>) => {
        if (error.response) {
          if (error.response.status === 401) {
            messageApi.warning('Your session has expired. Please log in again.', 3);
            localStorage.removeItem('access_token');
            window.location.href = '/login';
          }
          messageApi.error('Validation Error');
          const newErr = error.response.data.detail;
          console.log(newErr)
          if (newErr.includes('Old')) {
            newErrorOldPass = newErr;
          } else if (newErr.includes('New')) {
            newErrorNewPass = newErr;
          } else {
            newErrorGlobal = newErr;
          }
        } else {
          messageApi.error('An unexpected error occurred.');
          newErrorNewPass = 'An unexpected error occurred.';
        }
        userErr.value.old_password = newErrorOldPass
        userErr.value.new_password = newErrorNewPass
        userErr.value.confirm_password = newErrorConfPass
        userErr.value.global = newErrorGlobal
        setTimeout(() => {
          loadingState.value = false;
        }, 1000);
      });
  } else {
    userErr.value.old_password = newErrorOldPass
    userErr.value.new_password = newErrorNewPass
    userErr.value.confirm_password = newErrorConfPass
    userErr.value.global = newErrorGlobal
    loadingState.value = false;
  }
};
</script>

<template>
  <div>
    <div :class="[
      'loader fixed top-0 right-0 m-[2px] loader-dark dark:loader-light lg:size-6 size-4',
      userQIsFetching || loadingState ? 'opacity-100' : 'opacity-0'
    ]"></div>
    <div class=" lg:min-w-[60%] lg:p-12 p-6 text-black dark:text-white ">
      <div v-if="userQIsPending">
        <div class="flex justify-center items-center h-[80vh]">
          <a-spin size="large" />
        </div>
      </div>
      <div v-else-if="userQIsError">
        <div class="flex justify-center items-center h-[80vh]">
          <div class="text-2xl font-bold">An error occurred while fetching user data.</div>
        </div>
      </div>
      <div v-else-if="userQData && !axios.isAxiosError(userQData)"
        :class="['flex flex-col gap-10 p-2', dims.plus1440 ? 'w-1/3' : dims.plus1024 ? 'w-1/2' : 'w-full']">
        <div class="w-full">
          <div :class="['font-semibold mb-3', dims.plus1440 ? 'text-lg' : 'text-base']">Username</div>
          <a-input :placeholder="userQData.username" disabled>
            <template #prefix>
              <UserNotOutlined
                :class="userIconsClassName + ' size-[14px] opacity-25 dark:stroke-white dark:opacity-60'" />
            </template>
            <template #suffix>
              <a-tooltip>
                <template #title>Currently it is not possible to change your username. Contact us if you want this
                  feature unlocked.</template>
                <Tooltip :class="infoSvgClassName" />
              </a-tooltip>
            </template>
          </a-input>
        </div>
        <div class="w-full">
          <div class="flex gap-2 items-center mb-3">
            <div class=" font-semibold 2xl:text-lg text-base ">Email</div>
            <a-tooltip title="Email not verified! Please verify your email.">
              <div>
                <Info class="size-4 stroke-orange-600 fill-orange-600" />
              </div>
            </a-tooltip>
          </div>
          <a-input :placeholder="userQData.email" disabled>
            <template #prefix>
              <Email :class="userIconsClassName + ' opacity-25 dark:stroke-white dark:opacity-60'" />
            </template>
            <template #suffix>
              <a-tooltip>
                <template #title>Currently it is not possible to change your email address. Contact us if you want this
                  feature unlocked.</template>
                <Tooltip :class="infoSvgClassName" />
              </a-tooltip>
            </template>
          </a-input>
        </div>
        <div class="w-full flex flex-col">
          <div class="flex gap-2 items-center mb-3">
            <div :class="['font-semibold', dims.plus1440 ? 'text-lg' : 'text-base']">Password</div>
          </div>
          <div class="relative size-full mb-6">
            <a-input-password v-model:value="currentUser.old_password" placeholder="Old Password" allow-clear
              :maxlength="200" :status="userErr.old_password ? 'error' : currentUser.old_password ? 'warning' : ''"
              @change="() => {
                userErr.old_password = null;
                userErr.global = null;
              }">
              <template #prefix>
                <Password :class="userIconsClassName" />
              </template>
            </a-input-password>
            <div
              :class="['absolute bot-0 translate-y-[2px] text-sm text-red-500 transition-all duration-300', userErr.old_password ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
              {{ userErr.old_password }}
            </div>
          </div>
          <div class="relative size-full mb-6">
            <a-input-password v-model:value="currentUser.new_password" placeholder="New Password" allow-clear
              :maxlength="200" :status="userErr.new_password ? 'error' : currentUser.new_password ? 'warning' : ''"
              @change="() => {
                userErr.new_password = null;
                userErr.global = null;
              }">
              <template #prefix>
                <Password :class="userIconsClassName" />
              </template>
            </a-input-password>
            <div
              :class="['absolute bot-0 translate-y-[2px] text-sm text-red-500 transition-all duration-300', userErr.new_password ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
              {{ userErr.new_password }}
            </div>
          </div>
          <div class="relative size-full mb-6">
            <a-input-password v-model:value="currentUser.confirm_password" placeholder="Confirm Password" allow-clear
              :maxlength="200"
              :status="userErr.confirm_password ? 'error' : currentUser.confirm_password ? 'warning' : ''" @change="() => {
                userErr.confirm_password = null;
                userErr.global = null;
              }">
              <template #prefix>
                <Password :class="userIconsClassName" />
              </template>
            </a-input-password>
            <div
              :class="['absolute bot-0 translate-y-[2px] text-sm text-red-500 transition-all duration-300', userErr.confirm_password ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
              {{ userErr.confirm_password }}
            </div>
          </div>
          <button :class="btnClassPrimaryDarkBlue + ' w-[200px] mb-4 self-end cursor-pointer'" :disabled="loadingState"
            :style="{ overflow: 'hidden' }" @click="handleUpdate">
            Update
          </button>
        </div>
      </div>
      <div v-else>
        <div class="flex justify-center items-center h-[80vh]">
          <div class="text-2xl font-bold">An error occurred while fetching user data.</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add any additional styles here if needed */
</style>