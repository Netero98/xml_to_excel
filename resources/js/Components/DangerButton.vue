<script setup>
import {computed} from "vue";

const props = defineProps({
  disabled: {
    type: Boolean,
    default: false
  }
})

const style_class_base = 'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'

const style_class = computed(() => {
  return props.disabled
      ? style_class_base + ' opacity-10 '
      : style_class_base
})

</script>

<template>
    <button
        :class="style_class"
        :disabled="disabled"
    >
        <slot />
    </button>
</template>
