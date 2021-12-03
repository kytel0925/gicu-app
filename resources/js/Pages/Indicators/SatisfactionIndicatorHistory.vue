<template>
    <v-card flat>
        <v-card-title>Satisfaction Indicator History / ####</v-card-title>
        <v-card-text>
            <v-row>
                <v-col>Description:</v-col>
                <v-col>-</v-col>
                <v-col>Observation:</v-col>
                <v-col>-</v-col>
            </v-row>
            <v-row>
                <v-col>Source:</v-col>
                <v-col>-</v-col>
                <v-col>Notes:</v-col>
                <v-col>-</v-col>
            </v-row>
            <v-row>
                <v-col>Value (2021/2022):</v-col>
                <v-col>-</v-col>
            </v-row>
            <v-spacer></v-spacer>
            <v-data-table :headers="headers" :items="items" :search="search" dense :items-per-page="5">
                <template v-slot:footer.prepend>
                    <v-btn text>Download</v-btn>
                    <v-btn text @click="closeHistory">close</v-btn>
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
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
                { text: 'Period', value: 'period',},
                { text: 'Value', value: 'value',},
            ],

            items: [],
        }
    },

    mounted() {
        this.fakeData();

    },

    methods: {
        fakeData() {
            this.items = [...Array(8).keys()].map(() => ({
                id: _.uniqueId('sgic_indicator_history_'),
                period: _.sample(['2009/2010', '2010/2011', '2011/2012', '2012/2013', '2013/2014', '2014/2015', '2016/2017', '2017/2018', '2018/2019', '2019/2020', '2020/2021']),
                value: _.sample([true, false])? Math.random() : (
                    _.sample([true, false])? '-' : 'N/A'
                ),
            }));
        },

        closeHistory() {
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
