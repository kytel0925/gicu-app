<template>
    <v-card flat>
        <template v-if="current_activity === 'indicators'">
            <v-card-title>
                <h2>{{ title }}</h2>
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
                <template v-slot:item.value="{ item }">
                    <v-text-field v-if="item.flags.edit" v-model="item.flags.edit_value" dense style="margin: 0; height: 30px;" />
                    <span v-else>{{ item.value }}</span>
                </template>

                <template v-slot:item.process.name="{ value }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <div
                                class="text-truncate"
                                style="max-width: 200px"
                                v-bind="attrs"
                                v-on="on"
                            >{{ value }}</div>
                        </template>
                        <span>{{ value }}</span>
                    </v-tooltip>
                </template>

                <template v-slot:item.actions="{ item }">
                    <v-btn icon v-show="!item.flags.edit">
                        <v-icon small @click="editIndicator(item)">mdi-pencil</v-icon>
                    </v-btn>
                    <!-- edit buttons -->
                    <v-btn icon v-show="item.flags.edit">
                        <v-icon small @click="saveIndicator(item)">mdi-content-save</v-icon>
                    </v-btn>
                    <v-btn icon v-show="item.flags.edit">
                        <v-icon small @click="closeEditIndicator(item)">mdi-close</v-icon>
                    </v-btn>

                    <v-btn icon v-show="!item.flags.edit">
                        <v-icon small>mdi-database-refresh</v-icon>
                    </v-btn>
                    <v-btn icon v-show="!item.flags.edit">
                        <v-icon small>mdi-history</v-icon>
                    </v-btn>
                    <v-btn icon v-show="!item.flags.edit">
                        <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                </template>

                <template v-slot:footer.prepend>
                    <v-btn text>Add Indicator</v-btn>
                    <v-btn text>Download Indicators</v-btn>
                    <v-btn text @click="current_activity = 'subprocesses'">Sub Processes</v-btn>
                </template>
            </v-data-table>
            </v-card-text>
        </template>

        <!-- indicators / subprocesses (activities switchers) -->

        <template v-else-if="current_activity === 'subprocesses'">
            <sgic-subprocess-managments @show-indicators="current_activity = 'indicators'" />
        </template>

        <!-- dialogs -->
    </v-card>
</template>

<script>
import SgicSubprocessManagments from "./SgicSubprocessManagments";
export default {
    components: {SgicSubprocessManagments},

    mixins: [],

    props: {

    },

    data() {
        return {
            title: '',

            search: '',

            current_activity: 'indicators',

            loading: false,

            headers: [
                { text: 'Subprocess Code', value: 'sub_process.code',},
                { text: 'Subprocess Name', value: 'sub_process.name',},
                { text: 'Code', value: 'process.code',},
                { text: 'Description', value: 'process.name',},
                { text: 'Value (2021/2022)', value: 'value', filterable: false, sortable: false, },
                { text: 'Actions', value: 'actions', filterable: false, sortable: false,},
            ],

            items: [],
        }
    },

    mounted() {
        this.fakeData();
    },

    methods: {
        fakeData(){
            this.title = faker.lorem.words();

            this.items = [...Array(200).keys()].map(() => ({
                id: _.uniqueId('sgic_'),
                sub_process: {
                    code: faker.hacker.abbreviation(),
                    name: faker.lorem.words(),
                },
                process: {
                    code: faker.hacker.abbreviation(),
                    name: faker.lorem.paragraph(),
                },
                flags: {
                    edit: false,
                    edit_value: '',
                },
                value: _.sample([true, false])? Math.random() : '-',
            }));
       },

        editIndicator(item) {
            item.flags.edit = true;
            item.flags.edit_value = item.value;
        },

        saveIndicator(item) {
            item.value = item.flags.edit_value;

            this.closeEditIndicator(item);
        },

        closeEditIndicator(item) {
            item.flags.edit = false;
            item.flags.edit_value = '';
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
