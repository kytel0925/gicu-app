<template>
    <drawer-layout
        :drawer="drawer"
        title="Dashboard"
    >
        <v-card flat>
            <v-card-text>
                Satisfaction blank page
            </v-card-text>
        </v-card>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";

export default {
    mixins: [DrawerLayoutDefaults],

    props: {

    },

    data() {
        return {

        }
    },

    mounted() {

    },

    methods: {

    },

    computed: {

    },

    watch: {

    },

    filters: {

    },
}
</script>

<style scoped>

</style>
