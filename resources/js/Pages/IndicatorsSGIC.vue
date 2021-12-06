<template>
    <!-- values can have multiple entries (by program) -->
    <drawer-layout
        :drawer="drawer"
        title="SGIC Indicators"
    >
        <v-card flat :loading="loading">
            <v-card-title>
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
                        <v-btn
                            color="blue darken-1"
                            text
                            @click="manage"
                        >
                            Manage
                        </v-btn>
                        <v-btn
                            color="green darken-1"
                            text
                        >
                            Download
                        </v-btn>
                        <v-btn
                            color="red darken-1"
                            text
                        >
                            Delete
                        </v-btn>
                    </template>

                    <template v-slot:footer.prepend>
                        <v-btn text @click="dialogs.indicator_sgic_form = true">New SGIC Indicators</v-btn>
                    </template>
                </v-data-table>
            </v-card-text>
        </v-card>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";

export default {
    mixins: [DrawerLayoutDefaults],

    props: {

    },

    data() {
        return {
            title: '',

            search: '',

            loading: false,

            headers: [
                { text: 'Period', value: 'period',},
                { text: 'Title', value: 'title',},
                { text: 'Status', value: 'status',},
                { text: 'Indicators', value: 'indicators', filterable: false,},
                { text: 'N/A', value: 'n_a', filterable: false, },
                { text: 'Empty', value: 'empty', filterable: false, },
                { text: 'Actions', value: 'actions', filterable: false, sortable: false,},
            ],

            items: [],

            dialogs: {
                indicator_sgic_form: false,
            }
        }
    },

    mounted() {
        this.fakeData();
    },

    methods: {
        fakeData() {
            this.items = [...Array(20).keys()].map(() => ({
                id: _.uniqueId('sgic_indicator_'),
                period: _.sample(['2009/2010', '2010/2011', '2011/2012', '2012/2013', '2013/2014', '2014/2015', '2016/2017', '2017/2018', '2018/2019', '2019/2020', '2020/2021']),
                title: faker.lorem.words(),
                status: _.sample(['Sent', 'Pending', 'In Revision', 'Ready to Sent']),
                indicators: 120,
                n_a: _.random(10, 20),
                empty: _.random(10, 20),
            }));
        },

        manage(){
            this.loading = true;
            window.location.href = route('indicators.sgic.manage', {
                sgicId: 1
            })
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
