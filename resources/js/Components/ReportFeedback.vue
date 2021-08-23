<template>
    <div>
        <form @submit.prevent="handleFormSubmit">
            <div class="mb-2">
                <el-alert
                    type="info"
                    show-icon

                >
                    Use the form below to filter your feedback report by a date range.
                </el-alert>
            </div>


            <el-date-picker
                v-model="formData.date_range"
                type="daterange"
                unlink-panels
                range-separator="To"
                start-placeholder="Start date"
                end-placeholder="End date"
                :shortcuts="shortcuts"
                value-format="YYYY-MM-DD"
                class="wide-range-width mr-2"
            >
            </el-date-picker>
            <el-button type="submit" @click="handleFormSubmit">Get Report</el-button>
        </form>
        <form
            id="report_filter"
            ref="report_filter"
            method="POST"
            action="/reports/feedback"
            target="_blank"
            style="display: none"
        >
            <textarea name="filters" style="width: 300px; height: 300px">{{formData}}</textarea>
            <input type="hidden" name="_token" :value="csrfToken"/>
        </form>
    </div>

</template>

<script>
export default {
    name: "ReportFeedback",
    props: ['csrfToken'],
    data() {
        return {
            formData: {
                date_range: null
            },
            shortcuts: [
                {
                    text: 'Last week',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
                        return [start, end]
                    },
                },
                {
                    text: 'Last month',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
                        return [start, end]
                    },
                },
                {
                    text: 'Last 3 months',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
                        return [start, end]
                    },
                },
                {
                    text: 'Last 6 months',
                    value: () => {
                        const end = new Date()
                        const start = new Date()
                        start.setTime(start.getTime() - ((3600 * 1000 * 24 * 90) * 2))
                        return [start, end]
                    },
                },
                {
                    text: 'This year',
                    value: () => {
                        const end = new Date()
                        const start = new Date(new Date().getFullYear(), 0, 1);
                        return [start, end]
                    },
                }
            ],
        }
    },
    methods: {
        dataValid() {
            if (this.formData.date_range === null) {
                this.$notify({
                    title: 'Warning',
                    message: 'A valid date range is required to run this report',
                    type: 'warning'
                });
                return false;
            }
            return true;
        },
        handleFormSubmit() {
            if (this.dataValid()) {
                this.$refs.report_filter.submit()
            }
        }
    }
}
</script>

<style scoped>
.wide-range-width {
    min-width: 100%;
}
</style>
