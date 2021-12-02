<template>
    <v-card :loading="loading">
        <v-card-title class="text-h6 grey lighten-2">
            <v-icon>mdi-pencil-outline</v-icon>
            {{ title }}
        </v-card-title>
        <v-card-text>
            <v-form :readonly="loading">
                <v-text-field
                    v-model="reportModel.code"
                    readonly
                    label="Code"
                ></v-text-field>

                <!-- <v-text-field
                    v-model="reportModel.data_fetcher_text"
                    readonly
                    label="Data Fetcher"
                ></v-text-field> -->

                <v-text-field
                    v-model="reportModel.name"
                    :counter="255"
                    :rules="modelRules.name"
                    label="Name"
                    required
                ></v-text-field>

                <v-textarea
                    clearable
                    clear-icon="mdi-close-circle"
                    label="Description"
                    v-model="reportModel.description"
                ></v-textarea>

                <v-slider
                    v-model="reportModel.expires_in"
                    :min="1"
                    :max="365"
                    label="Expires In"
                    class="align-center"
                    :readonly="!has_expires_in"
                >
                    <template v-slot:prepend>
                        <v-switch
                            v-model="has_expires_in"
                        ></v-switch>
                    </template>
                    <template v-slot:append>
                        <v-text-field
                            label="Days"
                            v-model="reportModel.expires_in"
                            class="mt-0 pt-0"
                            type="number"
                            style="width: 60px"
                            :rules="has_expires_in? modelRules.expires_in : []"
                            :disabled="!has_expires_in"
                        ></v-text-field>
                    </template>
                </v-slider>
            </v-form>
        </v-card-text>
        <v-card-actions>
            <slot name="prepend_actions" />

            <v-spacer></v-spacer>

            <v-btn
                :disabled="loading"
                color="blue"
                outlined
                text
                @click="saveModel(reportModel)"
            >
                Save
            </v-btn>
            <v-btn
                :disabled="loading"
                color="green"
                outlined
                text
                @click="cloneModel(report)"
            >
                Reset
            </v-btn>
            <slot name="append_actions" v-bind:loading="loading" v-bind:close="closeEdit">
                <v-btn
                    :disabled="loading"
                    color="red"
                    outlined
                    text
                    @click="closeEdit"
                >
                    Close
                </v-btn>
            </slot>
        </v-card-actions>
        <reason-alert :type="reasonAlert.type" :reason="reasonAlert.message" />
    </v-card>
</template>

<script>
import Report from "../Models/Report";
import ReasonAlert from "../../Common/ReasonAlert";
import ReasonAlertHelper from "../../Common/ReasonAlertHelper";
import ReportUpdateAction from "./ReportUpdateAction";

export default {
    components: {ReasonAlert},
    mixins: [ReasonAlertHelper, ReportUpdateAction],

    props: {

    },

    mounted() {

    },

    data: () => ({
        loading: false,

        modelRules: {
            name: [
                v => !!v || 'Name is required',
                v => (v && v.length <= 255) || 'Name must be less than 255 characters',
            ],

            expires_in: [
                v => !!v || 'Days are required',
                v => (typeof v === 'number') || 'Days must be a valid number',
                v => (v && v >= 1 && v <= 365) || 'Days must have a value between 1 and 365',
            ]
        },

        valid: true,

        has_expires_in: false,
    }),
    methods: {
        cloneModel(model) {
            //manual clone
            this.reportModel = JSON.parse(JSON.stringify(model));
            this.reportModel.data_fetcher_text = _.get(this.reportModel, 'data_fetcher', '').split("\\").reverse()[0];
            this.has_expires_in = !!this.reportModel.expires_in;
        },

        saveModel(model) {
            let report = new Report(
                model.id,
                model.code,
                model.name,
                model.description,
                this.report.options,
                this.report.filters,
                this.has_expires_in? model.expires_in : null
            );

            let url = route(this.routeName, {
                report: report.id,
            });

            this.loading = true;

            axios
                .put(url, report)
                .then(response => this.$emit('report-saved', response.data))
                .catch(error => this.reasonAlertShowError(error))
                .then(() => this.loading = false);
        },

        closeEdit() {
            this.$emit('close');
        },
    },
    computed: {
        title() {
            return this.report.name || 'Report Values';
        },
    },
    watch: {

    }
}
</script>

<style scoped>

</style>
