<template>
    <drawer-layout
        :drawer="drawer"
        :title="title"
    >
        <report-results-data-table
            v-if="report.id && request.id"
            :report="report"
            :request="request"
            route-name="report-results.search"
        />
    </drawer-layout>
</template>

<script>
import DrawerLayout from "../Layouts/DrawerLayout";
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";
import Report from "./Models/Report";
import ReportResultsDataTable from "./ReportResults/ReportResultsDataTable";
import ReportRequest from "./Models/ReportRequest";

export default {
    components: {ReportResultsDataTable, DrawerLayout},
    mixins: [DrawerLayoutDefaults],

    data: () => ({
        report: new Report(),
        request: new ReportRequest(),
    }),

    methods: {
        drawerDefaults(){
        },
    },

    mounted() {
        this.report = Report.newInstanceFromItem(_.get(this.$page, 'props.report'));
        this.request = ReportRequest.newInstanceFromItem(_.get(this.$page, 'props.request'));

        let reports = this.drawer.items.firstOf(item => item.id === 'dashboard.report-requests.index');
        let report = reports.items.firstOf(item => item.id === `report-requests.${this.report.id}`);

        reports.current = report.current = true;

    },

    computed: {
        title() {
            return this.report.name || 'Report Request';
        }
    }
}
</script>

<style scoped>

</style>
