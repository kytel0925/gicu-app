<template>
    <drawer-layout
        :drawer="drawer"
        :title="title"
    >
        <template v-slot:app-bar-right>
            <app-actions :report="report" @report-updated="onReportUpdate" />
        </template>

        <v-card flat :loading="loading">
            <v-card-text>
                <v-row>
                    <v-col>

                    </v-col>
                    <v-col>

                    </v-col>
                    <v-col>
                        <v-menu
                            v-model="show_date_range"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="auto"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field
                                    v-model="dateRangeText"
                                    label="Requested At"
                                    append-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                ></v-text-field>
                            </template>
                            <v-date-picker
                                v-model="date_range"
                                range
                                scrollable
                                @input="onDateRange"
                            ></v-date-picker>
                        </v-menu>

                    </v-col>
                    <v-col>
                        <v-text-field
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Title, Code, Tags"
                            single-line
                            hide-details
                            @input="filterBySearch"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <!-- each v-row in reportRequests  -->
                <v-row v-for="(request, index) in filterRequests" :key="index">
                    <v-col>
                         <report-request-card :report="report" :request="request" />
                    </v-col>
                </v-row>

                <v-alert type="warning" v-show="filterRequests.length <= 0 && !loading">No Report Request to be displayed, clear the filters or reload the app</v-alert>
            </v-card-text>
        </v-card>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";
import AppActions from "./ReportRequests/AppActions";
import ReportRequestCard from "./ReportRequests/ReportRequestCard";
import Report from "./Models/Report";
import DateHelper from "../Common/DateHelper";

export default {
    components: {ReportRequestCard, AppActions},
    mixins: [DrawerLayoutDefaults, DateHelper],

    data: () => ({
        loading: false,
        show_date_range: false,
        search: '',
        date_range: [],
        report: new Report(),
        requests: [],
    }),

    mounted() {
        this.report = Report.newInstanceFromItem(_.get(this.$page, 'props.report'));

        this.fetchRequests();
    },

    methods: {
        drawerDefaults(){
            let reports = this.drawer.items.firstOf(item => item.id === 'dashboard.report-requests.index');

            reports.current = true;
        },

        fetchRequests() {
            let url = route('report-requests.search', {
                report: this.report.id
            });

            this.loading = true;

            axios.get(url)
                .then(response => this.loadRequests(response.data))
                .catch(error => console.error(error))
                .then(() => this.loading = false);
        },

        loadRequests(results) {
            this.requests = results.items.map(item => {
                let haystack = [item.title, item.code];

                item.appFlags = {
                    isDateRangeValid: true,
                    isSearchValid: true,
                };

                haystack.push(..._.get(item, 'tags', []));
                haystack = haystack.map(item => item.toLowerCase()); //case insensitive support

                item.createdAt = this.dayjs(item.created_at);
                item.searchHaystack = haystack;

                return item;
            });

            this.filterByDateRange();
            this.filterBySearch();
        },

        onDateRange() {
            if(this.date_range.length > 0){
                if(this.date_range.length === 2){
                    this.show_date_range = false;
                    this.date_range.sort();
                }
            }

            this.filterByDateRange();
        },

        filterByDateRange() {
            if(this.date_range.length > 0){
                let min = this.dayjs(this.date_range[0]), //by default is 00:00:00
                    max = this.dayjs(`${this.date_range[1]} 23:59:59`), //end of the day to be a real between
                    expected = this.dayjs(this.date_range[0]);

                    if(this.date_range.length === 2){
                        this.requests.forEach(item =>
                            item.appFlags.isDateRangeValid = item.createdAt.unix() >= min.unix()
                                && item.createdAt.unix() <= max.unix()
                        );
                    }
                    else if(this.date_range.length === 1) {
                        this.requests.forEach(item =>
                            item.appFlags.isDateRangeValid =
                                item.createdAt.format('YYYY-MM-DD') === expected.format('YYYY-MM-DD')
                        );
                    }
            }
            else{
                this.requests.forEach(item => item.appFlags.isDateRangeValid = true);
            }
        },

        filterBySearch() {
            let needles = this.search.split(',')
                .map(needle => needle.trim())
                .map(needle => needle.toLowerCase()) //case insensitive support
                .filter(needle => !!needle);

            if(needles.length <= 0){
                this.requests.forEach(item => item.appFlags.isSearchValid = true);

                return;
            }

            //every inverse of some
            this.requests.forEach(
                request => request.appFlags.isSearchValid = needles.some(
                    needle => request.searchHaystack.some(
                        haystack => haystack.includes(needle)
                    )
                )
            );
        },

        onReportUpdate(report) {
            //@todo the save operation should go in here, with that we save a los of props and disperse save methods, the report argument should come with all set for the save operations
            //@todo but how we handle the callback and loading times (with another object?)
            this.report = Report.newInstanceFromItem(report);
        },
    },

    computed: {
        title() {
            return this.report.name || 'Report Request';
        },

        dateRangeText() {
            return this.date_range.join(', ');
        },

        filterRequests() {
            //all flags must be true
            return this.requests.filter(
                request => _.values(request.appFlags)
                    .every(flag => flag)
            );
        }
    },
    watch: {

    }
}
</script>

<style scoped>

</style>
