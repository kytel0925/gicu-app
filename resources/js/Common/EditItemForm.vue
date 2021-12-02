<template>
    <v-card :loading="loading">
        <slot name="title" v-bind:title="title">
            <v-card-title class="text-h6 grey lighten-2" v-if="title">
                <v-icon>mdi-pencil-outline</v-icon>
                {{ title }}
            </v-card-title>
        </slot>
        <v-card-text>
            <v-form :readonly="loading">
                <template v-for="(attributeSettings, index) in modelAttributeSettings">
                    <slot :name="attributeSettings.slot_name" v-bind:value="model[attributeSettings.attribute]" v-bind:settings="attributeSettings">
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-if="attributeSettings.type === 'text'"
                                    v-model="model[attributeSettings.attribute]"

                                    :label="attributeSettings.label"
                                    :counter="attributeSettings.counter"
                                    :rules="attributeSettings.rules"

                                    :required="attributeSettings.required"
                                    :readonly="attributeSettings.readonly"
                                    :disabled="attributeSettings.disabled"

                                    dense
                                />
                                <v-textarea
                                    v-else-if="attributeSettings.type === 'textarea'"
                                    v-model="model[attributeSettings.attribute]"

                                    :label="attributeSettings.label"

                                    :required="attributeSettings.required"
                                    :readonly="attributeSettings.readonly"
                                    :disabled="attributeSettings.disabled"

                                    clearable
                                    clear-icon="mdi-close-circle"
                                    dense
                                />
                                <v-select
                                    v-else-if="attributeSettings.type === 'select'"
                                    v-model="model[attributeSettings.attribute]"

                                    :items="attributeSettings.items"
                                    :item-text="attributeSettings.item_text"
                                    :item-value="attributeSettings.item_value"
                                    :label="attributeSettings.label"

                                    :required="attributeSettings.required"
                                    :readonly="attributeSettings.readonly"
                                    :disabled="attributeSettings.disabled"

                                    dense
                                />
                                <v-switch
                                    v-else-if="attributeSettings.type === 'switch'"
                                    v-model="model[attributeSettings.attribute]"

                                    :required="attributeSettings.required"
                                    :readonly="attributeSettings.readonly"
                                    :disabled="attributeSettings.disabled"

                                    dense
                                ></v-switch>
                            </v-col>
                        </v-row>
                    </slot>
                </template>
            </v-form>
        </v-card-text>
        <v-card-actions>
            <slot name="prepend_actions" v-bind:loading="loading" v-bind:item="model" />

            <v-spacer></v-spacer>

            <slot name="append_actions" v-bind:loading="loading" v-bind:item="model">
                <v-btn
                    :disabled="loading"
                    color="blue"
                    outlined
                    text
                    @click="saveItem(model)"
                >
                    Save
                </v-btn>
                <v-btn
                    :disabled="loading"
                    color="green"
                    outlined
                    text
                    @click="cloneItem(item)"
                >
                    Reset
                </v-btn>
                <v-btn
                    :disabled="loading"
                    color="red"
                    outlined
                    text
                    @click="closeForm"
                >
                    Close
                </v-btn>
            </slot>
        </v-card-actions>
    </v-card>
</template>

<script>
import axios from "axios";

const defaultAttributeSettings = {
    type: null,
    attribute: null,
    slot_name: null,
    label: undefined,
    rules: [],
    required: false,
    readonly: false,
    disabled: false,
};

const textAttributeSettings = _.merge({}, defaultAttributeSettings, {
    type: 'text',
    counter: false,
});

const textAreaAttributeSettings = _.merge({}, defaultAttributeSettings, {
    type: 'textarea',
});

const selectAttributeSettings = _.merge({}, defaultAttributeSettings, {
    type: 'select',
    item_text: 'text',
    item_value: 'value',
});

const switchAttributeSettings = _.merge({}, defaultAttributeSettings, {
    type: 'switch',
});

export default {
    props: {
        item: {
            type: Object,
            require: true,
        },
        hiddenAttributes: {
            type: Array,
            default() {
                return ['_id'];
            },
        },
        readonlyAttributes: {
            type: Array,
            default() {
                return ['_id', 'id', 'created_at', 'updated_at', 'deleted_at'];
            },
        },
        fieldSettings: {
            type: Array,
        },
        title: {
            type: String
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
            model: {},
            valid: true,
            loading: false,
        }
    },

    created() {
        this.cloneItem(this.item);
    },

    methods: {
        cloneItem(item) {
            this.model = JSON.parse(JSON.stringify(item));
        },

        saveItem(item) {
            if(!this.saveRoute){
                this.$emit('saved', item);

                return;
            }

            this.$emit('saving', item);

            this.loading = true;

            axios[this.routeMethod](this.saveRoute, item)
                .then(response => this.$emit('saved', response.data))
                .catch(error => this.$emit('save-error', error))
                .then(() => this.loading = false);
        },

        closeForm() {
            this.$emit('close');
        },

        defaultAttributeSettings(attribute) {
            let value = this.model[attribute];

            if(value === null)
                return textAttributeSettings;

            if(typeof value === 'object')
                return false;

            if(typeof value === 'string' && value.length > 50){
                return textAreaAttributeSettings;
            }

            if(typeof value === 'boolean')
                return switchAttributeSettings;

            return textAttributeSettings;
        }
    },

    computed: {
        modelAttributeSettings() {
            return _.keys(this.model).map(attribute => {
                if(this.hiddenAttributes.inArray(attribute))
                    return false;

                let settings = _.get(this.fieldSettings, attribute, this.defaultAttributeSettings(attribute)); //@todo automatic selection with the value type(string: 'text', boolean: 'switch', object: 'jsonEditor'

                if(!settings)
                    return false;

                settings = _.merge({}, settings);

                settings.attribute = attribute;
                settings.label = settings.label || attribute;
                settings.slot_name = `attribute.${attribute}`;
                settings.readonly = this.readonlyAttributes.inArray(attribute) || settings.readonly;

                return settings;
            }).filter(item => !!item);
        },
    },

    watch: {
        item: {
            handler(item) {
                this.cloneItem(item);
            },
            deep: true,
        }
    }
}
</script>

<style scoped>

</style>
