<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" @click="getOrders">
                Orders <small>({{ tableData.data.length }})</small>
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div>
                            <jet-application-logo class="block h-12 w-auto"/>
                        </div>

                        <div>
                            <h2>Filters</h2>

                            <form class="grid grid-cols-10 gap-2" @submit.prevent="getOrders">
                                <div class="col-span-2">
                                    <el-input
                                        v-model="filters.term"
                                        clearable
                                        placeholder="Order #, email or customer name"
                                        v-on:keyup.enter="getOrders"
                                        :autocomplete="false"
                                    />
                                </div>
                                <div class="col-span-2">
                                    <el-select
                                        v-model="filters.fulfillment_status"
                                        class="width-100"
                                        clearable
                                        placeholder="Fulfillment Status"
                                        filterable
                                    >
                                        <el-option
                                            v-for="item in options.fulfillment_status"
                                            :value="item.value"
                                            :label="item.label"
                                        />
                                    </el-select>
                                </div>
                                <div class="col-span-2">
                                    <el-select
                                        v-model="filters.financial_status"
                                        class="width-100"
                                        placeholder="Payment Status"
                                        clearable
                                        filterable
                                    >
                                        <el-option v-for="item in options.payment_status" :value="item.value"
                                                   :label="item.label"/>
                                    </el-select>
                                </div>
                                <div class="col-span-2">
                                    <el-select
                                        v-model="filters.pickup_location"
                                        class="width-100"
                                        placeholder="Pickup Location"
                                        clearable
                                        filterable
                                    >
                                        <el-option
                                            :value="null"
                                            label="Any Pickup Location"
                                        />
                                        <el-option
                                            v-for="item in initData.options.pickup_locations"
                                            :value="item.name"
                                            :label="item.name"
                                        />
                                    </el-select>
                                </div>
                                <div>
                                    <el-button type="primary" @click="getOrders">Search</el-button>
                                </div>
                            </form>
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
                                v-loading="busy.orders"
                                element-loading-text="Loading..."
                                element-loading-spinner="el-icon-loading"
                                element-loading-background="rgba(0, 0, 0, 0.8)"
                            >
                                <el-table-column
                                    type="selection"
                                    width="55">
                                </el-table-column>
                                <el-table-column
                                    prop="order_number"
                                    label="Order"
                                    width="65">
                                    <template #default="scope">
                                        <el-button type="text" @click="handleShowOrderDrawer(scope.row)">
                                            {{ scope.row.order_number }}
                                        </el-button>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    label="Name"
                                    width="180">
                                    <template #default="scope">
                                        {{ scope.row.customer !== null ? scope.row.customer.full_name : scope.email }}
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    width="100"
                                    label="Fulfillment">
                                    <template #default="scope">
                                        {{ scope.row.fulfillment_status ? scope.row.fulfillment_status : 'New' }}
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    prop="financial_status"
                                    width="100"
                                    label="Payment">
                                </el-table-column>
                                <el-table-column
                                    label="Date"
                                    width="130">
                                    <template #default="scope">
                                        <span :title="formatDateTime(scope.row.created_at)">
                                            {{ formatDate(scope.row.created_at) }}
                                        </span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    label="Pickup/Address"
                                    width="180">
                                    <template #default="scope">
                                        {{ scope.row.pickup_location }}
                                        <div v-if="scope.row.pickup_location === 'Other'">
                                            <div v-if="scope.row.shipping_address">
                                                {{ scope.row.shipping_address.company }}<br>
                                                {{ scope.row.shipping_address.city }},
                                                {{ scope.row.shipping_address.province_code }},
                                                {{ scope.row.shipping_address.country_code }}
                                            </div>
                                            <div v-else-if="scope.row.shipping_lines.length">
                                                <span class="bold">Pickup: </span>{{
                                                    scope.row.shipping_lines[0].title
                                                }}
                                            </div>
                                            <div v-else>
                                                No address
                                            </div>
                                        </div>

                                    </template>
                                </el-table-column>
                                <el-table-column
                                    label="Items">
                                    <template #default="scope">
                                        <div v-for="item in scope.row.line_items">
                                            <span class="bold"> ({{ item.quantity * itemMultiplier }})</span>
                                            {{ item.name }}
                                        </div>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <el-drawer
            title="Order Details"
            v-model="drawer.order">
            <div class="p-3 drawer-order" style="font-size: 12px" v-if="drawer.order">
                <el-card class="box-card mb-3">
                    <template #header>
                        <div class="card-header bold">
                            <span>Action</span>
                        </div>
                    </template>

                    <el-button type="primary" plain size="small" @click="handleShowCustomerOrders">View Orders
                    </el-button>
                </el-card>

                <el-card class="box-card mb-3">
                    <template #header>
                        <div class="card-header bold">
                            <span>Customer Details</span>
                        </div>
                    </template>
                    <div class="text item">
                        <span class="bold mr-3">Allow Fulfillment:</span>:
                        <span v-if="drawer.order.customer.allow_fulfillment === true" class="allow-yes">Yes</span>
                        <span v-else class="allow-no">{{ drawer.order.customer.allow_fulfillment }}</span>
                    </div>
                    <div class="text item">
                        <span class="bold mr-3">Percentage Administered:</span>:
                        {{ drawer.order.customer.percentage_administered }}%
                    </div>
                    <div class="text item">
                        <span class="bold mr-3">Name:</span> {{ drawer.order.customer.full_name }}
                    </div>
                    <div class="text item">
                        <span class="bold mr-3">Order Count:</span>
                        <span class="clickable" @click="handleShowCustomerOrders">
                            {{ drawer.order.customer.orders_count }}
                        </span>
                    </div>
                    <div class="text item">
                        <span class="bold mr-3">Email Address:</span>
                        <a :href="'mailto:' + drawer.order.customer.email">{{ drawer.order.customer.email }}</a>
                    </div>
                    <div class="text item">
                        <span class="bold mr-3">Phone:</span>
                        <a :href="'tel:' + drawer.order.customer.phone">{{ drawer.order.customer.phone }}</a>
                    </div>
                </el-card>

                <el-card class="box-card mb-3">
                    <template #header>
                        <div class="card-header bold">
                            <span>Order Details</span>
                        </div>
                    </template>
                    <div class="text item">
                        <label>Fulfillment Status:</label>
                        {{ drawer.order.fulfillment_status ? drawer.order.fulfillment_status : 'New' }}
                    </div>
                    <div class="text item">
                        <label>Order Date:</label> {{ formatDateTime(drawer.order.created_at) }}
                    </div>
                    <hr/>
                    <div class="text item" v-for="item in drawer.order.line_items">
                        <span class="bold">({{ item.quantity * itemMultiplier }})</span> {{ item.quantity }} -
                        {{ item.name }}
                    </div>
                </el-card>

                <el-card class="box-card">
                    <template #header>
                        <div class="card-header bold">
                            <span>Pickup/Delivery Details</span>
                        </div>
                    </template>
                    <div class="text item">
                        {{ drawer.order.pickup_location }}
                        <div v-if="drawer.order.pickup_location === 'Other'">
                            <div v-if="drawer.order.shipping_address">
                                {{ drawer.order.shipping_address.company }}<br>
                                {{ drawer.order.shipping_address.city }},
                                {{ drawer.order.shipping_address.province_code }},
                                {{ drawer.order.shipping_address.country_code }}
                            </div>
                            <div v-else-if="drawer.order.shipping_lines.length">
                                <span class="bold">Pickup: </span>{{
                                    drawer.order.shipping_lines[0].title
                                }}
                            </div>
                            <div v-else>
                                No address
                            </div>
                        </div>
                    </div>

                </el-card>
            </div>

        </el-drawer>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import Welcome from '@/Jetstream/Welcome'
