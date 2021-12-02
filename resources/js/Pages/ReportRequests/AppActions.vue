<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon @click="dialog_edit = !dialog_edit">
                    <v-icon v-bind="attrs" v-on="on">mdi-pencil-outline</v-icon>
                </v-btn>
            </template>
            <span>Edit</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon>
                    <v-icon v-bind="attrs" v-on="on">mdi-tag-plus</v-icon>
                </v-btn>
            </template>
            <span>Tags</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon>
                    <v-icon v-bind="attrs" v-on="on">mdi-chart-box-plus-outline</v-icon>
                </v-btn>
            </template>
            <span>New Report Request</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon @click="dialog_configure = !dialog_configure">
                    <v-icon v-bind="attrs" v-on="on">mdi-cog</v-icon>
                </v-btn>
            </template>
            <span>Settings</span>
            <!-- @link https://vuetifyjs.com/en/components/dialogs/#fullscreen -->
        </v-tooltip>

        <!-- dialogs (all the dialog is a component or only the vcard (content) -->
        <v-dialog persistent v-model="dialog_edit" max-width="800px">
            <edit-report
                :report="report"
                route-name="reports.update"
                @report-saved="onReportSave"
                @close="dialog_edit = !dialog_edit"
            />
        </v-dialog>

        <v-dialog v-model="dialog_configure" fullscreen hide-overlay transition="dialog-bottom-transition">
            <configure-report
                :report="report"
                route-name="reports.update"
                @report-saved="onReportSave"
                @close="dialog_configure = !dialog_configure"
            />
        </v-dialog>
    </div>
</template>

<script>
import Report from "../Models/Report";
import EditReport from "./EditReport";
import ConfigureReport from "./ConfigureReport";

export default {
    components: {ConfigureReport, EditReport},
    props: {
        report: {
            type: Report,
            required: true,
        },
    },

    data: () => ({
        dialog_edit: false,
        dialog_configure: false,
    }),

    methods: {
        onReportSave(report){
            this.$emit('report-updated', report);

            this.dialog_edit = false;
            this.dialog_configure = false;
        },
    },

    computed: {
    },
}
</script>

<style scoped>

</style>
