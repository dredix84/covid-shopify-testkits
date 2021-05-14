<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" @click="getOrders">
                <div v-if="customer.default_address.company">customer.default_address.company</div>
                <div v-else> {{ customer.full_name }}</div>
            </h2>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

                        <el-alert
                            v-show="submitted"
                            title="success alert"
                            type="success"
                            effect="dark"
                            show-icon>
                            Thank you for taking the time to fill out this form and we appreciate your feedback.
                        </el-alert>

                        <form v-show="!submitted" @submit.prevent="handleSubmitForm">

                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-3">
                                    <label>
                                        Number of employees in the organization?
                                        <span class="show-required">*</span>
                                    </label><br>
                                    <el-input-number v-model="form.employee_count" :min="0" :max="10000" :step="1"/>
                                    <div class="error-alert" v-if="form.errors.has('employee_count')"
                                         v-html="form.errors.get('employee_count')"/>
                                </div>

                                <div class="mb-3">
                                    <label>
                                        Number of employees participating in screening?
                                        <span class="show-required">*</span>
                                    </label><br>
                                    <el-input-number v-model="form.employee_participating" :min="0" :step="1"
                                                     :max="10000"></el-input-number>
                                    <div class="error-alert" v-if="form.errors.has('employee_participating')"/>
                                </div>

                                <div class="mb-3">
                                    <label>
                                        Number of rapid antigen tests administered this week?
                                        <span class="show-required">*</span>
                                    </label><br>
                                    <el-input-number v-model="form.antigen_tests_administered" :min="0" :max="10000"
                                                     :step="1"/>
                                    <div class="error-alert" v-if="form.errors.has('antigen_tests_administered')"
                                         v-html="form.errors.get('antigen_tests_administered')"/>
                                </div>

                                <div class="mb-3">
                                    <label>
                                        Number of presumptive positive results?
                                        <span class="show-required">*</span>
                                    </label><br>
                                    <el-input-number v-model="form.presumptive_positive" :min="0" :max="10000"
                                                     :step="1"/>
                                    <div class="error-alert" v-if="form.errors.has('presumptive_positive')"
                                         v-html="form.errors.get('presumptive_positive')"/>
                                </div>

                                <div class="mb-3">
                                    <label>
                                        Number of presumptive negative results?
                                        <span class="show-required">*</span>
                                    </label><br>
                                    <el-input-number v-model="form.presumptive_negative" :min="0" :max="10000"
                                                     :step="1"/>
                                    <div class="error-alert" v-if="form.errors.has('presumptive_negative')"
                                         v-html="form.errors.get('presumptive_negative')"/>
                                </div>

                                <div class="mb-3">
                                    <label>
                                        Number of inconclusive results?
                                        <span class="show-required">*</span>
                                    </label><br>
                                    <el-input-number v-model="form.inconclusive" :min="0" :max="10000" :step="1"/>
                                    <div class="error-alert" v-if="form.errors.has('inconclusive')"
                                         v-html="form.errors.get('inconclusive')"/>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>
                                    How would you rate your experience with rapid screening in the workplace?
                                    <span class="show-required">*</span>
                                </label><br>

                                <el-rate
                                    v-model="form.experience_rating"
                                    :texts="['Very bad', 'Bad', 'Ok', 'Good', 'Great']" show-text
                                    :colors="['#f6a249', '#4d9aff', '#6bee4a']"
                                />
                            </div>

                            <div class="mb-3">
                                <label>
                                    Any feedback you would like to provide on using rapid screening in the workplace?
                                    <span class="show-required">*</span>
                                </label><br>
                                <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 2, maxRows: 4}"
                                    placeholder="What's on your mind?"
                                    v-model="form.comment">
                                </el-input>
                            </div>


                            <div>
                                <el-button type="primary" @click="handleSubmitForm" :disabled="form.busy">Done
                                </el-button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Form from 'vform'


export default {
    name: "OrderFeedback",
    components: {
        AppLayout
    },
    props: [
        'customer'
    ],
    data: () => ({
        submitted: false,
        form: new Form({
            customer_id: null,
            employee_count: 0,
            employee_participating: 0,
            antigen_tests_administered: 0,
            presumptive_positive: 0,
            presumptive_negative: 0,
            inconclusive: 0,
            experience_rating: null,
            comment: null
        }),
        iconClasses: ['icon-rate-face-1', 'icon-rate-face-2', 'icon-rate-face-3']
    }),
    methods: {
        handleSubmitForm() {
            let self = this;

            this.form.post('/api/feedback')
                .then(function ({data}) {
                    console.log({data})
                    self.submitted = true;
                })
        }
    },
    created() {
        this.form.customer_id = this.customer._id;
    }
}
</script>

<style scoped>
.error-alert {
    color: red;
    font-size: .9em;
}

.show-required {
    color: red;
}
</style>
