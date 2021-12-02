<template>
    <drawer-layout
        :drawer="drawer"
        title="Dashboard"
    >
        <v-card flat>
            <v-card-text>
                Tags content under design
            </v-card-text>
        </v-card>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";

export default {
    mixins: [DrawerLayoutDefaults],

    mounted() {

    },

    methods: {
        onClickDrawerItem(item) {

        }
    }
}
</script>

<style scoped>

</style>
