<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" @click="getCustomers">
                Customers
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div>
                            <jet-application-logo class="block h-12 w-auto"/>
                        </div>


                        <div class="mt-6 text-gray-500">
                            <div>
                                <el-pagination
                                    @size-change="handleSizeChange"
                                    @current-change="handleCurrentChange"
                                    v-model:currentPage="tableData.current_page"
                                    :page-sizes="[25, 50, 100, 200, 300, 400]"
                                    :page-size="tableData.per_page"
                                    layout="sizes, prev, pager, next, total, slot"
                                    :total="tableData.total">
                                </el-pagination>
                            </div>
                            <el-table
                                ref="multipleTable"
                                :data="tableData.data"
                                stripe
                                style="width: 100%"
                                @selection-change="handleSelectionChange"
                                v-loading="busy.customers"
                                element-loading-text="Loading..."
                                element-loading-spinner="el-icon-loading"
                                element-loading-background="rgba(0, 0, 0, 0.8)"
                            >
                                <el-table-column
                                    type="selection"
                                    width="55">
                                </el-table-column>
                                <el-table-column
                                    label="Name"
                                    width="150"
                                    prop="full_name"
                                >
                                </el-table-column>

                                <el-table-column
                                    prop="default_address.company"
                                    width="100"
                                    label="Company"/>

                                <el-table-column
                                    prop="orders_count"
                                    label="Orders"
                                    width="65"/>


                                <el-table-column
                                    prop="email"
                                    width="120"
                                    label="Email"/>

                                <el-table-column
                                    label="Address"
                                    width="180">
                                    <template #default="scope">
                                        {{ scope.row.default_address.city }},
                                        {{ scope.row.default_address.province_code }},
                                        {{ scope.row.default_address.country_code }}
                                    </template>
                                </el-table-column>

                                <el-table-column
                                    label="Created"
                                    width="110">
                                    <template #default="scope">
                                        {{ formatDate(scope.row.created_at) }}
                                    </template>
                                </el-table-column>

                                <el-table-column
                                    label="Info"
                                    width="220"
                                >
                                    <template #default="scope">
                                        <div>Last Order: {{ getLastOrder(scope.row.last_order) }}</div>
                                        <div>Last Feedback: {{ getLastFeedback(scope.row.last_feedback) }}</div>
                                        <div>
                                            Allow Fulfillment:
                                            <span v-if="calculateAllowFulfilment(scope.row)" class="allow-yes">
                                                Yes
                                            </span>
                                            <span v-else class="allow-no">
                                                No
                                            </span>
                                        </div>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>

import AppLayout from '@/Layouts/AppLayout'
import moment from "moment";

export default {
    name: "Customers",
    components: {
        AppLayout
    },
    props: [
        'initData'
    ],
    data() {
        return {
            busy: {
                customers: false
            },
            tableData: {
                current_page: 1,
                from: null,
                to: null,
                last_page: null,
                per_page: 50,
                total: 0,
                data: []
            },
            filters: {}
        }
    },
    methods: {
        getCustomers() {
            let self = this;
            this.busy.customers = true;
            let params = {
                per_page: this.tableData.per_page,
                page: this.tableData.current_page,
                filters: this.filters
            };

            axios.get('/api/customer', {params})
                .then(function ({data}) {
                    self.tableData = data;
                })
                .catch(function (err) {
                    console.err(err);
                })
                .finally(function () {
                    self.busy.customers = false;
                });
        },
        toggleSelection(rows) {
            if (rows) {
                rows.forEach(row => {
                    this.$refs.multipleTable.toggleRowSelection(row);
                });
            } else {
                this.$refs.multipleTable.clearSelection();
            }
        },
        handleSelectionChange(val) {
            console.log({val});
        },
        handleSizeChange(val) {
            console.log(`${val} items per page`);
            this.getCustomers();
        },
        handleCurrentChange(val) {
            console.log(`current page: ${val}`);
            this.getCustomers();
        },
        getLastOrder(last_order) {
            return last_order.length ? this.formatDate(last_order[0].created_at) : '--'
        },
        getLastFeedback(last_feedback) {
            return last_feedback.length ? this.formatDate(last_feedback[0].created_at) : '--'
        },
        calculateAllowFulfilment(record) {
            console.log({record})
            if (record.last_order.length && record.last_feedback.length && record.last_feedback[0].created_at > record.last_order[0].created_at) {
                return true;
            } else if (record.last_order.length && record.last_feedback.length === 0) {
                let orderDate = moment(record.last_order[0].created_at)
                console.log({fromNow: orderDate.fromNow()});
                return true;
            }
            return false
        },
        formatDateTime(dateString) {
            return moment(dateString).format("lll");
        },
        formatDate(dateString) {
            return moment(dateString).format("ll");
        }
    },
    mounted() {
        this.tableData = this.initData.customers;
    }
}
</script>

<style scoped>
.allow-no {
    color: white;
    background-color: red;
    padding: 2px 5px;
    border-radius: 5px;
}

.allow-yes {
    color: white;
    background-color: green;
    padding: 2px 5px;
    border-radius: 5px;
}
</style>
