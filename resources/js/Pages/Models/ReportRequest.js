import Report from "./Report";

class ReportRequest{
    constructor(id, report_id, user, code, title, notes, filters, data_id, statistics, extra, created_at, finished_at, failed_at, expires_at, data_cleaned_at) {
        this.id = id;
        typeof report_id === 'object'?
            this.setReport(report_id) :
            this.setReportId(report_id);
        this.user = user;
        this.code = code;
        this.title = title;
        this.notes = notes;
        this.data_id = data_id;
        this.filter = filters || {};
        this.statistics = statistics || {};
        this.extra = extra || {};
        this.created_at = created_at;
        this.finished_at = finished_at;
        this.failed_at = failed_at;
        this.expires_at = expires_at;
        this.data_cleaned_at = data_cleaned_at;
    }

    setReportId(report_id){
        this.report_id = report_id;
    }

    setReport(report){
        if(typeof report === 'object'){
            if(!(report instanceof Report))
                report = Report.newInstanceFromItem(report);

            this.report = report;
            this.setReportId(report.id);
        }
    }

    static newInstanceFromItem(item){
        return new ReportRequest(
            _.get(item, 'id'),
            _.get(item, 'report_id'),
            _.get(item, 'user'),
            _.get(item, 'code'),
            _.get(item, 'title'),
            _.get(item, 'notes'),
            _.get(item, 'data_id'),
            _.get(item, 'statistics'),
            _.get(item, 'created_at'),
            _.get(item, 'finished_at'),
            _.get(item, 'failed_at'),
            _.get(item, 'expires_at'),
            _.get(item, 'data_cleaned_at'),
        );
    }
}

export default ReportRequest;
