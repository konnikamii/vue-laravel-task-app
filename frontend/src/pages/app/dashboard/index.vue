<script setup lang="ts">
import { computed } from 'vue';
import { useQuery } from '@tanstack/vue-query';
import { tasksQueryOptions, userQueryOptions, usersQueryOptions } from '../../../utils_fetch/fetch';
import axios from 'axios'; 
import { useRouter } from 'vue-router';
import dayjs from "dayjs";
import CheckBox from '../../../components/svgs/checkBox.vue'; 

const router = useRouter(); 

const today = new Date();
const { data: userQData, isFetching: userQFetching, isPending: userQIsPending, isError: userQIsError } = useQuery(userQueryOptions());
const { data: usersQData, isFetching: usersQFetching, isPending: usersQIsPending, isError: usersQIsError } = useQuery(usersQueryOptions({ type: 'user_tasks' }));
const { data: upcomingTasksQData, isFetching: upcomingTasksQFetching, isPending: upcomingTasksQIsPending, isError: upcomingTasksQIsError } = useQuery(tasksQueryOptions({ page: 1, page_size: 25, sort_by: "due_date", sort_type: "asc", }));
const { data: completedTasksQData, isFetching: completedTasksQFetching, isPending: completedTasksQIsPending, isError: completedTasksQIsError } = useQuery(tasksQueryOptions({ page: 1, page_size: 3, sort_by: "completed", sort_type: "desc", }));
const { data: recentTasksQData, isFetching: recentTasksQFetching, isPending: recentTasksQIsPending, isError: recentTasksQIsError } = useQuery(tasksQueryOptions({ page: 1, page_size: 3, sort_by: "created_at", sort_type: "desc", }));

const sortedUsers = computed(() => {
  return usersQData.value && !axios.isAxiosError(usersQData.value) ? [...usersQData.value].sort((a, b) => b.tasks.length - a.tasks.length) : [];
});
const filteredCompletedTasksQData = computed(() => {
  return completedTasksQData.value && !axios.isAxiosError(completedTasksQData.value) ? completedTasksQData.value.data.filter((task) => task.completed) : [];
});
const filteredUpcomingTasksQData = computed(() => {
  return upcomingTasksQData.value && !axios.isAxiosError(upcomingTasksQData.value) ? upcomingTasksQData.value.data.filter((task) => task.due_date && new Date(task.due_date) >= today && !task.completed).sort((a, b) => {
    const dateA = a.due_date ? new Date(a.due_date).getTime() : 0;
    const dateB = b.due_date ? new Date(b.due_date).getTime() : 0;
    return dateA - dateB;
  }).slice(0, 3) : [];
});

const isFetching = computed(() => userQFetching || usersQFetching || upcomingTasksQFetching || completedTasksQFetching || recentTasksQFetching);

const navigateToTask = (taskId: number) => {
  router.push({ name: '/app/tasks/[taskid]', params: { taskid: taskId } });
};
const truncateDescription = (description: string) => {
  return description.length > 20 ? `${description.slice(0, 20)}...` : description;
};
</script>

