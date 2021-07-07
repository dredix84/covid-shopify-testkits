<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" @click="getUsers">
                Manage Users <small>({{ users.data.length }})</small>
            </h2>
        </template>

        <div class="manage-users">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div>
                                <jet-application-logo class="block h-12 w-auto"/>
                            </div>

                            <div class="mt-6 text-gray-500">

                                <div>
                                    <h2>Filter</h2>
                                    <form @submit.prevent="handleSearch">
                                        <div class="grid grid-cols-12 gap-4 p-4">
                                            <div class="col-span-11">
                                                <el-input
                                                    class="width-100"
                                                    clearable
                                                    v-model="filter.term" size="small"
                                                    placeholder="Name or email"
                                                    @clear="getUsers()"
                                                />
                                            </div>
                                            <div>
                                                <el-button type="primary" @click="handleSearch" size="small">
                                                    Search
                                                </el-button>
                                            </div>
                                        </div>
                                    </form>

                                </div>

                                <el-button
                                    icon="el-icon-plus"
                                    @click="handleNewUserClick"
                                    size="small"
                                    type="primary"
                                    class="float-right"
                                >
                                    New User
                                </el-button>

                                <div>
                                    <el-pagination
                                        @size-change="handleSizeChange"
                                        @current-change="handleCurrentChange"
                                        v-model:currentPage="users.current_page"
                                        :page-sizes="[25, 50, 100, 200, 300, 400]"
                                        :page-size="users.per_page"
                                        layout="sizes, prev, pager, next, total, slot"
                                        :total="users.total">
                                    </el-pagination>
                                </div>

                                <el-table
                                    ref="multipleTable"
                                    :data="users.data"
                                    stripe
                                    style="width: 100%"
                                    @selection-change="handleSelectionChange"
                                    v-loading="busy.users"
                                    element-loading-text="Loading..."
                                    element-loading-spinner="el-icon-loading"
                                    element-loading-background="rgba(0, 0, 0, 0.8)"
                                >
                                    <el-table-column
                                        label="Name"
                                        prop="name"
                                    >
                                    </el-table-column>

                                    <el-table-column
                                        label="Email"
                                        prop="email"
                                    >
                                    </el-table-column>

                                    <el-table-column
                                        label="Company"
                                        prop="company"
                                    >
                                    </el-table-column>

                                    <el-table-column
                                        label="Pickup Locations"
                                    >
                                        <template #default="scope">
                                            {{ getLocationsText(scope.row.pickup_locations) }}
                                        </template>
                                    </el-table-column>

                                    <el-table-column
                                        label="Admin"
                                        prop="is_admin"
                                        width="65"
                                    >
                                        <template #default="scope">
                                            <span v-if="scope.row.is_admin">Yes</span>
                                            <span v-else>No</span>
                                        </template>
                                    </el-table-column>

                                    <el-table-column
                                        label="Created"
                                        width="130">
                                        <template #default="scope">
                                        <span :title="formatDateTime(scope.row.created_at)">
                                            {{ formatDate(scope.row.created_at) }}
                                        </span>
                                        </template>
                                    </el-table-column>
                                    <el-table-column width="90">
                                        <template #default="scope">
                                            <el-button type="primary" icon="el-icon-edit" circle
                                                       @click="handleSelectEditUser(scope.row)"
                                                       size="mini"></el-button>
                                            <el-button
                                                v-if="scope.row.is_admin === false"
                                                type="danger" icon="el-icon-delete" circle
                                                @click="handleDeleteUserClick(scope.row)"
                                                size="mini"
                                            ></el-button>
                                        </template>
                                    </el-table-column>

                                </el-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <el-drawer
            :title="editUser ? editUser.name : ''"
            v-model="showEditUserDrawer"
            :before-close="handleCloseEditUser"
            size="50%"
        >
            <div v-if="editUser">
                <div class="grid grid-cols-3 gap-4 p-4">
                    <div class="bold">Name:</div>
                    <div class="col-span-2">
                        <el-input v-model="editUser.name" size="small" placeholder="Name" maxlength="20"/>
                        <HasError :form="editUser" field="name"/>
                    </div>

                    <div class="bold">Email:</div>
                    <div class="col-span-2">
                        <el-input v-model="editUser.email" size="small" placeholder="Email" type="email"/>
                        <HasError :form="editUser" field="email"/>
                    </div>

                    <div class="bold">Company:</div>
                    <div class="col-span-2">
                        <el-input v-model="editUser.company" size="small" placeholder="Company"/>
                        <HasError :form="editUser" field="company"/>
                    </div>

                    <div class="bold">Is Admin:</div>
                    <div class="col-span-2">
                        <el-switch
                            v-model="editUser.is_admin"
                            active-text="Is an admin"
                            inactive-text="Is not admin"
                            inactive-color="red"
                        >
                        </el-switch>
                    </div>

                    <div class="bold" v-if="!editUser.is_admin">Pickup Locations:</div>
                    <div class="col-span-2" v-if="!editUser.is_admin">
                        <el-select
                            v-model="editUser.pickup_locations"
                            placeholder="Select" multiple class="width-100"
                            size="small"
                            filterable
                            clearable
                        >
                            <el-option
                                v-for="item in initData.options.pickup_locations"
                                :key="item._id"
                                :label="item.name"
                                :value="item._id">
                            </el-option>
                        </el-select>
                        <HasError :form="editUser" field="pickup_locations"/>
                    </div>

                    <div class="bold">Password:</div>
                    <div class="col-span-2">
                        <el-input
                            v-model="editUser.password" size="small" type="password"
                            placeholder="Leave blank for no change"
                            clearable
                        />
                        <HasError :form="editUser" field="password"/>
                    </div>

                    <div class="bold">Confirm Password:</div>
                    <div class="col-span-2">
                        <el-input
                            v-model="editUser.password_confirmation" size="small" type="password"
                            placeholder="Confirm password if changing"
                            clearable
                        />
                        <HasError :form="editUser" field="password_confirmation"/>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 p-4">
                    <div>
                        <el-button type="info" plain @click="handleCloseEditUser" class="width-100">
                            Cancel
                        </el-button>
                    </div>
                    <div>
                        <el-button type="primary" class="width-100" @click="saveUser">Save</el-button>
                    </div>
                </div>
            </div>

        </el-drawer>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import moment from "moment";
