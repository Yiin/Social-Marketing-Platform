<template>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="header">
                    <h4 class="title">Manage Google Plus Accounts</h4>
                </div>
                <div class="content">
                    <google-plus-accounts-table></google-plus-accounts-table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <template v-if="done">
                    <div class="header">
                        <h4 class="title">Posting was successfully queued!</h4>
                        <p class="category">
                            <a :href="`http://smp.x.yiin.lt/statistics/${queue_id}`" target="_blank">
                                http://smp.x.yiin.lt/statistics/{{ queue_id }}
                            </a>
                        </p>
                    </div>

                    <div class="content">
                        <p>
                            To see the progress, visit this url:
                            <a :href="`http://smp.x.yiin.lt/statistics/${queue_id}`" target="_blank">
                                http://smp.x.yiin.lt/statistics/{{ queue_id }}
                            </a>
                        </p>
                        <p>
                            Usually posting is completed within 15 minutes, but time
                            can vary depending on number of posts.
                        </p>
                        <button @click="done = false" class="btn">Alright</button>
                    </div>
                </template>
                <template v-else>
                    <div class="header">
                        <h4 class="title">Mass Post Settings</h4>
                    </div>
                    <div class="content">
                        <form @submit.prevent="post">
                            <div class="form-group" :class="{ 'has-error': errors.client_id }">
                                <label>Client</label>
                                <select data-title="Select Client We Post For" data-style="btn-default btn-block" data-menu-style="dropdown-blue" class="form-control selectpicker" v-model="client_id">
                                    <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
                                </select>
                                <template v-if="errors.client_id">
                                    <label v-for="errorMessage in errors.client_id" class="error">{{ errorMessage }}</label>
                                </template>
                            </div>
                            <div class="form-group" :class="{ 'has-error': errors.delay }">
                                <label>Delay between posts (in seconds)</label>
                                <input v-model="delay" placeholder="Delay between posts (in seconds)" type="number" class="form-control">
                                <template v-if="errors.delay">
                                    <label v-for="errorMessage in errors.delay" class="error">{{ errorMessage }}</label>
                                </template>
                            </div>
                            <div class="form-group" :class="{ 'has-error': errors.message }">
                                <label>Message</label>
                                <input v-model="message" name="message" type="text" placeholder="Enter post message" class="form-control">
                                <template v-if="errors.message">
                                    <label v-for="errorMessage in errors.message" class="error">{{ errorMessage }}</label>
                                </template>
                            </div>
                            <div class="form-group">
                                <label class="checkbox">
                                    <input type="checkbox" data-toggle="checkbox" v-model="isImageUrl">
                                    Check this box for image post
                                </label>
                            </div>
                            <div class="form-group" :class="{ 'has-error': errors.url }">
                                <label>URL</label>
                                <input v-model="url" type="text" name="url" :placeholder="isImageUrl ? 'Image URL' : 'Direct link'" class="form-control">
                                <template v-if="errors.url">
                                    <label v-for="errorMessage in errors.url" class="error">{{ errorMessage }}</label>
                                </template>
                            </div>

                            <button type="submit" class="btn btn-fill btn-primary" :disabled="!selectedCommunitiesTotal()">{{ selectedCommunitiesTotal() ? 'Post to selected communities' : 'Please select at least one community' }}</button>
                        </form>
                    </div>
                </template>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h4 class="title">Select communities</h4>
                </div>
                <div class="content">
                    <template v-for="(account, accountIndex) in accounts">
                        <div class="heading">
                            <h5 class="title">
                                {{ communitiesHeadingText(account) }}
                                <button class="btn btn-xs" :data-target="`#account-communities-${accountIndex}`" data-toggle="collapse">
                                    Show / Hide
                                </button>
                                <button class="btn btn-xs" @click="selectAllCommunities(account)">
                                    Select All
                                </button>
                                <button class="btn btn-xs" @click="resetSelection(account)">
                                    Reset Selection
                                </button>
                            </h5>
                        </div>
                        <div :id="`account-communities-${accountIndex}`" class="table-responsive collapse">

                            <table class="table communities">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Community Name</th>
                                    <th>Members</th>
                                    <th>Categories</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(community, index) in account.communities"
                                    :class="{ selected: community.selected }"
                                    @click="selectCommunity(community)"
                                >
                                    <td class="text-center">{{ index + 1 }}</td>
                                    <td class="highlight">{{ community.name }}</td>
                                    <td>{{ community.members === undefined ? '...' : community.members }}</td>
                                    <td>{{ community.categories === undefined ? '...' : community.categories }}</td>
                                    <td class="text-right">
                                        <label class="explanation">CLICK TO SELECT</label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .heading {
        margin: 20px;
    }

    .heading > .title > button {
        margin-left: 15px;
    }

    table.communities tbody tr {
        cursor: pointer;
    }

    table.communities tbody tr:hover {
        background: rgba(74, 119, 234, 0.37);
    }

    table.communities tbody tr.selected {
        background: #4a77ea;
        color: white;
    }

    table.communities tbody tr.selected > td.highlight {
        font-weight: bold;
    }

    .explanation {
        font-weight: normal;
        font-size: 12px;
    }
