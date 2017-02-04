<template>
    <table class="table">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th>Client Name</th>
            <th>Email</th>
            <th>Notes (optional)</th>
            <th class="text-right">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr is="client-row" v-for="client in clients" :client="client" :onDelete="onDelete"></tr>
        <tr>
            <td></td>
            <td><input type="text" class="form-control" :class="{ error: errors.name }" placeholder="Name" v-model="client.name"></td>
            <td><input type="text" class="form-control" :class="{ error: errors.email }" placeholder="Email" v-model="client.email"></td>
            <td><input type="text" class="form-control" :class="{ error: errors.notes }" placeholder="Notes (optional)" v-model="client.notes"></td>
            <td class="text-right">
                <button @click="create" class="btn btn-primary btn-simple-btn-xs">
                    Create client
                </button>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: [
            'data'
        ],
        data() {
            return {
                clients: [],
                client: {},
                errors: {}
            }
        },
        methods: {
            create() {
                this.errors = {};

                this.$http.post('/client', this.client).then(response => {
                    this.client = {};
                    this.$set(this, 'clients', response.body);
                }).catch(response => {
                    this.$set(this, 'errors', response.body);
                });
            },
            onDelete(id) {
                this.$set(this, 'clients', this.clients.filter(client => client.id !== id));
            }
        },
        mounted() {
            this.clients = JSON.parse(this.data);
        }
    }
</script>