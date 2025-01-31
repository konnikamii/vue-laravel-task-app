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
    <circle cx="16" cy="16" r="13" fill="none" :stroke-width="2.5" stroke="currentColor" />
    <path d="M16 15V22" fill="none" :stroke-width="3" stroke-linecap="round" stroke="currentColor" />
    <circle cx="16" cy="10.5" r="1.5" stroke="none" fill="currentColor" />
  </svg>
</template>
