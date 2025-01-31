<script setup lang="ts">
import { useQuery, useQueryClient } from '@tanstack/vue-query';
import { ref, watch, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { taskIdQueryOptions } from '../../../utils_fetch/fetch';
import { TaskUpdateErrors, TaskUpdateValues } from '../../../utils_other/types';
import { message } from 'ant-design-vue';
import axios, { AxiosError, AxiosResponse } from 'axios';
import { BACKEND_URL,   btnClassPrimary, btnClassPrimaryDarkBlue } from '../../../utils_other/defaults';
import { getInitialTheme } from '../../../hooks/themeUtils';
import { rippleAnimation } from '../../../utils_other/helperFunctions';
import dayjs from 'dayjs';
import customParseFormat from 'dayjs/plugin/customParseFormat';

dayjs.extend(customParseFormat);

const maxDescriptionLength = 1500;
const defaultUpdateTaskValues: TaskUpdateValues = {
  title: '',
  description: '',
  completed: false,
  due_date: null,
};

const defaultUpdateTaskErrors: TaskUpdateErrors = {
  title: null,
  description: null,
  completed: null,
  due_date: null,
  global: null,
};

const router = useRouter();
const route = useRoute();
const queryClient = useQueryClient();
const messageApi = message;
const theme = ref<'light' | 'dark'>(getInitialTheme());
const isLight = computed(() => theme.value === 'light');

const params = route.params as { taskid: string };
const taskid = ref(params.taskid).value;


const values = ref<TaskUpdateValues>({ ...defaultUpdateTaskValues });
const errors = ref<TaskUpdateErrors>({ ...defaultUpdateTaskErrors });

const { data: taskQData, isFetching: taskQFetching, isPending: taskQIsPending, isError: taskQIsError } = useQuery(taskIdQueryOptions({ id: +taskid }));
watch(taskQData, (newData) => {
  if (newData && !axios.isAxiosError(newData)) {
    values.value = {
      title: newData.title,
      description: newData.description,
      completed: newData.completed,
      due_date: newData.due_date ? dayjs(newData.due_date) : null,
    };
  } else {
    values.value = { ...defaultUpdateTaskValues };
  }
}, { immediate: true });


const loadingState = ref(false);
 
const primaryBtnClassName = computed(() => (isLight.value ? btnClassPrimary : btnClassPrimaryDarkBlue));


const handleUpdateTask = (e: MouseEvent) => {
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
      .put(`${BACKEND_URL}api/task/${taskid}`, { ...values.value }, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('access_token')}`,
        },
        withCredentials: true
      })
      .then((response: AxiosResponse<string>) => {
        messageApi.success(response.data);
        ['task', 'tasks', 'taskid', 'users'].forEach((key) => {
          queryClient.invalidateQueries({ queryKey: [key] });
        });
        loadingState.value = false;
      })
      .catch((error: AxiosError<Record<string, string>>) => {
        if (error.response) {
          if (error.response.status === 401) {
            messageApi.warning('Your session has expired. Please log in again.', 3);
            localStorage.removeItem('access_token');
            window.location.href = '/login';
          }
          messageApi.error('There was a validation error');
          console.log(error.response.data);
        } else {
          messageApi.error('An unexpected error occurred.');
          newErr = 'An unexpected error occurred.';
        }
        errors.value.global = newErr;
        setTimeout(() => {
          loadingState.value = false;
        }, 1000);
      });
  }
};
const handleDeleteTask = () => {
  loadingState.value = true;
  axios
    .delete(`${BACKEND_URL}api/task/${taskid}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('access_token')}`,
      },
      withCredentials: true
    })
    .then((response: AxiosResponse<string>) => {
      messageApi.success(response.data);
      ['task', 'tasks', 'users'].forEach((key) => {
        queryClient.invalidateQueries({ queryKey: [key] });
      });
      loadingState.value = false;
      router.push('/app/tasks');
    })
    .catch((error: AxiosError<{ detail: string }>) => {
      if (error.response) {
        if (error.response.status === 401) {
          messageApi.warning('Your session has expired. Please log in again.', 3);
          localStorage.removeItem('access_token');
          window.location.href = '/login';
        }
        messageApi.error('There was a validation error');
        console.log(error.response.data.detail);
      } else {
        messageApi.error('An unexpected error occurred.');
      }
      setTimeout(() => {
        loadingState.value = false;
      }, 1000);
    });
};
const clearErrors = (field: keyof TaskUpdateErrors) => {
  errors.value[field] = null;
  errors.value.global = null;
};
const disabledDate = (current: any) => {
  return current && current < dayjs('1976-01-01', 'YYYY-MM-DD');
};
</script>

<template>
  <div>
    <div :class="[
      'loader fixed top-0 right-0 m-[2px] loader-dark dark:loader-light lg:size-6 size-4',
      taskQFetching || loadingState ? 'opacity-100' : 'opacity-0'
    ]"></div>
    <div class="lg:min-w-[60%] lg:p-12 p-6 ">
      <div v-if="taskQIsPending">
        <div class="flex justify-center items-center h-[80vh]">
          <a-spin size="large" />
        </div>
      </div>
      <div v-else-if="taskQIsError">
        <div class="flex justify-center items-center h-[80vh]">
          <div class="text-2xl font-bold">An error occurred while fetching the task.</div>
        </div>
      </div>
      <div v-else-if="taskQData && !axios.isAxiosError(taskQData)"
        class=" task-details p-4 border rounded-lg shadow-md bg-slate-50 border-gray-300 dark:bg-slate-800 dark:border-gray-700 ">
        <div class="flex flex-col gap-3 mb-6">
          <div class="relative size-full">
            <div class="font-semibold mb-2 2xl:text-lg text-base">Title</div>
            <div class="relative size-full">
              <a-input v-model:value="values.title" class="rounded-md" size="large" placeholder="Title" allow-clear
                :maxlength="200"
                :status="errors.title || errors.global ? 'error' : values.title !== taskQData.title ? 'warning' : ''"
                @change="clearErrors('title')" />
            </div>
            <div
              :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.title || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
              {{ errors.title ?? errors.global }}
            </div>
          </div>

          <div class="relative size-full">
            <div class="font-semibold mb-2 2xl:text-lg text-base">Description</div>
            <div class="relative size-full">
              <a-textarea v-model:value="values.description" class="rounded-md" size="large" placeholder="Description"
                allow-clear :maxlength="maxDescriptionLength"
                :status="errors.description || errors.global ? 'error' : values.description !== taskQData.description ? 'warning' : ''"
                @change="clearErrors('description')" />
              <p :class="['absolute top-full right-0 translate-y-[2px] text-sm text-end',
                (values.description?.length ?? 0) >= maxDescriptionLength ? 'text-red-600' : 'text-gray-500'
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
            <div class="font-semibold mb-2 2xl:text-lg text-base">Completed</div>
            <div class="relative size-full">
              <a-switch v-model:checked="values.completed" class="w-fit" @change="clearErrors('completed')" />
            </div>
            <div
              :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.completed || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
              {{ errors.completed ?? errors.global }}
            </div>
          </div>

          <div class="relative size-full">
            <div class="font-semibold mb-2 2xl:text-lg text-base">Due Date</div>
            <div class="relative size-full">
              <a-date-picker v-model:value="values.due_date" class="" placeholder="Select Date" size="middle"
                input-read-only
                :status="values.due_date?.format('YYYY-MM-DD') !== dayjs(taskQData.due_date).format('YYYY-MM-DD') ? 'warning' : ''"
                :disabled-date="disabledDate" @change="clearErrors('due_date')" />
            </div>
            <div
              :class="['pt-[2px] text-sm text-red-600 transition-all duration-300', errors.due_date || errors.global ? 'max-h-40' : 'max-h-0', 'overflow-hidden']">
              {{ errors.due_date ?? errors.global }}
            </div>
          </div>
        </div>

        <p class="mb-2"><strong>Created At:</strong> {{ new Date(taskQData.created_at).toLocaleString() }}</p>
        <p class="mb-2"><strong>Last Updated At:</strong> {{ new Date(taskQData.updated_at).toLocaleString() }}</p>
        <div class="flex justify-end gap-4 mt-4">
          <button class="rounded-md px-4 py-2 bg-gray-300 text-white border-gray-300 hover:bg-gray-400 hover:border-gray-400 dark:bg-gray-700 dark:text-white dark:border-gray-700 dark:hover:bg-gray-600 dark:hover:border-gray-600 transition-colors duration-300  cursor-pointer relative" 
            :disabled="values.title === taskQData.title && values.description === taskQData.description && values.completed === taskQData.completed && values.due_date === dayjs(taskQData.due_date)"
            :style="{ overflow: 'hidden' }" @click="handleUpdateTask">
            Update Task
          </button>
          <a-popconfirm title="Delete Task" description="Are you sure you want to delete the selected task?"
            ok-text="Yes" cancel-text="No" @confirm="handleDeleteTask">
            <button
              class="rounded-md px-4 py-2 bg-red-500/95 text-white border-red-500/95 hover:bg-red-500 hover:border-red-500 dark:bg-red-700 dark:text-white dark:border-red-700 dark:hover:bg-red-600 dark:hover:border-red-600 transition-colors duration-300  cursor-pointer">
              Delete Task
            </button>
          </a-popconfirm>
        </div>
      </div>
      <div v-else class="h-screen flex-col justify-center">
        <a-empty description="No task found with this id." />
        <div class="flex justify-center mt-2">
          <router-link :to="{ name: '/app/tasks/' }" :class="primaryBtnClassName">
            Back to Tasks
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>