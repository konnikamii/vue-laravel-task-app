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
    <path d="M5 7L15.2774 17.7445C15.6712 18.1562 16.3288 18.1562 16.7226 17.7445L27 7M27 24.5L18 16M5.06973 24.5L14 16M7 25.5H25C26.6569 25.5 28 24.1569 28 22.5V9C28 7.34315 26.6569 6 25 6H7C5.34315 6 4 7.34315 4 9V22.5C4 24.1569 5.34315 25.5 7 25.5Z" fill="none" :stroke-width="1.8"/> 
  </svg>
</template>
