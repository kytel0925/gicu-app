<template>
    <drawer-layout
        :drawer="drawer"
        title="Dashboard"
    >
        <v-card>
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
}
</script>

<style scoped>

</style>
