import { createApp } from "vue";
import "./style.css";
import App from "./App.vue";
import { router } from "./router";
import Antd from "ant-design-vue";
import "ant-design-vue/dist/reset.css";
import { VueQueryPlugin } from "@tanstack/vue-query";
import dayjs from "dayjs";
import VueGtag from "vue-gtag";

const VITE_TRACKING_ID =
  import.meta.env.VITE_TRACKING_ID?.trim() ?? "G-XXXXXXXXXX";

const app = createApp(App);

app.config.globalProperties.$dayjs = dayjs;
app.provide("dayjs", dayjs);

app
  .use(router)
  .use(Antd)
  .use(VueQueryPlugin)
  .use(VueGtag, { config: { id: VITE_TRACKING_ID } })
  .mount("#app");
