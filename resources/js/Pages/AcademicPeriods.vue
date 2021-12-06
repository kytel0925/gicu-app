<template>
    <drawer-layout
        :drawer="drawer"
        title="Give me a title, please?"
    >
        <v-card flat>
            <v-card-text>
                Blank Page, Again?
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
