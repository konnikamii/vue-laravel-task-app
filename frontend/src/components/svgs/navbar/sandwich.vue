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
    <path className="sandwich-path top" d="M29 9L3 9" :stroke-width="2.5" stroke-linecap="round"/>
    <path className="sandwich-path middle" d="M29 17L3 17" :stroke-width="2.5" stroke-linecap="round"/>
    <path className="sandwich-path bottom" d="M29 25L3 25" :stroke-width="2.5" stroke-linecap="round"/>
  </svg>
</template>
 