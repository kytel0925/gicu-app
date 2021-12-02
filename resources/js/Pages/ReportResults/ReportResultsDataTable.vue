<template>
    <v-card flat>
        <v-card-title>
            <slot name="title-left"></slot>
            {{ title }}
            <v-spacer></v-spacer>
            <slot name="title-right">
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    :label="searchText"
                    :readonly="searchDisabled"
                    :disabled="headers.length <= 0"
                    single-line
                    hide-details
                ></v-text-field>
                <v-btn icon>
                    <v-icon>mdi-filter-outline</v-icon>
                </v-btn>
            </slot>
        </v-card-title>

        <v-card-text v-if="headers.length > 0">
            <v-row v-if="pagination.show">
                <v-col></v-col>
                <v-col class="text-center" cols="8">
                    <v-pagination
                        v-model="pagination.page"
                        :length="pagination.pages"
                        :disabled="pagination.disabled"
                    ></v-pagination>
                </v-col>
                <v-col class="text-right">{{ items.length}} of {{ pagination.total }}</v-col>
            </v-row>

            <v-row>
                <v-col>
                    <v-data-table
                        :headers="headersWithActions"
                        :items="items"
                        :items-per-page="pagination.max"
                        :disable-sort="loading.value"
                        :loading="loading.value"
                        :loading-text="loading.message"
                        :search="search"
                        :sort-by.sync="sort.by"
                        :sort-desc.sync="sort.desc"
                        :no-data-text="noDataText"
                        :no-results-text="noResultsText"
                        class="elevation-1"
                        hide-default-footer
                        dense
                    >
                        <template v-slot:item.actions="{ item }">
                            <v-btn icon @click="editItem(item)">
                                <v-icon
                                    small
                                    class="mr-2"
                                >
                                    mdi-pencil
                                </v-icon>
                            </v-btn>
                            <v-btn icon @click="deleteItem(item)">
                                <v-icon
                                    small
                                    class="mr-2"
                                >
                                    mdi-delete
                                </v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-col>
            </v-row>

            <v-row v-if="pagination.show">
                <v-col></v-col>
                <v-col class="text-center" cols="8">
                    <v-pagination
                        v-model="pagination.page"
                        :length="pagination.pages"
                        :disabled="pagination.disabled"
                    ></v-pagination>
                </v-col>
                <v-col class="text-right">Items: {{ items.length}} of {{ pagination.total }}</v-col>
            </v-row>
        </v-card-text>

        <v-card-text v-else>
            <v-alert
                border="bottom"
                colored-border
                type="warning"
                elevation="2"
            >
                The provided report doesn't have the view headers configured, please use the configuration option and add some headers
            </v-alert>
        </v-card-text>

        <v-dialog v-model="edit_item" width="800px" max-width="800px">
            <!--<edit-item-form
                v-if="edit_item"
                title="Edit Result Item"
                :item="current_item"
                :save-route="itemSaveRoute"
                route-method="put"
                @saved="onItemSave"
                @close="onItemSaveClose"
            />-->
            <json-crud
                v-if="edit_item"
                v-model="current_item"
                :save-route="itemSaveRoute"
                route-method="put"
                @close="onItemSaveClose"
                show-close-button
            />
        </v-dialog>
    </v-card>
</template>

<script>
import Report from "../Models/Report";
import axios from "axios";
import ReportRequest from "../Models/ReportRequest";
import EditItemForm from "../../Common/EditItemForm";
import JsonCrud from "../../Common/JsonCrud";

