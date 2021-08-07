<template>
    <div>
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
                        <div class="col-md-2">
                            <select v-on:change="search" name="gender" id="gender" v-model="gender" class="form-control">
                                <option value="All">All</option>
                                <option value="m">m</option>
                                <option value="f">f</option>
                                <option value="o">o</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select v-on:change="search" class="form-control form-control-sm" name="acc" id="acc" v-model="acc">
                                <option value="">All</option>
                                <option v-for="(account, key) in accounts" :value="account.id" :key="key" v-text="account.last_name"></option>
                            </select>
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
                    <a href="/cold-leads-broadcasts" class="btn btn-primary btn-sm">Broadcasts</a>
                </div>
            </div>
        </div>
        <input @change="sendImage" type="file" name="image" id="image" style="display: none">
        <table class="table table-striped table-sm table-bordered mt-4">
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Name</th>
                    <th>Basic Info</th>
                    <th>Gender</th>
                    <th>Direct Messaging</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-if="isLoading">
                    <td colspan="6">
                        <h1 class="text-center">
                            Loading cold leads...
                        </h1>
                    </td>
                </tr>
                <tr v-for="(lead, index) in leads">
                    <td>{{ index+1}}</td>
                    <td>{{ lead.name }}</td>
                    <td>
                        <strong>IG Username :</strong> {{ lead.username }}<br>
                        <strong>IG Profile :</strong> <a :href="'https://instagram.com/'+lead.username">Visit Profile</a><br>
                        <strong>Bio:</strong> {{ lead.bio}}
                    </td>
                    <td>
                        {{ lead.gender }}
                    </td>
                    <td>
                        <div :key="'chat_'+lead.id+'_'+Math.floor((Math.random() * 100))" class="card" style="min-width: 600px; width: 100%;">
                            <div class="card-header">
                                <strong class="pull-left">Chat</strong>
                                <div class="pull-right">
                                    <div class="form-group form-group-sm">
                                        <strong>{{ lead.account_id ? lead.account.last_name : 'N/A'}}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chats text-center mb-4" v-if="!allDirectMessages.hasOwnProperty(lead.id)">
                                    <strong>Loading Messages...</strong>
                                </div>
                                <div class="chats text-center mb-4" v-if="allDirectMessages.hasOwnProperty(lead.id)">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="text-center" v-if="allDirectMessages[lead.id].length == 0">
                                                <strong>No Messages yet!</strong>
                                            </div>
                                            <template v-if="allDirectMessages[lead.id].length > 0">
                                                <div style="height: 200px;max-height: 250px;overflow: auto;padding:10px 0px;" class="messages-list">
                                                    <div v-for="msg in leadActiveMessages[lead.id]['messages']">
                                                        <div :class="{'balon2' : leadActiveMessages[lead.id].lead_instagram_id==msg.sender_id,  'balon1' : leadActiveMessages[lead.id].lead_instagram_id!=msg.sender_id}" class=" is-received p-2 m-0 position-relative">

                                                            <a :class="{'float-right' : leadActiveMessages[lead.id].lead_instagram_id==msg.sender_id,  'float-right' : leadActiveMessages[lead.id].lead_instagram_id!=msg.sender_id}">
                                                                <template v-if="msg.type==1">{{ msg.message }}</template>
                                                                <template v-if="msg.type==2">
                                                                    <a :href="'/uploads/'+msg.message" target="_new">
                                                                        <img style="width: 200px;" :src="'/uploads/'+msg.message" class="img img-fluid">
                                                                    </a>
                                                                </template>
                                                            </a>
                                                        </div>
                                                        <br clear="all">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="messagebox">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input :disabled="currentDisabledLead==lead.id" v-model.lazy="message[lead.id]" :key="index+1" placeholder="Type Message here..." type="text" name="message" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <img v-if="currentDisabledLead==lead.id" src="/images/loading2.gif" style="width: 40px;">
                                            <label @click="setCurrentLead(lead.id)" for="image" class="btn btn-sm btn-primary">
                                                <i class="fa fa-image"></i>
                                            </label>
                                            <button :disabled="currentDisabledLead==lead.id" @click="sendDm(lead.id)" class="btn btn-sm btn-success">
                                                <i class="fa fa-send"></i> Send Message
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <button @click="addColdLeadToCustomer(lead.id)" class="btn btn-sm btn-success mt-2">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button @click="deleteColdLead(lead.id)" class="btn btn-sm btn-danger mt-2">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getColdLeads();
        },
        data: function() {
            return {
                query: '',
                gender: 'All',
                pagination: 2,
                leads: [],
                accounts: [],
                isLoading: true,
                paginationData: {
                    lastPage: 1,
                    currentPage: 1,
                    perPage: 2,
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
                acc: '',
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        methods: {
            search(paginate = false) {
                if (this.query.length >= 4 || this.query.length == 0) {
                    this.getColdLeads();
                }
            },
            getColdLeads() {
                let self = this;
                axios.get('/cold-leads', {
                    params: {
                        query: this.query.length >= 4 ? this.query : '',
                        pagination: this.pagination,
                        page: this.paginationData.currentPage,
                        gender: this.gender,
                        acc: this.acc
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
                    self.startLoadingDirectMessages();
                });
            },
            sendImage() {
                let leadId = this.currentLeadId;
                let self = this;
                this.currentDisabledLead = leadId;
                this.$forceUpdate();

                let gameFile = document.getElementById("image").files[0];

                let data = new FormData();
                data.append('image', gameFile);
                data.append('account_id', this.accounts[this.selectedAccounts[leadId]].id);

                axios({
                    method: 'post',
                    url: '/instagram/thread/'+leadId,
                    data: data,
                    config: { headers: {'Content-Type': 'multipart/form-data' }}
                })
                .then(function (response) {
                    if (response.data.status == 'success') {
                        self.updateMessageForAlead(leadId, response.data.message, response.data.receiver_id, response.data.sender_id, 2);
                        self.currentDisabledLead = null;
                    }
                })
                .catch(function (response) {
                    console.log(response);
                });
            },
            sendDm(leadId) {
                let self = this;
                this.currentDisabledLead = leadId;
                this.$forceUpdate();
                axios({
                        method: 'post',
                        url: '/instagram/thread/'+leadId,
                        data: {
                            account_id: this.accounts[this.selectedAccounts[leadId]].id,
                            message: this.message[leadId],
                            _token: this.csrf
                        },
                        config: { headers: {'Content-Type': 'multipart/form-data' }}
                    })
                    .then(function (response) {
                        if (response.data.status == 'success') {
                            self.allDirectMessages[leadId] = [];
                            self.startLoadingForOneCustomer(leadId);
                            // self.updateMessageForAlead(leadId, response.data.message, response.data.receiver_id, response.data.sender_id);
                            self.message[leadId] = '';
                            self.currentDisabledLead = null;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            },
            updateMessageForAlead(leadId, message, rid, sid, messageType=1) {
                this.leadActiveMessages[leadId].messages.push({
                    created_at: '',
                    message: message,
                    receiver_id: rid,
                    sender_id: sid,
                    type: messageType
                });
            },
            startLoadingDirectMessages() {
                let self = this;
                self.selectedAccounts = [];
                self.leads.forEach(function(item) {
                    self.startLoadingForOneCustomer(item.id);
                });
            },
            startLoadingForOneCustomer(id) {
                let self = this;
                axios
                    .get('/instagram/thread/'+id)
                    .then(function(response) {
                        self.allDirectMessages[id] = response.data;
                        if (self.allDirectMessages[id].length > 0) {
                            self.leadActiveMessages[id] = self.allDirectMessages[id][0];
                            let AccId = self.allDirectMessages[id][0].account_id;
                            let x = 0;
                            self.accounts.forEach(function(acc) {
                                console.log(id + ' => ' + AccId + ' VS ' + acc.id);
                                if (acc.id == AccId) {
                                    self.selectedAccounts[id] = x;
                                    self.$forceUpdate();
                                    return;
                                }
                                x++;
                            });
                        } else {
                            self.selectedAccounts[id] = 0;
                        }
                        self.$forceUpdate();
                    });
                ;
            },
            addLeadToCustomer(leadId) {

            },
            removedLeadToCustomer(leadId) {
            },
            setCurrentLead(leadId) {
                this.currentLeadId = leadId;
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
            },
            deleteColdLead(id) {
                //delete cold lead..
            },
            addColdLeadToCustomer(id) {
                //add cold lead to customer...
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