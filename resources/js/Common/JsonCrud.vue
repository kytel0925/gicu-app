<template>
    <v-card :loading="loading">
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
            <v-data-table :headers="headers" :items="attributes" dense disable-sort :search="search">
                <template v-slot:item.value="{ item }">
                    <dynamic-input v-model="dotObject[item.attribute]" :type="item.type" :options="item.options" />
                </template>

                <template v-slot:item.actions="{ item }">
                    <v-btn
                        icon
                        @click="deleteItem(item)"
                        :disabled="loading"
                        v-if="!item.options.readonly"
                    >
                        <v-icon small>mdi-delete</v-icon>
                    </v-btn>
                </template>

                <template v-slot:footer.prepend>
                    <v-btn text @click="addAttribute" :disabled="loading">Add</v-btn>
                    <v-btn text @click="saveJSON" :disabled="loading">Save</v-btn>
                    <v-btn text @click="resetJSON" :disabled="loading">Reset</v-btn>
                    <slot name="footer_prepend_actions" v-bind:loading="loading">
                        <v-btn text @click="closeJSON" v-if="showCloseButton" :disabled="loading">Close</v-btn>
                    </slot>
                    <!--<v-btn text>Preview</v-btn>-->
                </template>
            </v-data-table>
        </v-card-text>
        <v-dialog v-model="show_dialog_add" width="500px" max-width="800px" persistent>
            <v-card>
                <v-card-title class="text-h6 grey lighten-2">
                    <v-icon>mdi-pencil-outline</v-icon>
                    New Entry
                </v-card-title>
                <v-card-text>
                    <v-form v-model="form_add_valid" lazy-validation ref="form_add">
                        <v-row>
                            <v-col>
                                <v-text-field v-model="newAttribute.attribute" required label="Key" :rules="newAttributeRules.attribute"/>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-select v-model="newAttribute.type" :items="types" required label="Types" />
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <dynamic-input v-model="newAttribute.value" :type="newAttribute.type" label="Value" />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-btn text :disabled="!form_add_valid" @click="saveFormAdd">Save New Entry</v-btn>
                    <v-btn text @click="closeFormAdd">Close</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import DynamicInput from "./DynamicInput";
import axios from "axios";
export default {
    components: {DynamicInput},
    props:{
        value: {
            type: [Object],
            required: true,
        },

        title: {
            Type: String,
            default: 'JSON CRUD',
        },

        readonlyAttributes: {
            type: Array,
            default() {
                return ['id', 'code', 'created_at', 'updated_at', 'deleted_at'];
            }
        },

        hiddenAttributes: {
            type: Array,
            default() {
                return ['_id', '_id.$oid'];
            }
        },

        showCloseButton: {
            type: Boolean,
            default: false,
        },

        saveRoute: {
            type: String
        },

        routeMethod: {
            type: String,
            default: 'post',
            validator(value) {
                return ['put', 'post'].inArray(value);
            },
        }
    },

    data() {
        return {
            content: this.value,

            search: '',

            loading: false,

            headers: [
                { text: 'Key', value: 'attribute',},
                { text: 'Type', value: 'type', filterable: false, },
                { text: 'Value', value: 'value', },
                { text: 'Actions', value: 'actions', filterable: false, },
            ],

            attributes: [],

            dotObject: {},

            show_dialog_add: false,

            form_add_valid: true,

            newAttribute: {},

            newAttributeRules: {
                attribute: [
                    v => !!v || 'Attribute is required',
                    v => typeof this.dotObject[v] === 'undefined' || 'Attribute is already taken',
                    this.validateKeyComposition
                ]
            },

            newEmptyAttribute: {
                attribute: '',
                type: 'dynamic',
                value: '',
                items: [],
                options: {
                    readonly: false,
                },
            },

            types: [
                {
                    value: 'text',
                    text: 'Text',
                },
                {
                    value: 'textarea',
                    text: 'Textarea',
                },
                {
                    value: 'text_array',
                    text: 'Text Array',
                },
                {
                    value: 'select',
                    text: 'Select',
                },
                {
                    value: 'switch',
                    text: 'Switch',
                },
                {
                    value: 'dynamic',
                    text: 'Dynamic',
                },
            ],
        }
    },

    mounted() {
        this.decompose(this.content);
        this.resetFormAdd();
    },

    methods: {
        decompose(decomposable) {
            if(typeof decomposable === 'object'){
                this.dotObject = collapseToDotNotation(decomposable);
                this.attributes = _.map(this.dotObject,
                    (value, attribute) => ({
                        attribute,
                        value,
                        type: 'dynamic',
                        options: {
                            readonly: this.readonlyAttributes.inArray(attribute),
                        },
                    })
                ).filter(attributeItem => !this.hiddenAttributes.inArray(attributeItem.attribute));
            }
        },

        saveJSON() {
            let nestedObject = expandWithDotNotation(this.dotObject);

            this.$emit('input', nestedObject);

            if(!this.saveRoute){
                return;
            }

            this.$emit('saving', nestedObject);

            this.loading = true;

            axios[this.routeMethod](this.saveRoute, nestedObject)
                .then(response => this.$emit('saved', response.data))
                .catch(error => this.$emit('save-error', error))
                .then(() => this.loading = false);
        },

        resetJSON() {
            this.$emit('reset', expandWithDotNotation(this.dotObject));

            this.decompose(this.value);
        },

        closeJSON() {
            this.$emit('close', expandWithDotNotation(this.dotObject));
        },

        deleteItem(item) {
            if(confirm(`Are you sure you want to delete this key[${item.attribute}]`)) {
                delete this.dotObject[item.attribute];
                this.attributes = this.attributes.filter(attributeItem => item.attribute !== attributeItem.attribute);
            }
        },

        saveFormAdd(){
            if(!this.$refs.form_add.validate())
                return;

            this.dotObject[this.newAttribute.attribute] = this.newAttribute.value;
            this.attributes = [JSON.parse(JSON.stringify(this.newAttribute)), ...this.attributes];

            this.closeFormAdd();
        },

        resetFormAdd() {
            this.newAttribute = JSON.parse(JSON.stringify(this.newEmptyAttribute));
        },

        closeFormAdd() {
            this.show_dialog_add = !this.show_dialog_add;
            this.$refs.form_add.resetValidation()
            // this.$refs.form_add.reset()
            this.resetFormAdd();
        },

        addAttribute() {
            this.resetFormAdd();
            this.show_dialog_add = true;
        },

        validateKeyComposition(key){
            if(key){
                let keys = key.split('.');
                keys.pop();
                if(keys.length === 0)
                    return true;

                let test_key = keys.join('.');
                if(typeof this.dotObject[test_key] !== 'undefined')
                    return `Attribute is already used in key[${test_key}]`;

                if(this.readonlyAttributes.inArray(test_key))
                    return `Attribute is readonly and can't bet set[${test_key}]`;

                if(this.hiddenAttributes.inArray(test_key))
                    return `Attribute is hidden and can't bet set[${test_key}]`;

                return this.validateKeyComposition(test_key);
            }

            return true;
        }
    },

    watch: {
        value: {
            handler(value){
                this.decompose(value);
            },
            deep: true,
        }
    }
}
</script>

<style scoped>

</style>
