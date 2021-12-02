<template>
    <v-card>
        <v-toolbar
            dark
            color="primary"
        >
            <v-btn
                icon
                dark
                @click="closeConfiguration"
            >
                <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Report Settings: {{ report.name }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn
                    dark
                    text
                    @click="saveReportModel(reportModel)"
                    :disabled="savingReportModel"
                >
                    {{ savingReportModel? 'Saving' : 'Save' }}
                </v-btn>
                <v-btn
                    dark
                    text
                    @click="cloneReportModel(report)"
                    :disabled="savingReportModel"
                >
                    Reset
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-tabs vertical>
            <v-tab style="justify-content: left">
                <v-icon left>
                    mdi-connection
                </v-icon>
                Runtime Connections
            </v-tab>
            <v-tab style="justify-content: left">
                <v-icon left>
                    mdi-filter-outline
                </v-icon>
                Filters
            </v-tab>
            <v-tab style="justify-content: left">
                <v-icon left>
                    mdi-archive-eye-outline
                </v-icon>
                Explore
            </v-tab>
            <v-tab style="justify-content: left">
                <v-icon left>
                    mdi-file-export
                </v-icon>
                Templates
            </v-tab>
            <!--
            <v-tab>
                <v-icon left>
                    mdi-table-cog
                </v-icon>
                Custom
            </v-tab>
            -->

            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        Runtime Connections
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        Filters
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <!-- view table -->
            <v-tab-item>
                <configure-results-explore
                    :table-columns="exploreDataTableColumns"
                    @save-columns="onExploreDataColumnsSave"
                />
            </v-tab-item>
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        Templates
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <!--
            <v-tab-item>
                <v-card flat>
                    <v-card-text>
                        Custom
                    </v-card-text>
                </v-card>
            </v-tab-item>
            -->
        </v-tabs>
        <reason-alert :type="reasonAlert.type" :reason="reasonAlert.message" />
    </v-card>
</template>

<script>
import ConfigureResultsExplore from "./ConfigureResultsExplore";
import ReportUpdateAction from "./ReportUpdateAction";
import ReasonAlertHelper from "../../Common/ReasonAlertHelper";
import ReasonAlert from "../../Common/ReasonAlert";

export default {
    components: {ConfigureResultsExplore, ReasonAlert},

    mixins: [ReportUpdateAction, ReasonAlertHelper],

    data () {
        return {

        }
    },

    methods: {
        closeConfiguration() {
            this.$emit('close');
        },

        onExploreDataColumnsSave(columns) {
            _.set(this.reportModel, 'options.explore.data_table_headers', columns)
        },

        onSaveReportModelError(error) {
            this.reasonAlertShowError(error);
        },
    },

    computed: {
        /**
         * Columns used in the view data table component for results presentation
         *
         * @returns {*}
         */
        exploreDataTableColumns() {
            return _.get(this.reportModel,'options.explore.data_table_headers', []);
        }
    }
}
</script>

<style scoped>

</style>
