<template>
    <drawer-layout
        :drawer="drawer"
        title="SGIC Indicators / 2021 - 2022 / Here goes a wild title"
    >
        <template v-slot:app-bar-right>
            <sgic-indicator-management-actions />
        </template>

        <v-card flat>
            <v-tabs
                center-active
                color="primary"
            >
                <v-tab>P01</v-tab>
                <v-tab>P02</v-tab>
                <v-tab>P03</v-tab>
                <v-tab>P04</v-tab>
                <v-tab>P05</v-tab>
                <v-tab>P06</v-tab>
                <v-tab>P07</v-tab>

                <v-tab-item>
                    <sgic-indicator-process-management />
                </v-tab-item>
                <v-tab-item>
                    <sgic-indicator-process-management />
                </v-tab-item>
                <v-tab-item>
                    <sgic-indicator-process-management />
                </v-tab-item>
                <v-tab-item>
                    <sgic-indicator-process-management />
                </v-tab-item>
                <v-tab-item>
                    <sgic-indicator-process-management />
                </v-tab-item>
                <v-tab-item>
                    <sgic-indicator-process-management />
                </v-tab-item>
                <v-tab-item>
                    <sgic-indicator-process-management />
                </v-tab-item>
            </v-tabs>
        </v-card>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";
import SgicAppActions from "./Indicators/SgicAppActions";
import SgicIndicatorProcessManagement from "./Indicators/SgicIndicatorProcessManagement";
import SgicIndicatorManagementActions from "./Indicators/SgicIndicatorManagementActions";

export default {
    components: {SgicIndicatorManagementActions, SgicIndicatorProcessManagement, SgicAppActions},
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
        drawerDefaults() {
            this.setDrawerItemsCurrent(['indicators.sgic.index', 'indicators']);
        }
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
