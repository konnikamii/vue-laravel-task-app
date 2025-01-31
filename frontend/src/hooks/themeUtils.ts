import { computed } from "vue";
import { useMediaQuery } from "@vueuse/core";
import { DimensionContextType } from "../utils_other/types";

export const getInitialTheme = (): "light" | "dark" => {
  const storedTheme = localStorage.getItem("theme");
  if (storedTheme === "light" || storedTheme === "dark") {
    return storedTheme;
  }
  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  return prefersDark ? "dark" : "light";
};

export const useDims = () => {
  return computed<DimensionContextType>(() => ({
    isLandscape: useMediaQuery("(orientation: landscape)").value,
    plus500h: useMediaQuery("(min-height: 500px)").value,
    plus768h: useMediaQuery("(min-height: 768px)").value,
    plus1080h: useMediaQuery("(min-height: 1080px)").value,
    plus375: useMediaQuery("(min-width: 375px)").value,
    plus425: useMediaQuery("(min-width: 425px)").value,
    plus768: useMediaQuery("(min-width: 768px)").value,
    plus1024: useMediaQuery("(min-width: 1024px)").value,
    plus1440: useMediaQuery("(min-width: 1440px)").value,
    plus550: useMediaQuery("(min-width: 550px)").value,
  }));
};
