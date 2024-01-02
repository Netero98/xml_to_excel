<script setup>
import Pagination from "@/Components/Pagination.vue";
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3"
import {routes} from "@/config.js";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    offers: Object,
    filters: Object
})

const search = ref(props.filters.search)

watch(search, value => {
    router.visit(
        route(routes.offers_index),
        {
            method: "get",
            data: {
                search: value
            },
            preserveState: true,
            replace: true
        }
    )
})
function downloadExcel() {
    axios({
        url: route(routes.offers_download_excel),
        method: 'GET',
        responseType: 'arraybuffer',
    }).then((response) => {
        let blob = new Blob([response.data], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        })
        let link = document.createElement('a')
        link.href = window.URL.createObjectURL(blob)
        link.download = 'offers.xlsx'
        link.click()
    });
}

</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto p-6">
            <div class="flex gap-2">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search..."
                    class="flex-1 rounded-lg"
                >
                <PrimaryButton @click="downloadExcel">Загрузить в формате Excel</PrimaryButton>
            </div>

            <div class=" mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="p-10">
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="offer in offers.data"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                    >
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{offer.name}}
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="grid place-items-center">
                <Pagination :links="offers.links" class=""/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
