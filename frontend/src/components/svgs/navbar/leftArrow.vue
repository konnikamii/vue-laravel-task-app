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
    <path d="M15 27L3 15L15 3" :stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</template>
 