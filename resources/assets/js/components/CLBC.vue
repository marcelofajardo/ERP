<template>
    <div>
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Broadcast</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" name="name" id="name" placeholder="Broadcast Name" v-model="broadcast.name">
                        </div>

                        <div class="form-group">
                            <label for="number_of_users">Number of People</label>
                            <input class="form-control" min="1" max="20" type="text" name="number_of_users" id="number_of_users" placeholder="Number of people" v-model="broadcast.number_of_users">
                        </div>

                        <div class="form-group">
                            <label for="frequency">Frequency</label>
                            <input class="form-control" type="text" name="frequency" id="frequency" placeholder="Frequency" v-model="broadcast.frequency">
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control"  name="message" id="message" placeholder="Frequency" v-model="broadcast.message"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <input class="form-control" type="file" name="image" id="image" placeholder="Frequency">
                        </div>

                        <div class="form-group">
                            <label for="image">Scheduled For</label>
                            <datetime input-class="form-control" type="datetime" format="y-M-d H:m" v-model="broadcast.started_at"></datetime>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" v-model="broadcast.status" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <button @click="saveBroadcast" class="btn btn-success">Save</button>
                        </div>


                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="actions">
            <div class="row">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-2 form-horizontal">
                            <select class="form-control form-control-sm" v-model="pagination" name="show" id="show" @change="search">
                                <option value="2">2 Records</option>
                                <option value="25">25 Records</option>
                                <option value="50">50 Records</option>
                                <option value="100">100 Records</option>
                                <option value="200">200 Records</option>
                                <option value="500">500 Records</option>
                                <option value="1000">1000 Records</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input v-on:keyup.13="search" v-model="query" type="text" name="query" class="form-control form-control-sm" placeholder="Search Query...">
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-right pb-2 mb-3 float-right">
                            <div v-if="paginationData.show" class="input-group mr-2 pull-right">
                                <input v-on:keyup.13="getColdLeads()" v-model="paginationData.currentPage" type="text" value="" name="page_id" id="page_id" class="form-control form-control-sm" placeholder="Page #" style="width:50px !important;">
                                <div class="input-group-append">
                                    <button style="height: 34px;" v-on:click="getColdLeads()" class="btn btn-sm btn-primary">
                                        Go
                                    </button>
                                    <button style="height: 34px;" v-on:click="previousPage" class="btn btn-sm btn-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left" color="#FFFFFF"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                    </button>
                                    <button style="height: 34px;" v-on:click="nextPage" class="btn btn-sm btn-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right" color="#FFFFFF"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="btn-group mr-2">
                                <button disabled type="button" class="btn btn-primary disabled btn-sm">
                                    Pages <span class="badge badge-light">{{ paginationData.lastPage }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <a href="/cold-leads" class="btn btn-primary btn-sm">View Cold Leads</a>
                    <!-- Button to Open the Modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                        Add New Broadcast
                    </button>
                </div>
            </div>
        </div>
        <table class="table table-striped table-sm table-bordered mt-4">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Name</th>
                    <th>Message Content</th>
                    <th>Frequency</th>
                    <th>Start Time</th>
                    <th>Number Of Leads</th>
                    <th>Sent Messages</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="isLoading">
                    <td colspan="6">
                        <h1 class="text-center">
                            Loading broadcasts...
                        </h1>
                    </td>
                </tr>
                <tr v-for="(lead, index) in leads">
                    <td>{{ index+1}}</td>
                    <td>{{ lead.name }}</td>
                    <td class="text-center">
                        <div>
                            <strong>{{ lead.message }}</strong>
                        </div>
                        <img v-if="lead.image" :src="'/uploads/' + lead.image" alt="Broadcast Image" style="width: 250px;">
                    </td>
                    <td>{{ lead.frequency }}</td>
                    <td>{{ lead.started_at }}</td>
                    <td>{{ lead.number_of_users }}</td>
                    <td>{{ lead.messages_sent }}</td>
                    <td><strong>{{ lead.status ? 'Active' : 'Inactive' }}</strong></td>
                    <td>
                        <bar :key="'lead_'+lead.id" style="width: 200px; height: 200px;" :cd='getData(lead)'></bar>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import { Datetime } from 'vue-datetime';
    import 'vue-datetime/dist/vue-datetime.css';
    import Bar from './Bar.vue';
    export default {
        components: {
            datetime: Datetime,
            bar: Bar
        },
        mounted() {
            this.getColdLeads();
        },
        data: function() {
            return {
                query: '',
                pagination: 25,
                leads: [],
                accounts: [],
                isLoading: true,
                paginationData: {
                    lastPage: 1,
                    currentPage: 1,
                    perPage: 25,
                    show: false
                },
                dm: {
                    currentLeadId: null,
                },
                allDirectMessages: {},
                leadActiveMessages: {},
                selectedAccounts: {},
                message: [],
                currentDisabledLead: null,
                currentUploadingFileLead: null,
                currentLeadId: null,
                sender: null,
                receivers: [],
                broadcast: {
                    name: '',
                    number_of_users: 10,
                    frequency: 5,
                    message: '',
                    image: '',
                    started_at: '',
                    status: 1,
                    messages_sent: 0,
                    new:true
                }
            }
        },
        methods: {
            search(paginate = false) {
                if (this.query.length >= 4 || this.query.length == 0) {
                    this.getColdLeads();
                }
            },
            getData(lead) {
                return {
                    //Data to be represented on x-axis
                    labels: ['Total', 'Sent'],
                    datasets: [
                        {
                            label: 'Lead Statistics',
                            backgroundColor: ['#249EBF', '#1ABC9c'],
                            pointBackgroundColor: 'white',
                            borderWidth: 1,
                            pointBorderColor: '#249EBF',
                            data: [lead.number_of_users, lead.messages_sent]
                        }
                    ]
                };
            },
            getColdLeads() {
                let self = this;
                axios.get('/cold-leads-broadcasts', {
                    params: {
                        query: this.query.length >= 4 ? this.query : '',
                        pagination: this.pagination,
                        page: this.paginationData.currentPage
                    }
                }).then(function(response) {
                    self.accounts = response.data.accounts;
                    let leadsData = response.data.leads;
                    self.leads = leadsData.data;
                    self.paginationData.currentPage = leadsData.current_page;
                    self.paginationData.lastPage = leadsData.last_page;
                    if (leadsData.last_page>1) {
                        self.paginationData.show = true;
                    } else {
                        self.paginationData.show = false;
                    }
                    self.paginationData.perPage = leadsData.per_page;
                    self.isLoading = false;
                    self.$forceUpdate();
                });
                self.$forceUpdate();
            },
            saveBroadcast() {
                let self = this;
                this.$forceUpdate();

                let gameFile = document.getElementById("image").files[0];

                let data = new FormData();
                data.append('image', gameFile);
                data.append('name', this.broadcast.name);
                data.append('number_of_users', this.broadcast.number_of_users);
                data.append('started_at', this.broadcast.started_at);
                data.append('status', this.broadcast.status);
                data.append('message', this.broadcast.message);
                data.append('frequency', this.broadcast.frequency);

                axios({
                    method: 'post',
                    url: '/cold-leads-broadcasts',
                    data: data,
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
                .then(function (response) {
                    if (response.data.status == 'success') {
                        alert("Broadcast added successfully!");
                        self.broadcast = {
                            name: '',
                            number_of_users: 10,
                            frequency: 5,
                            message: '',
                            image: '',
                            started_at: '',
                            status: 1,
                            messages_sent: 0,
                            new:true
                        };
                        self.getColdLeads();
                    }
                })
                .catch(function () {
                    alert('Could not add a new broadcast!');
                });
            },
            nextPage: function() {
                let cp = this.paginationData.currentPage;
                let lp = this.paginationData.lastPage;
                if (cp < lp) {
                    this.paginationData.currentPage = cp+1;
                }
                this.getColdLeads();
            },
            previousPage: function() {
                let pp = 1;
                let cp = this.paginationData.currentPage;
                if (cp > pp) {
                    this.paginationData.currentPage = cp-1;
                }
                this.getColdLeads();
            },
            reset: function() {
                this.query = '';
                this.paginationData.currentPage = 1;
                this.getColdLeads();
            }
        }
    }
</script>

<style>
    .balon1, .balon2 {

        margin-top: 5px !important;
        margin-bottom: 5px !important;

    }


    .balon1 a {

        background: #42a5f5;
        color: #fff !important;
        border-radius: 20px 20px 3px 20px;
        display: block;
        max-width: 75%;
        padding: 7px 13px 7px 13px;

    }

    .balon1:before {

        content: attr(data-is);
        position: absolute;
        right: 15px;
        bottom: -0.8em;
        display: block;
        font-size: .750rem;
        color: rgba(84, 110, 122,1.0);

    }

    .balon2 a {

        background: #f1f1f1;
        color: #000 !important;
        border-radius: 20px 20px 20px 3px;
        display: block;
        max-width: 75%;
        padding: 7px 13px 7px 13px;

    }

    .balon2:before {

        content: attr(data-is);
        position: absolute;
        left: 13px;
        bottom: -0.8em;
        display: block;
        font-size: .750rem;
        color: rgba(84, 110, 122,1.0);

    }
</style>