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
    <path d="M3.09091 6H9M12 6H28.9999M12 12H28.9999M9 12H3.09091M3.09091 18H9M12 18H28.9999M28.9999 24H12M9 24H3.09091" :stroke-width="2" />
  </svg>
</template>
 