export default {
    components: {JsonCrud, EditItemForm},
    props: {
        report: {
            type: Report,
            required: true,
        },
        request: {
            type: ReportRequest,
            required: true,
        },
        hideSearchInput: {
            type: Boolean,
            default: false,
        },
        hidePagination: {
            type: Boolean,
            default: false,
        },
        noDataText: {
            type: String,
            default: 'no data retrieved',
        },
        noResultsText: {
            type: String,
            default: 'no matches found',
        },
        searchText: {
            type: String,
            default: 'Search in Displayed Results',
        },
        routeName: {
            type: String,
            required: true,
        },
    },
    data: () => ({
        search: '',
        loading: {
            value: false,
            message: 'Fetching...',
        },
        edit_item: false,
        current_item: {},
        items: [],
        pagination: {
            disabled: false,
            pages: null,
            page: 1, //prop?
            max: 100, //prop?
            total: null,
            offset: null,
        },
        sort: {
            by: '',
            desc: false,
        },

        dialog: false,
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
        defaultItem: {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
    }),

    created () {
        //auto fetching should be in here
        this.fetchItems();
    },

    methods: {
        fetchItems(){
            let url = route(this.routeName, {
                report: this.report.id,
                request: this.request.id,
                page: this.pagination.page,
                max: this.pagination.max,
            });
            let params = {};

            this.setLoading(true)
            this.items = [];

            //order
            if(this.sort.by){
                if(typeof this.sort.by === 'string' && this.sort.by.trim()){
                    params[`sort.by[${this.sort.by}]`] = this.sort.desc === true? 'desc' : 'asc';
                }
                else if(this.sort.by.length > 0){
                    _.each(this.sort.by, (by, index) =>
                        params[`sort.by[${by}]`] = _.get(this.sort.desc, index, false) === true? 'desc' : 'asc'
                    );
                }
            }

            axios
                .get(url, {params})
                .then(response => this.loadFetchedItems(response.data))
                .catch(error => console.log(error))
                .then(() => this.setLoading(false));
        },

        loadFetchedItems(results) {
            this.pagination.pages = results.pages;
            this.pagination.total = results.total;
            this.pagination.offset = results.offset;
            this.pagination.show = results.items.length > 0 && results.pages > 1;

            this.items = results.items;
        },

        editItem(item) {
            this.edit_item = true;
            this.current_item = item;
        },

        onItemSave(updatedItem) {
            this.onItemSaveClose();

            let item = this.items.firstOf(item => item.id === updatedItem.id);

            if(item){
                item = _.merge(item, updatedItem);
            }
            else{
                this.fetchItems();
            }
        },

        onItemSaveClose(item) {
            this.edit_item = false;
        },

        deleteItem(item) {
            if(!confirm('Are you sure you want to delete this item'))
                return;

            let url = route('report-results.delete', {
                report: this.report.id,
                request: this.request.id,
                result: _.get(item, '_id.$oid', item.id),
            });

            this.setLoading(true);

            axios
                .delete(url, {data: item})
                .then(() => this.fetchItems())
                .catch(error => console.log(error))
                .then(() => this.setLoading(false));
        },

        setLoading(value) {
            this.pagination.disabled = this.loading.value = value;
        }
    },

    computed: {
        title () {
            return 'Report Results';
        },

        searchDisabled() {
            return this.pagination.disabled || this.items.length <= 0;
        },

        headers() {
            return _.get(this.report.options, 'explore.data_table_headers', []);
        },

        headersWithActions() {
            if(this.headers.length > 0){
                let newHeaders = [...this.headers];

                newHeaders.push({
                    text: 'Actions',
                    align: 'start',
                    sortable: false,
                    value: 'actions',
                });

                return newHeaders;
            }

            return this.headers;
        },

        itemSaveRoute() {
            return route('report-results.update', {
                report: this.report.id,
                request: this.request.id,
                result: _.get(this.current_item, '_id.$oid', this.current_item.id),
            });
        }
    },

    watch: {
        'pagination.page': function(newPage, oldPage) {
            if(newPage !== oldPage)
                this.fetchItems();
        },

        'pagination.max': function(newMax, oldMax) {
            if(newMax !== oldMax) {
                //to prevent offset bugs
                if(this.pagination.page > 1){
                    this.pagination.page = 1; //the 'pagination.page' watch will fire the fetch
                }
                else{
                    this.fetchItems();
                }
            }
        },

        sort: {
            handler(newSort) {
                if((typeof newSort.by === 'string' && newSort.by !== '') || (typeof newSort.by === 'object' && newSort.by.length > 0 && newSort.desc && newSort.desc.length > 0)) {
                    this.fetchItems();
                }
                else if((typeof newSort.by === 'undefined' || newSort.by.length === 0) && (typeof newSort.desc === 'undefined' || newSort.desc.length === 0)){
                    this.fetchItems();
                }
            },
            deep: true,
        },
    },
}
</script>

<style scoped>

</style>
