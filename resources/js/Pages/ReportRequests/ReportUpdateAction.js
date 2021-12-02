import Report from "../Models/Report";

//@todo rename to ReportModelHelper
export default {
    props: {
        report: {
            type: Report,
            required: true,
        },

        routeName: {
            type: String,
            required: true,
        },
    },

    data() {
        return {
            reportModel: {}, //a clone of the report model prop
            savingReportModel: false,
        }
    },

    mounted() {
        this.cloneReportModel(this.report);
    },

    methods: {
        cloneReportModel(model) {
            this.reportModel = JSON.parse(JSON.stringify(model)); //manual and simple clone
        },

        saveReportModel(model) {
            let report = new Report.newInstanceFromItem(model);

            let url = route(this.routeName, {
                report: report.id,
            });

            this.savingReportModel = true;

            axios
                .put(url, report)
                .then(response => this.onSaveReportModelSuccess(response))
                .catch(error => this.reasonAlertShowError(error))
                .then(() => this.savingReportModel = false);
        },

        onSaveReportModelSuccess(response) {
            this.$emit('report-saved', response.data);
        },

        onSaveReportModelError(error) {
            alert('we experience some problems during the save operations, please contact the support center');
            console.error(error);
        },
    },

    watch: {
        report: {
            handler(reportProp) {
                this.cloneReportModel(reportProp);
            },
            deep: true
        },
    },
}
