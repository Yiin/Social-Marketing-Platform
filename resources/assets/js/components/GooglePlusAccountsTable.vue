<template>
    <div class="table-responsive">

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
                <td>{{ account.name }}</td>
                <td class="text-right">
                    <button type="button" class="btn btn-danger btn-simple btn-xs">
                        Log Out <i class="fa fa-times"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                accounts: []
            }
        },
        methods: {
            updateAccounts() {
                this.$http.get('/api/google-plus-accounts').then(response => {
                    this.$set(this, 'accounts', response.body);
                });
            }
        },
        mounted() {
            this.updateAccounts();
        }
    }
</script>