<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reporting
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-3 sm:px-10 bg-white border-b border-gray-200">
                        <div>
                            <jet-application-logo class="block h-12 w-auto"/>
                        </div>

                        <el-tabs type="card" v-model="activeTab">
                            <el-tab-pane label="Aggregate Feedback" name="a-feedback">
                                <report-feedback :csrf-token="csrfToken"></report-feedback>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import ReportFeedback from "../Components/ReportFeedback";

export default {
    name: "ReportsIndex",
    components: {
        ReportFeedback,
        AppLayout
    },
    props: {
        initData: {}
    },
    data() {
        return {
            activeTab: 'a-feedback',
            csrfToken: null
        }
    },
    created() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    }
}
</script>

<style scoped>

</style>
