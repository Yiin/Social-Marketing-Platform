<template>
    <div>
        <div v-if="addingNewAccount" class="card">
            <div class="header">Add Google Account</div>
            <div class="content">
                <form @submit.prevent="submitAccount">
                    <div class="form-group" :class="{ 'has-error': errors.password }">
                        <label>Username</label>
                        <input v-model="auth.username" type="text" placeholder="Enter username or email address"
                               class="form-control">
                        <template v-if="errors.username">
                            <label v-for="message in errors.username" class="error">{{ message }}</label>
                        </template>
                    </div>
                    <div class="form-group" :class="{ 'has-error': errors.password }">
                        <label>Password</label>
                        <input v-model="auth.password" type="password" placeholder="Password" class="form-control">
                        <template v-if="errors.password">
                            <label v-for="message in errors.password" class="error">{{ message }}</label>
                        </template>
                    </div>

                    <button type="submit" class="btn btn-fill btn-primary">Submit</button>
                    <button @click.prevent="back" class="btn btn-fill pull-right">Close</button>
                </form>
            </div>
        </div>
        <div v-else class="table-responsive">

            <table class="table">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Name</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(account, index) in accounts">
                    <td class="text-center">{{ index + 1 }}</td>
                    <td>{{ account.username }}</td>
                    <td class="text-right">
                        <button @click="logout(account)" type="button" class="btn btn-danger btn-simple btn-xs">
                            Log Out <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-right">
                        <button class="btn btn-primary btn-simple-btn-xs" @click="addNewAccount">
                            Add new Google+ account
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                accounts: [],
                addingNewAccount: false,
                auth: {},
                errors: []
            }
        },
        methods: {
            updateAccounts(accounts) {
                if (accounts) {
                    this.$set(this, 'accounts', accounts);

                    this.$parent.$emit('ACCOUNTS_LOADED', accounts);
                }
                else {
                    this.$http.get('/api/google-plus/accounts').then(response => {
                        this.updateAccounts(response.body);
                    });
                }
            },
            addNewAccount() {
                this.addingNewAccount = true;
            },
            submitAccount() {
                this.$set(this, 'errors', {});

                this.$http.post('/api/google-plus/add-account', this.auth).then(response => {
                    this.updateAccounts(response.body);
                    this.back();
                }).catch(response => {
                    this.$set(this, 'errors', response.body);
                });
            },
            back() {
                this.addingNewAccount = false;
            },
            logout(account) {
                this.$http.post('/api/google-plus/logout-account', {account}).then(response => {
                    this.updateAccounts(response.body);
                });
            }
        },
        mounted() {
            this.updateAccounts();
        }
    }
</script>