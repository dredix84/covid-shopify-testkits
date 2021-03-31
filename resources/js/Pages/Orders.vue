<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                        <div>
                            <jet-application-logo class="block h-12 w-auto"/>
                        </div>

                        <div class="mt-8 text-2xl">
                            Welcome to your Jetstream application!
                        </div>

                        <div class="mt-6 text-gray-500">
                            <el-table
                                ref="multipleTable"
                                :data="tableData"
                                stripe
                                style="width: 100%"
                                @selection-change="handleSelectionChange"
                            >
                                <el-table-column
                                    type="selection"
                                    width="55">
                                </el-table-column>
                                <el-table-column
                                    prop="date"
                                    label="Date"
                                    width="180">
                                </el-table-column>
                                <el-table-column
                                    prop="name"
                                    label="Name"
                                    width="180">
                                </el-table-column>
                                <el-table-column
                                    prop="address"
                                    label="Address">
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
import Welcome from '@/Jetstream/Welcome'

export default {
    components: {
        AppLayout,
        Welcome,
    },
    data() {
        return {
            tableData: [
                {
                    date: '2016-05-03',
                    name: 'Tom',
                    address: 'No. 189, Grove St, Los Angeles'
                }, {
                    date: '2016-05-02',
                    name: 'Tom',
                    address: 'No. 189, Grove St, Los Angeles'
                }, {
                    date: '2016-05-04',
                    name: 'Tom',
                    address: 'No. 189, Grove St, Los Angeles'
                }, {
                    date: '2016-05-01',
                    name: 'Tom',
                    address: 'No. 189, Grove St, Los Angeles'
                }
            ]
        }
    },
    methods: {
        getOrders() {
            axios.get('/api/orders/list')
                .then(function ({data}) {
                    console.log({data})
                })
                .catch(function (err) {
                    console.err(err);
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
    },
    mounted() {
        this.getOrders();
    }
}
</script>
