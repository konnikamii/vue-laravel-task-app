import axios from "axios";
import { queryOptions } from "@tanstack/vue-query";
import { AuthResponse } from "../utils_other/types";
import { BACKEND_URL } from "../utils_other/defaults";

export const isAuthenticated = async () => {
  console.log("authenthicating");
  const token = localStorage.getItem("access_token");
  if (!token) {
    window.location.href = "/login";
  }
  try {
    const response = await axios.post<AuthResponse>(
      `${BACKEND_URL}api/auth/`,
      {},
      {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("access_token")}`,
        },
        withCredentials: true,
      },
    );
    if (response.status !== 200) {
      localStorage.removeItem("access_token");
      window.location.href = "/login";
      return false;
    } else {
      return true;
    }
  } catch (error) {
    console.error(error);
    localStorage.removeItem("access_token");
    window.location.href = "/login";
    return false;
  }
};

export const authQueryOptions = () => {
  return queryOptions({
    queryKey: ["auth"],
    queryFn: isAuthenticated,
    staleTime: 5 * 60 * 1000, //auth every 5 minutes
  });
};
