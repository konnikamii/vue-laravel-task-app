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
    <rect x="5" y="5" width="22" height="22" rx="2" stroke="none"/>
    <path d="M5.5 17.5L13.5 24.5L26.5 7" fill="none" :stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</template>
