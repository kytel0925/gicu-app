<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon>
                    <v-icon v-bind="attrs" v-on="on">mdi-pencil</v-icon>
                </v-btn>
            </template>
            <span>Edit Indicator</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon @click="dialogs.process_management = true">
                    <v-icon v-bind="attrs" v-on="on">mdi-playlist-plus</v-icon>
                </v-btn>
            </template>
            <span>Processes</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon>
                    <v-icon v-bind="attrs" v-on="on">mdi-cog</v-icon>
                </v-btn>
            </template>
            <span>Settings</span>
        </v-tooltip>

        <!-- dialogs (all the dialog is a component or only the vcard (content) -->
        <v-dialog v-model="dialogs.process_management" width="800px">
            <sgic-process-management @close="dialogs.process_management = false"  />
        </v-dialog>
    </div>
</template>

<script>
import SgicProcessManagement from "./SgicProcessManagement";
export default {
    components: {SgicProcessManagement},

    props: {

    },

    data: () => ({
        dialogs: {
            process_management: false,
        }
    }),

    methods: {

    },

    computed: {

    },
}
</script>

<style scoped>

</style>