<template>
  <div>
    <div :class="[
      'loader fixed top-0 right-0 m-[2px] transition-all duration-300 loader-dark dark:loader-light lg:size-6 size-4',
      isFetching.value ? 'opacity-100' : 'opacity-0'
    ]"></div>
    <div class="lg:min-w-[60%] lg:p-12 p-6 text-black dark:text-white">
      <div class="flex md:flex-row flex-col gap-10 w-full">
        <div class="flex flex-col md:w-8/12">

          <div class="flex w-full justify-between items-center mb-4">
            <h1 class="text-2xl font-bold mb-4">Upcoming Tasks</h1>
          </div>
          <div v-if="upcomingTasksQIsPending" class="py-10">
            <a-spin size="large" />
          </div>
          <div v-else-if="upcomingTasksQIsError" class="py-10">
            <div>Error fetching tasks</div>
          </div>
          <div v-else-if="upcomingTasksQData && !axios.isAxiosError(upcomingTasksQData) && filteredUpcomingTasksQData.length > 0"
            class="grid md:grid-cols-3 grid-cols-1 gap-2">
            <div v-for="task in filteredUpcomingTasksQData" :key="task.id" class="task-card p-4 border rounded-lg shadow-md transition-all duration-300 cursor-pointer bg-white hover:bg-blue-100/70 border-gray-300 dark:bg-gray-700 dark:hover:bg-gray-800 dark:border-gray-600" @click="navigateToTask(task.id)">
              <div class="text-xl font-semibold mb-2">{{ task.title }}</div>
              <p class="text-gray-700 dark:text-gray-300">{{ task.description }}</p>
              <p class="text-gray-500 dark:text-gray-400">
                Due Date: {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'Not set' }}
              </p>
              <p :class="['text-sm font-medium', task.completed ? 'text-green-600' : 'text-red-600']">
                {{ task.completed ? 'Completed' : 'Not Completed' }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                Created At: {{ new Date(task.created_at).toLocaleDateString() }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                Updated At: {{ new Date(task.updated_at).toLocaleDateString() }}
              </p>
            </div>
          </div>
          <div v-else class="py-10">
            No <span class="font-semibold">upcoming (not completed)</span> tasks found
          </div>

          <div class="flex w-full justify-between items-center my-4">
            <h1 class="text-2xl font-bold mb-4">Completed Tasks</h1>
          </div>
          <div v-if="completedTasksQIsPending" class="py-10">
            <a-spin size="large" />
          </div>
          <div v-else-if="completedTasksQIsError" class="py-10">
            <div>Error fetching tasks</div>
          </div>
          <div v-else-if="completedTasksQData && !axios.isAxiosError(completedTasksQData) && filteredCompletedTasksQData.length > 0"
            class="grid md:grid-cols-3 grid-cols-1 gap-2">
            <div v-for="task in filteredCompletedTasksQData" :key="task.id" class="task-card p-4 border rounded-lg shadow-md transition-all duration-300 cursor-pointer bg-white hover:bg-blue-100/70 border-gray-300 dark:bg-gray-700 dark:hover:bg-gray-800 dark:border-gray-600" @click="navigateToTask(task.id)">
              <div class="text-xl font-semibold mb-2">{{ task.title }}</div>
              <p class="text-gray-700 dark:text-gray-300">{{ task.description }}</p>
              <p class="text-gray-500 dark:text-gray-400">
                Due Date: {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'Not set' }}
              </p>
              <p :class="['text-sm font-medium', task.completed ? 'text-green-600' : 'text-red-600']">
                {{ task.completed ? 'Completed' : 'Not Completed' }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                Created At: {{ new Date(task.created_at).toLocaleDateString() }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                Updated At: {{ new Date(task.updated_at).toLocaleDateString() }}
              </p>
            </div>
          </div>
          <div v-else class="py-10">
            No <span class="font-semibold">completed</span> tasks found
          </div>

          <div class="flex w-full justify-between items-center my-4">
            <h1 class="text-2xl font-bold mb-4">Recently Added Tasks</h1>
          </div>
          <div v-if="recentTasksQIsPending" class="py-10">
            <a-spin size="large" />
          </div>
          <div v-else-if="recentTasksQIsError" class="py-10">
            <div>Error fetching tasks</div>
          </div>
          <div v-else-if="recentTasksQData && !axios.isAxiosError(recentTasksQData) && recentTasksQData.data.length > 0"
            class="grid md:grid-cols-3 grid-cols-1 gap-2">
            <div v-for="task in recentTasksQData.data" :key="task.id" class="task-card p-4 border rounded-lg shadow-md transition-all duration-300 cursor-pointer bg-white hover:bg-blue-100/70 border-gray-300 dark:bg-gray-700 dark:hover:bg-gray-800 dark:border-gray-600" @click="navigateToTask(task.id)">
              <div class="text-xl font-semibold mb-2">{{ task.title }}</div>
              <p class="text-gray-700 dark:text-gray-300">{{ task.description }}</p>
              <p class="text-gray-500 dark:text-gray-400">
                Due Date: {{ task.due_date ? new Date(task.due_date).toLocaleDateString() : 'Not set' }}
              </p>
              <p :class="['text-sm font-medium', task.completed ? 'text-green-600' : 'text-red-600']">
                {{ task.completed ? 'Completed' : 'Not Completed' }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                Created At: {{ new Date(task.created_at).toLocaleDateString() }}
              </p>
              <p class="text-gray-500 dark:text-gray-400">
                Updated At: {{ new Date(task.updated_at).toLocaleDateString() }}
              </p>
            </div>
          </div>
          <div v-else class="py-10">No tasks found</div>
        </div>

        <div class="flex flex-col md:w-4/12">
          <div v-if="usersQIsPending || userQIsPending" class="py-10">
            <a-spin size="large" />
          </div>
          <div v-else-if="usersQIsError || userQIsError" class="py-10">
            <div>Error fetching tasks</div>
          </div>
          <div
            v-else-if="usersQData && !axios.isAxiosError(usersQData) && usersQData.length > 0 && userQData && !axios.isAxiosError(userQData)">
            <div class="flex w-full justify-between items-center mb-4">
              <h1 class="text-2xl font-bold mb-4">Top Users</h1>
            </div>
            <div class="grid grid-cols-1 gap-4">
              <div v-for="user in sortedUsers" :key="user.id" class="user-card p-4 border rounded-lg shadow-md transition-all duration-300 bg-white hover:bg-blue-100/70 border-gray-300 dark:bg-gray-700 dark:hover:bg-gray-800 dark:border-gray-600">
                <h2 class="text-xl font-semibold mb-2">{{ user.username }}</h2>
                <p class="mb-2">
                  <strong>Joined:</strong> {{ new Date(user.created_at).toLocaleDateString() }} ({{
                    dayjs(user.created_at) }})
                </p>
                <h3 class="text-lg font-semibold mt-4 mb-2">
                  Tasks <span class="italisc text-sm">(total {{ user.tasks.length }})</span>
                </h3>
                <ul class="list-disc list-inside">
                  <li v-for="task in user.tasks.slice(0, 6)" :key="task.id" :class="[
                    'mb-2 flex justify-between items-center rounded-md px-2 py-1 transition-all duration-300 hover:bg-gray-300 dark:hover:bg-black',
                    user.id === userQData.id ? 'cursor-pointer' : ''
                  ]" @click="user.id === userQData.id && navigateToTask(task.id)">
                    <div>
                      <strong>{{ task.title }}</strong>: {{ truncateDescription(task.description) }} (Due: {{
                        task.due_date ? new Date(task.due_date).toLocaleDateString() : 'No due date' }})
                    </div>
                    <CheckBox v-if="task.completed" class="stroke-green-400 size-5 flex-shrink-0" />
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div v-else>No users found</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
