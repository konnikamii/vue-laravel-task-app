import axios, { AxiosError } from "axios";
import { queryOptions, keepPreviousData } from "@tanstack/vue-query";
import {
  TasksGet,
  UsersGet,
  TasksIdGet,
  User,
  UserTasks,
  TasksIn,
  Task,
} from "../utils_other/types";
import { BACKEND_URL } from "../utils_other/defaults";
import { message } from "ant-design-vue";

const staleTime = 1000 * 60 * 30; //fetch every 30 minutes
const messageApi = message;

// -------------------------- User -------------------------- //
export const fetchUser = () => {
  console.log("fetching user");
  return axios
    .get<User>(`${BACKEND_URL}api/user/`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("access_token")}`,
      },
      withCredentials: true,
    })
    .then((response) => {
      return response.data;
    })
    .catch((error: AxiosError) => {
      console.log(error);
      if (error.response?.status === 401) {
        messageApi.warning("Your session has expired. Please log in again.", 3);
        localStorage.removeItem("access_token");
        window.location.href = "/login";
      }
      return error;
    });
};
export const userQueryOptions = () => {
  return queryOptions({
    queryKey: ["user"],
    queryFn: fetchUser,
    staleTime: staleTime, //fetch every minute
    placeholderData: keepPreviousData,
  });
};
export const fetchUsers = ({ type }: UsersGet) => {
  console.log("fetching users", type);
  return axios
    .post<UserTasks[]>(
      `${BACKEND_URL}api/users/`,
      { type },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("access_token")}`,
        },
        withCredentials: true,
      },
    )
    .then((response) => {
      return response.data;
    })
    .catch((error: AxiosError) => {
      console.log(error);
      if (error.response?.status === 401) {
        messageApi.warning("Your session has expired. Please log in again.", 3);
        localStorage.removeItem("access_token");
        window.location.href = "/login";
      }
      return error;
    });
};
export const usersQueryOptions = ({ type }: UsersGet) => {
  return queryOptions({
    queryKey: ["users", type],
    queryFn: () => fetchUsers({ type }),
    staleTime: staleTime, //fetch every minute
    placeholderData: keepPreviousData,
  });
};
// -------------------------- Tasks -------------------------- //
export const fetchTasks = ({
  page,
  page_size,
  sort_by,
  sort_type,
}: TasksGet) => {
  console.log("fetching tasks");
  return axios
    .post<TasksIn>(
      `${BACKEND_URL}api/tasks/`,
      { page, page_size, sort_by, sort_type },
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("access_token")}`,
        },
        withCredentials: true,
      },
    )
    .then((response) => {
      return response.data;
    })
    .catch((error: AxiosError) => {
      console.log(error);
      if (error.response?.status === 401) {
        messageApi.warning("Your session has expired. Please log in again.", 3);
        localStorage.removeItem("access_token");
        window.location.href = "/login";
      }
      return error;
    });
};
export const tasksQueryOptions = ({
  page,
  page_size,
  sort_by,
  sort_type,
}: TasksGet) => {
  console.log("fetching tasks", page, page_size, sort_by, sort_type);
  return queryOptions({
    queryKey: ["tasks", page, page_size, sort_by, sort_type],
    queryFn: () => fetchTasks({ page, page_size, sort_by, sort_type }),
    staleTime: staleTime, //fetch every minute
    placeholderData: keepPreviousData,
  });
};
export const fetchTaskId = ({ id }: TasksIdGet) => {
  console.log("fetching task with id:", id);
  return axios
    .get<Task>(`${BACKEND_URL}api/task/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("access_token")}`,
      },
      withCredentials: true,
    })
    .then((response) => {
      return response.data;
    })
    .catch((error: AxiosError) => {
      console.log(error);
      if (error.response?.status === 401) {
        messageApi.warning("Your session has expired. Please log in again.", 3);
        localStorage.removeItem("access_token");
        window.location.href = "/login";
      }
      return error;
    });
};
export const taskIdQueryOptions = ({ id }: TasksIdGet) => {
  return queryOptions({
    queryKey: ["taskid", id],
    queryFn: () => fetchTaskId({ id }),
    staleTime: staleTime, // fetch every minute
    placeholderData: keepPreviousData,
  });
};
