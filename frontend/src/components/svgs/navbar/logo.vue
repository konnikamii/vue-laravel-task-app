<script setup lang="ts">
import { ref } from 'vue';

defineProps({
  width: { type: String, default: "32" },
  height: { type: String, default: "32" },
  isLight: { type: Boolean, required: true }
});

const isHovered = ref(false);

const handleMouseEnter = () => {
  isHovered.value = true;
};

const handleMouseLeave = () => {
  isHovered.value = false;
};
</script>

<template>
  <svg
    :width="width"
    :height="height"
    viewBox="0 0 32 32"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    v-bind="$attrs"
    @mouseenter="handleMouseEnter"
    @mouseleave="handleMouseLeave"
  >  
    <path
      d="M27.5 13.5C33 20 26 30 16 30C8.26801 30 2 23.732 2 16C2 8.26801 8.26801 2 16 2C25.3299 2 30 7.5 30 16V20"
      stroke="url(#paint0_radial_1103_4989)"
      stroke-linecap="round"
      :class="['transition-all duration-300', isHovered ? 'scale-90' : 'scale-100', 'origin-center']"
    />
    <path
      d="M26 13.5C32.5 20.5 23.4609 28.5099 16 28.5C8.26802 28.4898 3.5 22.5 3.5 16C3.5 9.5 8.26801 3.5 16 3.5C23.732 3.5 29 8.26801 29 16V19.5"
      stroke="url(#paint1_radial_1103_4989)"
      stroke-linecap="round"
      :class="['transition-all duration-300', isHovered ? 'scale-90' : 'scale-100', 'origin-center']"
    />
    <rect
      :x="isHovered ? '8.5' : '9.5'"
      :y="isHovered ? '8.5' : '9.5'"
      :width="isHovered ? '15' : '13'"
      :height="isHovered ? '15' : '13'"
      rx="3.5"
      :stroke="isLight ? '#666666' : '#999999'"
      :stroke-width="isLight ? '1' : '2'"
      class="transition-all duration-300"
    />
    <path
      d="M8 11C12.5 14 14.5 18 15.5 20.5C19.5 14.5 22 8.5 28.5 4"
      stroke="url(#paint2_radial_1103_4989)"
      stroke-width="4"
      stroke-linecap="round"
      :class="['transition-all duration-300 origin-center', isHovered ? 'scale-110' : 'scale-100']"
      @mouseenter="isHovered = true"
      @mouseleave="isHovered = false"
    />
    <defs>
      <radialGradient id="paint0_radial_1103_4989" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(16.5 9) rotate(59.6209) scale(16.8077 34.3431)">
        <stop stop-color="#004798" />
        <stop offset="1" stop-color="#63ACFF" />
      </radialGradient>
      <radialGradient id="paint1_radial_1103_4989" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(16.5 9) rotate(59.6209) scale(16.8077 34.3431)">
        <stop stop-color="#009805" />
        <stop offset="1" stop-color="#63FF68" />
      </radialGradient>
      <radialGradient id="paint2_radial_1103_4989" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(18.6696 7.875) rotate(48.3704) scale(10.7388 24.9733)">
        <stop :stop-color="isLight ? '#0A0098' : '#5157ff'" />
        <stop offset="1" stop-color="#63ACFF" />
      </radialGradient>
    </defs>
  </svg>
</template>

 