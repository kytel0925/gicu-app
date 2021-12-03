<template>
    <drawer-layout
        :drawer="drawer"
        title="SGIC Satisfaction / 2021 - 2022 / Here goes a wild title"
    >
        <template v-slot:app-bar-right>
            <!-- <sgic-indicator-management-actions /> -->
        </template>

        <v-card flat>
            <v-card-title>
                <h2>Indicators</h2>
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
                    <template v-slot:item.description="{ value }">
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

                    <template v-slot:item.observation="{ value }">
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
                        <v-btn icon @click="dialogs.indicator_form = true">
                            <v-icon small>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon small>mdi-database-refresh</v-icon>
                        </v-btn>
                        <v-btn icon @click="dialogs.indicator_history = true">
                            <v-icon small>mdi-history</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon small>mdi-delete</v-icon>
                        </v-btn>
                    </template>

                    <template v-slot:footer.prepend>
                        <v-btn text @click="dialogs.indicator_form = true">Add Indicator</v-btn>
                        <v-btn text>Download Indicators</v-btn>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>

        <!-- dialogs -->
        <v-dialog width="800px" v-if="dialogs.indicator_history" v-model="dialogs.indicator_history">
            <satisfaction-indicator-history @close="dialogs.indicator_history = !dialogs.indicator_history" />
        </v-dialog>

        <v-dialog width="600px" v-if="dialogs.indicator_form" v-model="dialogs.indicator_form">
            <satisfaction-indicator-form @close="dialogs.indicator_form = false"/>
        </v-dialog>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";
import SgicIndicatorHistory from "./Indicators/SgicIndicatorHistory";
import SatisfactionIndicatorForm from "./Indicators/SatisfactionIndicatorForm";
import SatisfactionIndicatorHistory from "./Indicators/SatisfactionIndicatorHistory";

export default {
    mixins: [DrawerLayoutDefaults],

    components: {SatisfactionIndicatorHistory, SatisfactionIndicatorForm, SgicIndicatorHistory},

    props: {

    },

    data() {
        return {
            title: '',

            search: '',

            loading: false,

            headers: [
                { text: 'Code', value: 'code',},
                { text: 'Code 2021', value: 'code_2021',},
                { text: 'Description', value: 'description',},
                { text: 'Source', value: 'source',},
                { text: 'Observation', value: 'observation',},
                { text: 'Value (2021/2022)', value: 'value', filterable: false, sortable: false, },
                { text: 'Actions', value: 'actions', filterable: false, sortable: false,},
            ],

            items: [],

            dialogs: {
                indicator_history: false,
                indicator_form: false,
            }
        }
    },

    mounted() {
        this.fakeData();
    },

    methods: {
        fakeData(){
            this.title = faker.lorem.words();

            this.items = [...Array(40).keys()].map(() => ({
                id: _.uniqueId('sgic_satisfaction_'),
                code: faker.hacker.abbreviation(),
                code_2021: faker.hacker.abbreviation(),
                description: faker.lorem.paragraph(),
                source: faker.lorem.words(),
                observation: faker.lorem.paragraph(),
                value: _.sample([true, false])? Math.random() : '-',
            }));
        },

        drawerDefaults() {
            this.setDrawerItemsCurrent(['indicators.satisfaction.index', 'indicators']);
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
