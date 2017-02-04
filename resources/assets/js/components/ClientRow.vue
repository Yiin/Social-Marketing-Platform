<template>
    <tr>
        <td class="text-center">{{ client.id }}</td>
        <td>
            <input v-if="editing" v-model="client.name" type="text" class="form-control">
            <template v-else>{{ client.name }}</template>
        </td>
        <td>
            <input v-if="editing" v-model="client.email" type="text" class="form-control">
            <template v-else>{{ client.email }}</template>
        </td>
        <td>
            <input v-if="editing" v-model="client.notes" type="text" class="form-control">
            <template v-else>{{ client.notes }}</template>
        </td>
        <td class="text-right">
            <button v-if="editing" @click="save" class="btn btn-success btn-xs">
                Save <i class="fa fa-save"></i>
            </button>
            <button v-else @click="edit" type="button" class="btn btn-primary btn-xs">
                Edit <i class="fa fa-edit"></i>
            </button>
            <template v-if="confirm">
                <button @click="confirmRemove" class="btn btn-danger btn-fill btn-xs">
                    Confirm deletion <i class="fa fa-remove"></i>
                </button>
                <button @click="cancelRemove" class="btn btn-default btn-xs">
                    Cancel <i class="fa fa-ban"></i>
                </button>
            </template>
            <button v-else @click="remove" class="btn btn-danger btn-xs">
                Delete <i class="fa fa-remove"></i>
            </button>
        </td>
    </tr>
</template>

<style>
    td[contenteditable="true"] {
        border-left: 1px solid;
    }
</style>

<script>
    export default {
        props: ['client', 'onDelete', 'onUpdate'],
        data() {
            return {
                editing: false,
                confirm: false
            }
        },
        methods: {
            edit() {
                this.editing = true;
            },
            save() {
                this.editing = false;

                this.$http.put(`/client/${this.client.id}`, this.client).then(this.onUpdate);
            },
            remove() {
                this.confirm = true;
            },
            confirmRemove() {
                this.editing = false;
                this.confirm = false;
                this.$http.delete(`/client/${this.client.id}`).then(this.onDelete.bind(null, this.client.id));
            },
            cancelRemove() {
                this.confirm = false;
            }
        },
        mounted() {
            console.log('client-row', this.client);
        }
    }
</script>