import moment from "moment";

export default {
    components: {
        AppLayout,
        Welcome,
    },
    props: {
        initData: {},
        itemMultiplier: {
            default: 25
        }
    },
    data() {
        return {
            drawer: {
                order: null
            },
            busy: {
                orders: false
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
            filters: {
                term: null,
                fulfillment_status: null,
                financial_status: null,
                pickup_location: null
            },
            blankFilter: {},
            options: {
                fulfillment_status: [
                    {label: 'Any Fulfillment Status', value: null},
                    {label: 'Fulfilled', value: 'fulfilled'},
                    {label: 'Unfulfilled', value: 'unfulfilled'},
                    {label: 'Pending', value: 'pending'},
                    {label: 'Scheduled', value: 'scheduled'},
                    {label: 'Partially Fulfilled', value: 'partially_fulfilled'},
                    {label: 'On Hold', value: 'on_hold'}
                ],
                payment_status: [
                    {label: 'Any Payment Status', value: null},
                    {label: 'Voided', value: 'voided'},
                    {label: 'Authorized', value: 'authorized'},
                    {label: 'Paid', value: 'paid'},
                    {label: 'Pending', value: 'pending'},
                    {label: 'Refunded', value: 'refunded'},
                    {label: 'Unpaid', value: 'unpaid'},
                    {label: 'Partially Refunded', value: 'partially_refunded'},
                    {label: 'Partially Paid', value: 'partially_paid'}
                ]
            }
        }
    },
    methods: {
        handleShowCustomerOrders() {
            this.resetFilters();
            this.filters.term = this.drawer.order.email;
            this.getOrders();
            this.drawer.order = null;
        },
        handleShowOrderDrawer(order) {
            this.drawer.order = order;
        },
        resetFilters() {
            this.filters = Object.assign({}, this.blankFilter);
        },
        getOrders() {
            let self = this;
            this.busy.orders = true;
            let params = {
                per_page: this.tableData.per_page,
                page: this.tableData.current_page,
                ...this.filters
            };

            axios.get('/api/orders', {params})
                .then(function ({data}) {
                    self.tableData = data;
                    console.log({data})
                })
                .catch(function (err) {
                    console.err(err);
                })
                .finally(function () {
                    self.busy.orders = false;
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
            this.tableData.per_page = val;
            this.getOrders();
        },
        handleCurrentChange(val) {
            console.log(`current page: ${val}`);
            this.getOrders();
        },
        formatDate(dateString) {
            return moment(dateString).format("ll");
        },
        formatDateTime(dateString) {
            return moment(dateString).format("lll");
        }
    },
    mounted() {
        this.tableData = this.initData.orders;
        this.blankFilter = Object.assign({}, this.filters);
    }
}
</script>

<style scoped>
.drawer-order label {
    font-weight: bold;
}
</style>
