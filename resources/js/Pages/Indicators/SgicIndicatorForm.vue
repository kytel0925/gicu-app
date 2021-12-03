<template>
    <v-card flat>
        <v-card-title>SGIC Indicator Form / Process P#</v-card-title>
        <v-card-subtitle>Period: 2021/2022 - A wild title</v-card-subtitle>
        <v-card-text>
            <v-container>
                <v-row>
                    <v-col>
                        <v-autocomplete
                            v-model="current.subprocess"
                            :items="subprocesses"
                            label="Subprocess"
                            placeholder="Select Subprocess"
                            persistent-placeholder
                            required
                        />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-text-field
                            v-model="current.code"
                            label="Code"
                            required
                        />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-textarea
                            v-model="current.description"
                            label="Description"
                            required
                        />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-select
                            v-model="current.type"
                            :items="types"
                            label="Indicator Type"
                            placeholder="Select Indicator Type"
                            persistent-placeholder
                            required
                        />
                    </v-col>
                </v-row>
                <v-row v-if="current.type === 'provider'">
                    <v-col>
                        <v-autocomplete
                            v-model="current.provider"
                            :items="providers"
                            label="Providers"
                            placeholder="Select Provider"
                            persistent-placeholder
                            required
                        />
                    </v-col>
                </v-row>
                <v-row v-else-if="current.type === 'manual'">
                    <v-col>
                        <v-text-field
                            v-model="current.value"
                            label="Value"
                            required
                        />
                    </v-col>
                </v-row>
            </v-container>
        </v-card-text>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                v-if="newItem"
                color="blue darken-1"
                text
                @click="closeForm"
            >
                Add
            </v-btn>
            <v-btn
                v-else
                color="blue darken-1"
                text
                @click="closeForm"
            >
                Save
            </v-btn>
            <v-btn
                color="blue darken-1"
                text
                @click="closeForm"
            >
                Cancel
            </v-btn>
        </v-card-actions>

    </v-card>
</template>

<script>
export default {
    components: {},

    mixins: [],

    props: {
        newItem: {
            type: Boolean,
            default: true,
        }
    },

    data() {
        return {
            subprocesses: [],
            types: [
                {
                    value: 'manual',
                    text: 'Manual',
                },
                {
                    value: 'provider',
                    text: 'Provider (system)',
                }
            ],
            providers: [],
            current: {
                process: '',
                subprocess: null,
                code: '',
                description: '',
                type: undefined,
                value: '',
                provider: '',
            }
        }
    },

    mounted() {
        this.fakeData();
    },

    methods: {
        fakeData(){
            this.subprocesses = [...Array(50).keys()].map(() => ({
                value: _.uniqueId('sgic_subprocess_'),
                text: faker.hacker.abbreviation(),
            }));

            this.providers = [...Array(50).keys()].map(() => ({
                value: _.uniqueId('sgic_providers_'),
                text: faker.lorem.words(),
            }));
        },

        closeForm() {
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
