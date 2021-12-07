<template>
    <v-card :loading="loading">
        <v-card-title class="text-h5">{{ message }}</v-card-title>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="ok" :disabled="loading">OK</v-btn>
            <v-btn color="red darken-1" text @click="cancel" :disabled="loading">Cancel</v-btn>
            <v-spacer></v-spacer>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    props: {
        message: {
            type: String,
            required: true,
        },

        loading: {
            type: Boolean,
            default: false,
        },
    },

    methods: {
        ok() {
            this.$emit('ok');
        },

        cancel() {
            this.$emit('cancel');
        },
    }
}
</script>

<style scoped>

</style>
