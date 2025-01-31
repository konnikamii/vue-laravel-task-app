<script setup lang="ts">
import { useQuery, useQueryClient } from '@tanstack/vue-query';
import { useRouter } from 'vue-router';
import { getInitialTheme, useDims } from '../../../hooks/themeUtils';
import { computed, ref } from 'vue';
import { tasksQueryOptions } from '../../../utils_fetch/fetch';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { BACKEND_URL } from '../../../utils_other/defaults';
import TaskCreateModal from '../../../components/app/TaskCreateModal.vue';
// get the route params
const router = useRouter();
const queryClient = useQueryClient();
const isLight = ref(getInitialTheme() === 'light');
const dims = useDims();

const messageApi = message;
const sortBy = ref<"title" | "due_date" | "completed" | "created_at" | "updated_at">('created_at');
const sortType = ref<'asc' | 'desc'>('asc');
const tasksModal = ref(false);
const currentPage = ref(1);
const currentPageSize = ref(12);

const queryOptions = computed(() => tasksQueryOptions({
  page: currentPage.value,
  page_size: currentPageSize.value,
  sort_by: sortBy.value,
  sort_type: sortType.value,
}));
const loadingState = ref(false);
const { data: tasksQData, isFetching: tasksQFetching, isPending: tasksQIsPending, isError: tasksQIsError } = useQuery(queryOptions);

const navigateToTask = (taskId: number) => {
  router.push({ name: '/app/tasks/[taskid]', params: { taskid: taskId } });
};
const deleteTask = (taskId: number) => {
  loadingState.value = true;
  axios
    .delete(`${BACKEND_URL}api/task/${taskId}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('access_token')}`,
      },
      withCredentials: true,
    })
    .then((response) => {
      messageApi.success(response.data);
      ['task', 'tasks', 'users'].forEach((key) => {
        queryClient.invalidateQueries({ queryKey: [key] });
      });
      loadingState.value = false;
    })
    .catch((error) => {
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
const setCurrentPage = (value: number) => {
  currentPage.value = value;
};
const resetPage = (value: number) => {
  currentPage.value = 1;
  currentPageSize.value = value;
};
</script>

<template>
  <div>
    <div :class="[
      'loader fixed top-0 right-0 m-[2px]',
      isLight ? 'loader-dark' : 'loader-light',
      dims.plus1024 ? 'size-6' : 'size-4',
      tasksQFetching || loadingState ? 'opacity-100' : 'opacity-0'
    ]"></div>
    <div class="lg:min-w-[60%] lg:p-12 p-6 text-black dark:text-white">
      <div v-if="tasksQIsPending">
        <div class="flex justify-center items-center h-[80vh]">
          <a-spin size="large" />
        </div>
      </div>
      <div v-else-if="tasksQIsError">
        <div class="flex justify-center items-center h-[80vh]">
          <div class="text-2xl font-bold">An error occurred while fetching tasks.</div>
        </div>
      </div>
      <div v-else-if="tasksQData && !axios.isAxiosError(tasksQData)">
        <div class="flex w-full md:flex-row flex-col justify-between items-center mb-4">
          <div class="text-2xl font-bold">
            Tasks
            <span class="text-lg font-medium italic">
              (Total <span>{{ tasksQData.total }}</span>)
            </span>
          </div>
          <div class="flex md:flex-row flex-col gap-2 items-center">
            <div>Sort by: </div>
            <a-select title="Sort By" v-model:value="sortBy" class="min-w-[120px]"
              :size="dims.plus550 ? 'large' : 'middle'">
              <a-select-option value="created_at">Created At</a-select-option>
              <a-select-option value="updated_at">Updated At</a-select-option>
              <a-select-option value="title">Title</a-select-option>
              <a-select-option value="completed">Completed</a-select-option>
              <a-select-option value="due_date">Due Date</a-select-option>
            </a-select>
            <a-select title="Sort Type" v-model:value="sortType" class="min-w-[120px]"
              :size="dims.plus550 ? 'large' : 'middle'">
              <a-select-option value="asc">Ascending</a-select-option>
              <a-select-option value="desc">Descending</a-select-option>
            </a-select>
            <button :class="[
              'rounded-md px-4 py-2 transition-colors duration-300 cursor-pointer bg-blue-400 border-blue-500 hover:bg-blue-500 hover:border-blue-600 dark:bg-blue-700 dark:border-gray-700 dark:hover:bg-blue-800 dark:hover:border-gray-600'
            ]" @click="tasksModal = !tasksModal">
              Add Task
            </button>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="task in tasksQData.data" :key="task.id"
            class="task-card p-4 border rounded-lg shadow-md transition-all duration-300 cursor-pointer bg-white hover:bg-blue-100/70 border-gray-300 dark:bg-gray-700 dark:hover:bg-gray-800 dark:border-gray-600"
            @click="navigateToTask(task.id)">
            <div class="flex justify-between items-center mb-3">
              <div class="text-xl font-semibold mb-2">{{ task.title }}</div>
              <a-popconfirm title="Delete Task" description="Are you sure you want to delete the selected task?"
                okText="Yes" cancelText="No" @confirm="deleteTask(task.id)" @popup-click.stop>
                <button class="cursor-pointer" @click.stop>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    class="w-6 h-6 stroke-red-600 hover:stroke-red-800">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </a-popconfirm>
            </div>
            <div class="text-gray-700 dark:text-gray-300 mb-6">{{ task.description }}</div>
            <div class="text-gray-500 dark:text-gray-400 my-1">
              Due Date: {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'Not set' }}
            </div>
            <div :class="['text-sm font-medium mb-4', task.completed ? 'text-green-600' : 'text-red-600']">
              {{ task.completed ? 'Completed' : 'Not Completed' }}
            </div>
            <div class="text-gray-500 dark:text-gray-400">
              Created At: {{ new Date(task.created_at).toLocaleDateString() }}
            </div>
            <div class="text-gray-500 dark:text-gray-400">
              Updated At: {{ new Date(task.updated_at).toLocaleDateString() }}
            </div>
          </div>
        </div>
        <div class="flex justify-center items-center my-4 mt-16">
          <a-pagination v-model:current="currentPage" v-model:page-size="currentPageSize"
            v-model:total="tasksQData.total" :default-current="currentPage" :default-page-size="currentPageSize"
            :show-size-changer="false" :size="dims.plus550 ? 'default' : 'small'" :show-less-items="!dims.plus375"
            @change="setCurrentPage" />
          <a-select title="Page Size" v-model:value="currentPageSize" :size="dims.plus550 ? 'middle' : 'small'"
            @change="resetPage">
            <a-select-option :value="12">12</a-select-option>
            <a-select-option :value="36">36</a-select-option>
            <a-select-option :value="96">96</a-select-option>
          </a-select>
        </div>
        <TaskCreateModal :tasksModal="tasksModal" @close="tasksModal = false" />
      </div>
      <div v-else class="h-screen flex-col justify-center">
        <a-empty description="No tasks found. Add some!" />
      </div>
    </div>
  </div>
</template>
