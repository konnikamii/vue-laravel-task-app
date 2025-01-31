<script setup lang="ts">
defineProps({
  width: { type: String, default: "32" },
  height: { type: String, default: "32" },
});
</script>

<template>
  <svg
    :width="width"
    :height="height"
    viewBox="0 0 32 32"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    v-bind="$attrs"
  >   
    <path d="M4 30C4 30 4.54545 17 15.9998 17C27.4542 17 28 30 28 30" :stroke-width="2.8"/>
    <circle cx="16" cy="10" r="7" :stroke-width="2.8"/>
  </svg>
</template>