<template>
    <v-tabs>
        <v-tab>
            <v-icon left>
                mdi-table-column
            </v-icon>
            Table Columns
        </v-tab>
        <v-tab>
            <v-icon left>
                mdi-form-select
            </v-icon>
            JSON CRUD
            <v-btn right icon>
                <v-icon>mdi-plus</v-icon>
            </v-btn>
            <v-btn right icon>
                <v-icon>mdi-file-undo</v-icon>
            </v-btn>
        </v-tab>
        <!-- columns configuration -->
        <v-tab-item>
            <v-card flat>
                <v-data-table :headers="headers" :items="tableColumnItems" disable-pagination disable-filtering disable-sort hide-default-footer>
                    <template v-slot:body="props">
                        <draggable
                            :list="props.items"
                            tag="tbody"
                        >
                            <tr
                                v-for="(column, index) in props.items"
                                :key="index"
                            >
                                <td>
                                    {{ column.text }}
                                </td>
                                <td>
                                    {{ column.value }}
                                </td>
                                <td>
                                    {{ column.align | alignText }}
                                </td>
                                <td>
                                    <v-switch v-model="column.sortable" />
                                </td>
                                <td>
                                    <v-btn icon @click="loadColumnConfigurationModal(index)">
                                        <v-icon>mdi-pencil-outline</v-icon>
                                    </v-btn>
                                    <!--<v-btn icon>
                                        <v-icon>mdi-cog-outline</v-icon>
                                    </v-btn>-->
                                    <v-btn icon @click="deleteColumnConfigurationModal(index)">
                                        <v-icon>mdi-trash-can-outline</v-icon>
                                    </v-btn>
                                </td>
                            </tr>
                        </draggable>
                    </template>
                </v-data-table>

                <v-alert type="warning" v-show="tableColumnItems.length <= 0">No columns configured for the displayed results</v-alert>

                <v-dialog v-model="modals.column_configuration" persistent width="800px">
                    <v-card>
                        <v-card-title>
                            <span class="text-h5">{{ currentItem === null? 'Add Column Configuration' : 'Edit Column Configuration' }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col>
                                        <v-text-field
                                            v-model="columnItem.text"
                                            label="Title"
                                            dense
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <v-text-field
                                            v-model="columnItem.value"
                                            label="Value"
                                            dense
                                        ></v-text-field>
                                    </v-col>
                                    <v-col>
                                        <v-switch v-model="columnItem.sortable" label="Sortable" />
                                    </v-col>
                                    <v-col>
                                        <v-select v-model="columnItem.align" :items="alignItems" label="Align" />
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="closeColumnConfiguration"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                color="blue darken-1"
                                text
                                @click="saveColumnConfiguration"
                            >
                                Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-card-actions>
                    <v-btn right @click="loadColumnConfigurationModal(null)">
                        <v-icon>mdi-plus</v-icon>
                        Add
                    </v-btn>
                    <v-btn right @click="cloneTableColumns(tableColumns)">
                        <v-icon>mdi-file-undo</v-icon>
                        Reset
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-tab-item>
        <!-- form configuration -->
        <v-tab-item>
            Form Configuration
        </v-tab-item>
    </v-tabs>
</template>

<script>
import draggable from 'vuedraggable'

const columnItem = {
    text: '',
    value: '',
    sortable: false,
    align: 'start',
    options: {},
};

const alignItems = [
    {value: 'start', text: 'Left'},
    {value: 'center', text: 'Center'},
    {value: 'end', text: 'Right'},
];

export default {
    components: {draggable},

    props: {
        tableColumns: {
            type: Array
        },
    },

    data() {
        return {
            modals: {
                column_configuration: false,
            },
            model: {},
            currentItem: null,
            columnItem: columnItem,
            headers: [
                { text: 'Title', value: 'text',},
                { text: 'Value', value: 'value', },
                { text: 'Align', value: 'align', },
                { text: 'Sortable', value: 'sortable', },
                { text: 'Actions', value: 'actions', },
            ],
            tableColumnItems: [],
            alignItems: alignItems,
        }
    },

    mounted() {
        this.cloneTableColumns(this.tableColumns);
    },

    methods: {
        cloneTableColumns(columns){
            this.tableColumnItems = typeof columns !== 'undefined' && columns && columns.length > 0?
                JSON.parse(JSON.stringify(columns)) :
                [];
        },

        loadColumnConfigurationModal(columnItemIndex){
            this.modals.column_configuration = true;
            let column = _.get(this.tableColumnItems, columnItemIndex);

            if(columnItemIndex === null || !column){
                this.columnItem = JSON.parse(JSON.stringify(columnItem));
                this.currentItem = null;
            }
            else{
                this.columnItem = JSON.parse(JSON.stringify(column));
                this.currentItem = columnItemIndex;
            }
        },

        closeColumnConfiguration() {
            this.currentItem = null;
            this.modals.column_configuration = false;
            this.columnItem = columnItem;
        },

        saveColumnConfiguration() {
            if(this.currentItem === null)
                this.tableColumnItems.push(this.columnItem);
            else
                this.tableColumnItems[this.currentItem] = this.columnItem;

            this.closeColumnConfiguration();

            this.$emit('save-columns', this.tableColumnItems);
        },

        deleteColumnConfigurationModal(index) {
            if(confirm('Are you sure?'))
                this.tableColumnItems.splice(index, 1)
        },
    },

    watch: {
        tableColumns: {
            handler(columns){
                this.cloneTableColumns(columns);
            },
            deep: true,
        },
    },

    filters: {
        alignText(value) {
            return _.get(alignItems.firstOf(item => item.value === value), 'text', '-');
        },
    },
}

//json-crud :payload :reserve-keys key-value-pair (add, edit, delete)
</script>

<style scoped>

</style>
