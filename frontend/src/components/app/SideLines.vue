<script setup lang="ts">
//no script
</script>

<template>
  <div class="pointer-events-none absolute h-full w-full overflow-hidden">
    <div class="pointer-events-none relative h-full w-full overflow-hidden">
      <div
        class="scaling-up-side absolute top-0 left-0 h-full w-[7px] translate-x-[-75%] rounded-full bg-[#c83bf3] opacity-60 mix-blend-darken blur-[30px] filter dark:w-[5px] dark:opacity-20 dark:mix-blend-lighten"
      ></div>
      <div
        class="scaling-up-side absolute top-0 right-0 h-full w-[7px] translate-x-[75%] rounded-full bg-[#c83bf3] opacity-60 mix-blend-darken blur-[30px] filter dark:w-[5px] dark:opacity-20 dark:mix-blend-lighten"
      ></div>
    </div>
  </div>
</template>