</style>

<script>
    export default {
        props: [
            'clientsjson'
        ],
        data() {
            return {
                done: false,
                errors: [],
                accounts: [{
                    communities: [{}]
                }],
                clients: [],
                delay: 1,
                client_id: undefined,
                message: '',
                url: '',
                isImageUrl: false,
                queue_id: 'ERROR'
            }
        },
        methods: {
            listeners() {
                this.$on('ACCOUNTS_LOADED', accounts => {
                    this.accounts = accounts;

                    setTimeout($('[data-toggle="collapse"]').collapse, 100);

                    accounts.forEach(this.loadCommunities);
                });
            },
            post() {
                let data = {
                    client_id: this.client_id,
                    delay: this.delay,
                    message: this.message,
                    isImageUrl: this.isImageUrl,
                    url: this.url,
                    queue: []
                };

                this.accounts.forEach(account => {
                    account.communities.filter(community => community.selected).forEach(community => {
                        data.queue.push({
                            username: account.username,
                            communityId: community.id
                        });
                    });
                });

                this.$http.post('/api/google-plus/post', data).then(response => {
                    this.done = true;
                    this.message = '';
                    this.isImageUrl = false;
                    this.url = '';
                    this.resetSelection();
                    this.queue_id = response.body;
                }).catch(response => {
                    this.errors = response.body;
                });
            },
            loadCommunities(account) {
                this.$http.post('/api/google-plus/communities', {username: account.username}).then(response => {
                    this.$set(account, 'communities', response.body);
                });
            },
            selectCommunity(community) {
                this.$set(community, 'selected', !community.selected);
            },
            selectAllCommunities(account) {
                if (!account.communities) {
                    return;
                }

                account.communities.forEach(community => {
                    this.$set(community, 'selected', true);
                });
            },
            resetSelection(account) {
                let reset = account => {
                    if (!account.communities) {
                        return;
                    }

                    account.communities.forEach(community => {
                        this.$set(community, 'selected', false);
                    });
                };

                if (account) {
                    reset(account);
                }
                else {
                    this.accounts.forEach(account => {
                        reset(account);
                    });
                }
            },
            selectedCommunities(account) {
                return account.communities.filter(community => community.selected).length;
            },
            selectedCommunitiesTotal() {
                let sum = 0;

                this.accounts.forEach(account => {
                    if (account.communities) {
                        sum += account.communities.filter(community => community.selected).length;
                    }
                });

                return sum;
            },
            communitiesHeadingText(account) {
                let text = `${account.username}`;

                if (account.communities === undefined) {
                    text += ' (loading...)';
                }
                else {
                    let selectedCommunities = this.selectedCommunities(account);

                    if (selectedCommunities) {
                        text += ` (selected ${selectedCommunities} out of ${account.communities.length})`;
                    }
                    else {
                        text += ` (${account.communities.length})`;
                    }
                }

                return text;
            }
        },
        mounted() {
            this.accounts = [];
            this.clients = JSON.parse(this.clientsjson);
            this.listeners();
        }
    }
</script>