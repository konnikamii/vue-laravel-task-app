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
    <path d="M11 10V5C11 3.34315 12.3431 2 14 2H25C26.6569 2 28 3.34315 28 5V27C28 28.6569 26.6569 30 25 30H14C12.3431 30 11 28.6569 11 27V22M17 16H2M2 16L5 13M2 16L5 19" :stroke-width="2" stroke-linecap="round"/>
  </svg>
</template>
 