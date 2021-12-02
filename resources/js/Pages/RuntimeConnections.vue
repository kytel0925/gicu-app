<template>
    <drawer-layout
        :drawer="drawer"
        title="Dashboard"
    >
        <v-card>
            <v-card-text>
                Runtime connections under construction
            </v-card-text>
        </v-card>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";

export default {
    mixins: [DrawerLayoutDefaults],

    methods: {
        drawerDefaults(){
            let settings = this.drawer.items.firstOf(item => item.id === 'settings');

            settings.current = true;
        }
    }
}
</script>

<style scoped>

</style>
