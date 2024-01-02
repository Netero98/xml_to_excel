<script setup>
import {computed} from "vue";

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false
    }
})

const style_class_base = 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'

const style_class = computed(() => {
    return props.disabled
        ? style_class_base + ' opacity-10 '
        : style_class_base
})

</script>

<template>
    <button
        :disabled="disabled"
        :class="style_class"
    >
        <slot />
    </button>
</template>
