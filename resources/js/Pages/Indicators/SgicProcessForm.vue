<template>
    <v-card flat>
        <v-card-title>
            <span class="text-h5">Subprocess edit form</span>
        </v-card-title>

        <v-card-text>
            <v-container>
                <v-row>
                    <v-col>
                        <v-text-field
                            v-model="process.code"
                            label="Code"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-text-field
                            v-model="process.name"
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
                @click="closeForm"
            >
                Cancel
            </v-btn>
            <v-btn
                v-if="!editing"
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
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    mixins: [],

    props: {
        editing: {
            type: Boolean,
            default: false,
        }
    },

    data() {
        return {
            process: {
                code: '',
                name: '',
            }
        }
    },

    mounted() {

    },

    methods: {
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
