<template>
    <v-card>
        <v-app-bar
            flat
            dense
        >
            <v-icon>mdi-chart-box-outline</v-icon>

            <v-toolbar-title>{{ title }}</v-toolbar-title>

            <v-spacer></v-spacer>

            <v-menu
                bottom
                left
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        icon
                        v-bind="attrs"
                        v-on="on"
                    >
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>

                <v-list dense>
                    <v-list-item
                        v-for="(item, i) in filteredActions"
                        :key="i"
                        link
                        @click="itemClick(item)"
                    >
                        <v-list-item-icon>
                            <v-icon>mdi-{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list>
            </v-menu>

        </v-app-bar>
        <v-card-text>
            <v-row v-show="show_complete_info">
                <v-col>Description</v-col>
                <v-col>{{ request.notes }}</v-col>
            </v-row>
            <v-row>
                <v-col>Requested</v-col>
                <v-col>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <span
                                v-bind="attrs"
                                v-on="on"
                            >{{ request.created_at | humanizeDate }}</span>
                        </template>
                        <span>{{ request.created_at | mysqlDateTime }}</span>
                    </v-tooltip>
                </v-col>
            </v-row>
            <v-row v-show="show_complete_info && tags.length > 0">
                <v-col>Tags</v-col>
                <v-col>
                    <v-chip
                        v-for="(tag, index) in tags"
                        class="ma-2"
                        color="indigo darken-3"
                        outlined
                        :key="index"
                    >
                        <v-icon left>
                            mdi-tag-text-outline
                        </v-icon>
                        {{ tag }}
                    </v-chip>
                </v-col>
            </v-row>
            <v-row v-if="in_progress">
                <v-col>Progress</v-col>
                <v-col>
                    <v-progress-linear
                        v-if="has_progress"
                        v-model="current_progress"
                        color="blue-grey"
                        height="25"
                    >
                        <template v-slot:default="{ value }">
                            <strong>{{ Math.ceil(value) }}%</strong>
                        </template>
                    </v-progress-linear>
                    <v-progress-linear
                        v-else
                        indeterminate
                        color="blue-grey"
                        height="25"
                    />
                </v-col>
            </v-row>
            <v-row v-else>
                <v-col>Completed</v-col>
                <v-col>
                    <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <span
                                v-bind="attrs"
                                v-on="on"
                            >{{ request.finished_at | humanizeDate }}</span>
                        </template>
                        <span>{{ request.finished_at | mysqlDateTime }}</span>
                    </v-tooltip>
                </v-col>
            </v-row>
        </v-card-text>

        <v-card-actions>
            <v-btn
                color="orange"
                outlined
                :href="links.results"
            >
                <v-icon left>mdi-archive-eye-outline</v-icon>
                Explore
            </v-btn>
            <v-btn
                color="blue"
                outlined
            >
                <v-icon left>mdi-file-download-outline</v-icon>
                Download
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import Report from "../Models/Report";
import DateHelper from "../../Common/DateHelper";

export default {
    mixins: [DateHelper],
    props: {
        report: {
            type: Report,
            required: true,
        },

        request: {
            type: Object,
            required: true,
        },
    },
    data: () => ({
        show_complete_info: false,
        actions: [
            {
                id: 'show_info',
                title: 'Show Details',
                icon: 'eye-outline',
                show: true,
                action: 'toggleInfo',
            },
            {
                id: 'hide_info',
                title: 'Hide Details',
                icon: 'eye-off-outline',
                show: true,
                action: 'toggleInfo',
            },
            {
                id: 'tags',
                title: 'Tags',
                icon: 'tag-multiple-outline',
                show: true,
            },
            {
                id: 'filters',
                title: 'Filters',
                icon: 'filter-outline',
                show: true,
            },
            {
                id: 'statistics',
                title: 'Statistics',
                icon: 'chart-bell-curve',
                show: true,
            },
            {
                id: 'export',
                title: 'Export',
                icon: 'database-export-outline',
                show: true,
            },
            {
                id: 'discard',
                title: 'Discard',
                icon: 'database-remove-outline',
                show: true,
            },
            {
                id: 'delete',
                title: 'Delete',
                icon: 'delete-outline',
                show: true,
            },
        ],
        links: {
            results: '',
        },
    }),

    mounted() {
        this.toggleInfoActions();
        this.setLinks();
    },

    methods: {
        itemClick(item) {
            if(item.action)
                this[item.action](item);
        },

        toggleInfo() {
            this.show_complete_info = !this.show_complete_info;
            this.toggleInfoActions();
        },

        toggleInfoActions() {
            this.actions.firstOf(action => action.id === 'show_info').show = !this.show_complete_info;
            this.actions.firstOf(action => action.id === 'hide_info').show = this.show_complete_info;
        },

        setLinks() {
            this.links.results = route('dashboard.report-results.index', {
              report: this.report.id,
              request: this.request.id,
            });
        },
    },

    computed: {
        filteredActions() {
            return this.actions.filter(action => action.show);
        },

        title() {
            return [this.request.title, this.request.code]
                .filter(item => !!item)
                .join(' | ');
        },

        tags() {
            return _.get(this.request, 'tags', []);
        },

        in_progress() {
            return !this.request.finished_at;
        },

        has_progress() {
            return this.current_progress !== null;
        },

        current_progress() {
            return _.get(this.request.statistics, 'progress.current', null);
        },
    },

    watch: {
        report: {
            handler() {
                this.setLinks();
            },
            deep: true,
        },
        request: {
            handler() {
                this.setLinks();
            },
            deep: true,
        },
    },

    filters: {

    },
}
</script>

<style scoped>

</style>
