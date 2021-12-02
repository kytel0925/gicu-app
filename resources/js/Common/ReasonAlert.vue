<template>
    <v-dialog
        v-model="show"
        :width="width"
        :max-width="maxWidth"
    >
        <v-alert
            border="left"
            close-text="Close"
            :type="type"
        >
            {{ reasonText }}
        </v-alert>
    </v-dialog>
</template>

<script>
export default {
    props: {
        width: {
            default: '500px',
        },
        maxWidth: {
            default: '800px',
        },
        reason: {
            type: String,
            require: true,
        },
        type: {
            type: String,
            default: 'warning',
        }
    },

    data: () => ({
        show: false,
        message: '',
    }),
    methods: {

    },
    watch: {
        reason(reason) {
            if(reason) {
                this.show = true;
                this.message = reason;
            }
        },
        show(value){
            if(!value)
                this.message = '';
        },
    },
    computed: {
        reasonText() {
            return this.reason;
        }
    }
}
</script>

<style scoped>

</style>
