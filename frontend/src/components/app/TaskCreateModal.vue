<script setup lang="ts">
import { ref, computed } from 'vue';
import { useQueryClient } from '@tanstack/vue-query';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { getInitialTheme, useDims } from '../../hooks/themeUtils';
import { BACKEND_URL, btnClassBase, btnClassBaseDark, btnClassPrimary, btnClassPrimaryDarkBlue } from '../../utils_other/defaults';
import { rippleAnimation } from '../../utils_other/helperFunctions';
import UserNotOutlined from '../svgs/userNotOutlined.vue';
import { TaskCreateErrors, TaskCreateValues } from '../../utils_other/types';
import dayjs from 'dayjs';

const props = defineProps<{
  tasksModal: boolean;
}>();
const emit = defineEmits<{
  (e: 'close'): void;
}>();

const maxDescriptionLength = 1500;
const defaultTaskCreateValues: TaskCreateValues = {
  title: null,
  description: null,
  completed: false,
  due_date: null,
};

const defaultTaskCreateErrors: TaskCreateErrors = {
  title: null,
  description: null,
  completed: null,
  due_date: null,
  global: null,
};

const values = ref<TaskCreateValues>({ ...defaultTaskCreateValues });
const errors = ref<TaskCreateErrors>({ ...defaultTaskCreateErrors });
const loadingState = ref(false);

const queryClient = useQueryClient();
const dims = useDims();
const isLight = ref(getInitialTheme() === 'light');

const baseBtnClassName = computed(() => (isLight.value ? btnClassBase : btnClassBaseDark));
const primaryBtnClassName = computed(() => (isLight.value ? btnClassPrimary : btnClassPrimaryDarkBlue));

const handleCancel = () => {
  errors.value = { ...defaultTaskCreateErrors };
  emit('close');
};

const handleClose = (e: MouseEvent) => {
  rippleAnimation(e, isLight.value);
  errors.value = { ...defaultTaskCreateErrors };
  emit('close');
};

const handleCreate = (e: MouseEvent) => {
  rippleAnimation(e, isLight.value);
  loadingState.value = true;
  let newErr: string | null = null;
  if (!values.value.title) {
    errors.value.title = 'Title is required';
    newErr = 'err';
  }
  if (!values.value.description) {
    errors.value.description = 'Description is required';
    newErr = 'err';
  }
  if (newErr) {
    setTimeout(() => {
      loadingState.value = false;
    }, 500);
    errors.value.global = newErr;
  } else {
    axios
      .post(`${BACKEND_URL}api/task/`, values.value, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('access_token')}`,
        },
        withCredentials: true
      })
      .then((response) => {
        message.success(response.data);
        ['task', 'tasks', 'users'].forEach((key) => {
          queryClient.invalidateQueries({ queryKey: [key] });
        });
        values.value = { ...defaultTaskCreateValues };
        loadingState.value = false;
        emit('close');
      })
      .catch((error) => {
        if (error.response) {
          if (error.response.status === 401) {
            window.location.href = '/login';
          }
          message.error('There was a validation error');
          console.log(error.response.data);
        } else {
          message.error('An unexpected error occurred.');
          newErr = 'An unexpected error occurred.';
        }
        errors.value.global = newErr;
        setTimeout(() => {
          loadingState.value = false;
        }, 1000);
      });
  }
};

const clearErrors = (field: keyof TaskCreateErrors) => {
  errors.value[field] = null;
  errors.value.global = null;
};

const disabledDate = (current: any) => {
  return current && current < dayjs('1976-01-01', 'YYYY-MM-DD');
};
</script>

<template>
  <a-modal :open="props.tasksModal" title="Create new task" @cancel="handleCancel" :ok-button-props="{
    class: primaryBtnClassName + ' min-w-[135px] cursor-pointer overflow-hidden',
    style: { overflow: 'hidden' },
    onclick: handleCreate
  }" :cancel-button-props="{
      class: baseBtnClassName + ' min-w-[115px] cursor-pointer overflow-hidden',
      style: { overflow: 'hidden' },
      onclick: handleClose
    }">
    <div :class="[
      'loader fixed top-1/2 right-1/2 m-[2px]',
      isLight ? 'loader-dark' : 'loader-light',
      dims.plus1024 ? 'size-6' : 'size-4',
      loadingState ? 'opacity-100' : 'opacity-0'
    ]"></div>
    <div class="text-black dark:text-white">
      <div class="flex flex-col gap-3 mb-6">
        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base">
            Title
          </div>
          <div class="relative size-full">
            <a-input v-model:value="values.title" class="rounded-md" size="large" placeholder="Title" allow-clear
              :maxlength="200" :status="errors.title || errors.global ? 'error' : ''" @change="clearErrors('title')">
              <template #prefix>
                <UserNotOutlined class="size-4 stroke-black dark:stroke-white opacity-40" />
              </template>
            </a-input>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.title || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.title ?? errors.global }}
          </div>
        </div>

        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base">
            Description
          </div>
          <div class="relative size-full">
            <a-textarea v-model:value="values.description" class="rounded-md min-h-32 max-h-[600px]" size="large"
              placeholder="Description" allow-clear :maxlength="maxDescriptionLength"
              :status="errors.description || errors.global ? 'error' : ''" @change="clearErrors('description')" />
            <p :class="['absolute top-full right-0 translate-y-[2px] text-sm text-end',
              (values.description?.length ?? 0) >= maxDescriptionLength ? 'text-red-600' : 'text-gray-600'
            ]">
              {{ values.description?.length ?? 0 }}/{{ maxDescriptionLength }}
            </p>
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.description || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.description ?? errors.global }}
          </div>
        </div>

        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base">
            Completed
          </div>
          <div class="relative size-full">
            <a-switch v-model:checked="values.completed" class="w-fit" @change="clearErrors('completed')" />
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.completed || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.completed ?? errors.global }}
          </div>
        </div>

        <div class="relative size-full">
          <div class="font-semibold mb-2 2xl:text-lg text-base">
            Due Date
          </div>
          <div class="relative size-full">
            <a-date-picker v-model:value="values.due_date" class="" placeholder="Select Date" size="middle"
              input-read-only :disabled-date="disabledDate" @change="clearErrors('due_date')" />
          </div>
          <div
            :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.due_date || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
            {{ errors.due_date ?? errors.global }}
          </div>
        </div>
      </div>
    </div>
  </a-modal>
</template>


<style scoped>
/* Add any additional styles here if needed */
</style>