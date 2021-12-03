<template>
    <v-card flat>
        <v-card-title>
            Process Management
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
            <v-data-table :headers="headers" :items="items" :search="search" dense :items-per-page="10">
                <template v-slot:item.actions="{ item }">
                    <v-btn icon>
                        <v-icon small @click="dialogs.process_form = true">mdi-pencil</v-icon>
                    </v-btn>
                    <v-btn icon>
                        <v-icon small>mdi-database-refresh</v-icon>
                    </v-btn>
                    <v-btn icon>
                        <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                </template>

                <template v-slot:footer.prepend>
                    <v-btn text @click="dialogs.process_form = true">New Process</v-btn>
                    <v-btn text @click="closeManagement">Close</v-btn>
                </template>
            </v-data-table>
        </v-card-text>
        <v-dialog v-model="dialogs.process_form" width="800px">
            <sgic-process-form @close="dialogs.process_form = false" />
        </v-dialog>
    </v-card>
</template>

<script>
import SgicProcessForm from "./SgicProcessForm";
export default {
    components: {SgicProcessForm},

    mixins: [],

    props: {

    },

    data() {
        return {
            title: '',

            search: '',

            loading: false,

            headers: [
                { text: 'Code', value: 'code',},
                { text: 'Name', value: 'title',},
                { text: 'Subprocess', value: 'indicators', filterable: false,},
                { text: 'Indicators', value: 'n_a', filterable: false, },
                { text: 'Actions', value: 'actions', filterable: false, sortable: false,},
            ],

            items: [],

            dialogs: {
                process_form: false,
            }
        }
    },

    mounted() {
        this.fakeData();
    },

    methods: {
        fakeData() {
            this.items = [...Array(7).keys()].map(index => ({
                id: _.uniqueId('sgic_process_'),
                code: `P0${++index}`,
                title: faker.lorem.words(),
                indicators: _.random(15, 20),
                n_a: _.random(2, 5),
                empty: _.random(2, 5),
            }));
        },

        closeManagement() {
            this.$emit('close');
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
