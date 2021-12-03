<template>
    <div>
        <v-card-title>
            <h2>Subprocess of P#</h2>
            <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </v-card-title>

        <v-card-text>
            <v-data-table :headers="headers" :items="items" :search="search" dense :items-per-page="15">
                <template v-slot:item.actions="{ item }">
                    <v-btn icon>
                        <v-icon small @click="editSubprocess(item)">mdi-pencil</v-icon>
                    </v-btn>
                    <v-btn icon>
                        <v-icon small @click="confirmDeleteItem(item)">mdi-delete</v-icon>
                    </v-btn>
                </template>

                <template v-slot:footer.prepend>
                    <v-btn text @click="addSubprocess">Add Subprocess</v-btn>
                    <v-btn text>Download Subprocesses</v-btn>
                    <v-btn text @click="showIndicators">Show Indicators</v-btn>
                </template>
            </v-data-table>
        </v-card-text>

        <v-dialog
            v-model="editItemDialog"
            width="500px"
        >
            <v-card flat>
                <v-card-title>
                    <span class="text-h5">Subprocess edit form</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="editItem.code"
                                    label="Code"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="editItem.name"
                                    label="Name"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="closeEditItem"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        v-if="currentEditItemIndex === -1"
                        color="blue darken-1"
                        text
                        @click="storeSubprocess"
                    >
                        Add
                    </v-btn>
                    <v-btn
                        v-else
                        color="blue darken-1"
                        text
                        @click="updateSubprocess"
                    >
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deleteItemDialog" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="deleteItemDialog = !deleteItemDialog">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="deleteItem">OK</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    components: {},

    mixins: [],

    props: {

    },

    data() {
        return {
            title: '',

            search: '',

            loading: false,

            headers: [
                { text: 'Subprocess Code', value: 'code',},
                { text: 'Subprocess Name', value: 'name',},
                { text: 'Actions', value: 'actions', filterable: false, sortable: false,},
            ],

            items: [],

            editItemPrototype: {
                id: '',
                code: '',
                name: '',
            },

            editItem: {},

            currentEditItemIndex: -1,

            editItemDialog: false,

            deleteItemDialog: false,
        }
    },

    mounted() {
        this.fakeData();
    },

    methods: {
        fakeData() {
            this.items = [...Array(50).keys()].map(() => ({
                id: _.uniqueId('sgic_subprocess_'),
                code: faker.hacker.abbreviation(),
                name: faker.lorem.words(),
            }));
        },

        addSubprocess(){
            this.editItem = JSON.parse(JSON.stringify(this.editItemPrototype));
            this.editItem.id = _.uniqueId('sgic_subprocess_');
            this.editItemDialog = true;
            this.currentEditItemIndex = -1;
        },

        editSubprocess(item){
            this.editItem = Object.assign({}, item);
            this.currentEditItemIndex = this.items.indexOf(item);
            this.editItemDialog = true;
        },

        storeSubprocess() {
            this.items.unshift(JSON.parse(JSON.stringify(this.editItem)));
            this.editItem = JSON.parse(JSON.stringify(this.editItemPrototype));
            this.editItemDialog = false;
        },

        updateSubprocess(){
            this.items[this.currentEditItemIndex] = JSON.parse(JSON.stringify(this.editItem));
            this.editItem = JSON.parse(JSON.stringify(this.editItemPrototype));
            this.editItemDialog = false;
        },

        closeEditItem() {
            this.editItemDialog = false;
        },

        confirmDeleteItem(item) {
            this.currentEditItemIndex = this.items.indexOf(item);
            this.deleteItemDialog = true;
        },

        deleteItem() {
            this.deleteItemDialog = false;
        },

        showIndicators() {
            this.$emit('show-indicators');
        }
    },

    computed: {

    },

    watch: {

    },

    filters: {

    },

}
</script>

<style scoped>

</style>
