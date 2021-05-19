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

                        <form @submit.prevent="getCustomers">
                            <div class="grid grid-cols-10 gap-4">
                                <div class="col-span-9">
                                    <el-input
                                        v-model="searchTerm"
                                        placeholder="Search customer name or email"
                                        clearable
                                        @clear="getCustomers"
                                    />
                                </div>
                                <div>
                                    <el-button
                                        type="primary"
                                        title="Search"
                                        @click="getCustomers"
                                        :disabled="busy.customers"
                                    >
                                        <i class="el-icon-loading" v-if="busy.customers"></i>
                                        Search
                                    </el-button>
                                </div>
                            </div>
                        </form>


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
<!--                                <el-table-column-->
<!--                                    type="selection"-->
<!--                                    width="55">-->
<!--                                </el-table-column>-->
                                <el-table-column
                                    label="Name"
                                    width="150"
                                    prop="full_name"
                                    fixed
                                >
                                    <template #default="scope">
                                        <el-button
                                            type="text"
                                            @click="handleShowDrawer('Customer', scope.row)"
                                            size="medium">
                                            {{ scope.row.full_name }}
                                        </el-button>
                                    </template>
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
                                    prop="item_count"
                                    label="Items"
                                    width="65">
                                    <template #default="scope">
                                        <div>{{ scope.row.item_count * 25 }}</div>
                                    </template>
                                </el-table-column>

                                <el-table-column
                                    prop="total_administered"
                                    label="Used"
                                    width="65">
                                </el-table-column>

                                <el-table-column
                                    prop="total_administered"
                                    label="Used %"
                                    width="75">
                                    <template #default="scope">
                                        <div>{{ calculatePercentageAdministered(scope.row) }}%</div>
                                    </template>
                                </el-table-column>

                                <el-table-column
                                    prop="last_order"
                                    label="Last Order"
                                    width="120">
                                    <template #default="scope">
                                        <div>{{ getLastOrder(scope.row.last_order) }}</div>
                                    </template>
                                </el-table-column>

                                <el-table-column
                                    label="Last Feedback"
                                    width="120">
                                    <template #default="scope">
                                        <div>{{ getLastFeedback(scope.row.last_feedback) }}</div>
                                    </template>
                                </el-table-column>

                                <el-table-column
                                    label="Allow Fulfillment"
                                    width="220"
                                >
                                    <template #default="scope">
                                        <div>
                                            <span v-if="calculateAllowFulfilment(scope.row) === true" class="allow-yes">
                                                Yes
                                            </span>
                                            <span v-else class="allow-no">
                                                <span class="bold">No</span>: {{ calculateAllowFulfilment(scope.row) }}
                                            </span>
                                        </div>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    label=""
                                    width="100"
                                >
                                    <template #default="scope">
                                        <el-dropdown
                                            trigger="click"
                                            @command="handleCustomerMoreCommandClick"
                                        >
                                            <el-button type="primary" size="small" circle plain>
                                                <i class="el-icon-more"></i>
                                            </el-button>
                                            <template #dropdown #default="scope">
                                                <el-dropdown-menu>
                                                    <el-dropdown-item :command="`${scope.row._id},notes`">
                                                        <!--                                                    <el-dropdown-item :command="scope.row">-->
                                                        Notes
                                                    </el-dropdown-item>
                                                    <el-dropdown-item :command="`${scope.row._id},feedback`">
                                                        View Feedback
                                                    </el-dropdown-item>
                                                    <el-dropdown-item :command="`${scope.row._id},newFeedback`">
                                                        Open Feedback Form
                                                    </el-dropdown-item>
                                                </el-dropdown-menu>
                                            </template>
                                        </el-dropdown>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <el-drawer
            :title="drawer.show"
            v-model="drawer.show">
            <div class="m-1" v-if="drawer.show === 'Customer'">
                <div class="grid grid-cols-3 gap-4">
                    <div>Full Name:</div>
                    <div class="col-span-2">{{ drawer.data.full_name }}</div>

                    <div>Company:</div>
                    <div class="col-span-2">{{ drawer.data.default_address.company }}</div>

                    <div>Email Address:</div>
                    <div class="col-span-2">{{ drawer.data.email }}</div>

                    <div>Phone:</div>
                    <div class="col-span-2">{{ drawer.data.default_address.phone }}</div>

                    <div>City:</div>
                    <div class="col-span-2">{{ drawer.data.default_address.city }}</div>
                </div>
            </div>
        </el-drawer>

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
            drawer: {
                show: false,
                data: null
            },
            searchTerm: '',
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
        handleCustomerMoreCommandClick(command) {
            let commmandData = command.split(',');
            console.log({command, commmandData});

            switch (commmandData[1]) {
                case "newFeedback":
                    window.open('/feedback/' + commmandData[0], '_blank').focus();
                    break;
                default:
                    this.$notify({
                        title: 'Warning',
                        message: 'Not yet implemented',
                        type: 'warning'
                    });
                    break;
            }
        },
        handleShowDrawer(showType, data) {
            this.drawer = {
                show: showType,
                data: data
            };
        },
        getCustomers() {
            let self = this;
            this.busy.customers = true;
            let params = {
                per_page: this.tableData.per_page,
                page: this.tableData.current_page,
                term: this.searchTerm,
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
            return last_order ? this.formatDate(last_order.created_at) : '--'
        },
        getLastFeedback(last_feedback) {
            if (last_feedback) {
                console.log({last_feedback});
            }
            return last_feedback ? this.formatDate(last_feedback.created_at) : '--'
        },
        calculatePercentageAdministered(record) {
            let outValue = record.total_administered / (record.item_count * 25) * 100;
            return !isNaN(outValue) ? Math.floor(outValue) : 0
        },
        calculateAllowFulfilment(record) {
            if (!!record.last_order) {
                if (record.last_order && (record.last_feedback === null || !('last_feedback' in record))) {
                    return 'No feedback submitted';
                } else if (record.last_order && record.last_feedback && record.last_order.created_at > record.last_feedback.created_at) {
                    return 'No feedback submitted after last order';
                } else if (this.calculatePercentageAdministered(record) < 80) {
                    return 'Less than 80% used.';
                }
            }

            // console.log({record})
            // if (record.last_order.length && record.last_feedback.length && record.last_feedback[0].created_at > record.last_order[0].created_at) {
            //     return true;
            // } else if (record.last_order.length && record.last_feedback.length === 0) {
            //     let orderDate = moment(record.last_order[0].created_at)
            //     console.log({fromNow: orderDate.fromNow()});
            //     return true;
            // }
            return true;
        },
        formatDateTime(dateString) {
            let moment = require('moment');
            return moment(dateString).format("lll");
        },
        formatDate(dateString) {
            let moment = require('moment');
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
    color: black;
    background-color: #ffb7b7;
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