import Form from 'vform'
import {HasError, AlertError} from 'vform/src/components/tailwind'

export default {
    name: "ManageUsers",
    props: ['initData'],
    components: {
        AppLayout,
        HasError
    },
    data() {
        return {
            filter: {
                term: ''
            },
            users: {
                data: [],

            },
            editUser: {},
            showEditUserDrawer: false,
            busy: {
                users: false
            },
        }
    },
    methods: {
        getLocationsText(ids) {
            if (ids && ids.length) {
                let outStrings = [];
                outStrings = this.initData.options.pickup_locations.filter(function (item) {
                    return ids.includes(item._id);
                });

                return outStrings
                    .map(function (item) {
                        return item.name
                    })
                    .join(', ');
            } else {
                return "No specified";
            }
        },
        handleSearch() {
            this.getUsers();
        },
        handleNewUserClick() {
            this.editUser = new Form({
                name: '',
                email: '',
                company: '',
                is_admin: false,
                pickup_locations: []
            });
            this.showEditUserDrawer = true;
        },
        handleSizeChange(val) {
            console.log(`${val} items per page`);
            this.getUsers();
        },
        handleCurrentChange(val) {
            console.log(`current page: ${val}`);
            this.getUsers();
        },
        handleSelectEditUser(user) {
            this.editUser = new Form({...user});
            this.showEditUserDrawer = true;
        },
        handleCloseEditUser() {
            this.showEditUserDrawer = false;
            this.editUser = null;
        },
        getUsers() {
            let self = this;
            this.busy.users = true;
            axios.get('/api/users', {params: this.filter})
                .then(function ({data}) {
                    self.users = data;
                })
                .catch(function (err) {
                    console.error(err);
                    self.$notify.error({
                        title: 'Error',
                        message: 'This is an error message'
                    });
                })
                .finally(function () {
                    self.busy.users = false;
                });
        },
        formatDate(dateString) {
            return moment(dateString).format("ll");
        },
        formatDateTime(dateString) {
            return moment(dateString).format("lll");
        },
        saveUser() {
            let self = this;
            if (this.editUser) {
                this.editUser.post('/api/users')
                    .then(function ({data}) {
                        console.log({data});
                        self.getUsers();
                        self.handleCloseEditUser();
                    })
                    .catch(function (err) {
                        console.error(err);
                        self.$notify.error({
                            title: 'Error While Saving',
                            message: 'There was an error while attempting to save the user data'
                        });
                    })
            }
        },
        handleDeleteUserClick(user) {
            if (user.is_admin) {
                this.$notify({
                    title: 'Warning',
                    message: 'You are not allowed to delete a user who is an admin',
                    type: 'warning'
                });
            } else {
                let self = this;
                this.$confirm(`Are you sure you want to delete ${user.name}?`, 'Warning', {
                    confirmButtonText: 'DELETE',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.delete('/api/users/' + user._id)
                        .then(function ({data}) {
                            console.log({data});
                            self.$notify({
                                title: 'User Deleted',
                                message: `${user.name} was deleted and will no long have access.`,
                                type: 'success'
                            });
                        })
                        .catch(function (err) {
                            console.error(err);
                            self.$notify.error({
                                title: 'Error While Deleting',
                                message: 'There was an error while attempting to delete the user data'
                            });
                        })
                        .finally(function () {
                            self.getUsers();
                        })
                });
            }


        }
    },
    created() {
        this.users = this.initData.users;
    }
}
</script>

<style scoped>
.width-100 {
    width: 100%;
}
</style>
