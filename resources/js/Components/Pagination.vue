<script setup>

import {Link} from "@inertiajs/vue3";

defineProps({
  links: Array
})

const classPaginateCurrent = "flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
const classPaginateCommon = "flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"

</script>

<template>
  <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
    <div class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
      <template v-for="link in links">
        <Link
          v-if="link.url"
          :href="link.url"
          :class="link.active ? classPaginateCurrent : classPaginateCommon"
          v-html="link.label"
        >
        </Link>
        <span v-else v-html="link.html"></span>
      </template>
    </div>
  </nav>
</template>

<style scoped>

</style>
