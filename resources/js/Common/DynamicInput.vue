<template>
    <div>
        <v-text-field
            v-if="dynamicType === 'text'"
            v-model="content"
            :label="label"
            :dense="dense"
            v-bind="attributes"
        />
        <v-textarea
            v-else-if="dynamicType === 'textarea'"
            v-model="content"
            :label="label"
            :dense="dense"
            v-bind="attributes"

            clearable
            clear-icon="mdi-close-circle"
        />
        <v-select
            v-else-if="dynamicType === 'select'"
            v-model="content"
            :label="label"
            :dense="dense"
            v-bind="attributes"
        />
        <v-switch
            v-else-if="dynamicType === 'switch'"
            v-model="content"
            :label="label"
            :dense="dense"
            v-bind="attributes"
        ></v-switch>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: [String, Boolean, Number, Array],
        },
        type: {
            type: String,
            default: "dynamic",
            validator(value) {
                return ['text', 'textarea', 'text_array', 'select', 'switch', 'dynamic'].inArray(value);
            }
        },
        label: {
            type: String,
        },
        dense: {
            type: Boolean,
            default: true,
        },
        options: {
            type: Object,
            default(){
                return {};
            },
        },
        rules: {
            type: Array,
            default(){
                return [];
            }
        }
    },
    data() {
        return {
            content: this.value, //default support?
            arrayTypes: ['select', 'array_text'],
        };
    },
    mounted() {

    },
    computed: {
        attributes() {
            let attributes = _.omit(this.options, ['value', 'type', 'label', 'options', 'dense']);

            switch (this.dynamicType){
                case 'select':
                    attributes.items = [];
                    break;
                case 'switch':
                    attributes = _.omit(attributes, ['required']);
            }

            return attributes;
        },

        dynamicType() {
            if(this.type === 'dynamic'){
                //eval the typeof of the value
                switch (typeof this.value){
                    case "object":
                        if(!this.arrayTypes.inArray(this.type)){
                            return 'array_text';
                        }
                        break;

                    case "boolean":
                        if(this.type !== 'switch')
                            return 'switch';
                        break;

                    default:
                        return 'text';
                }
            }

            if(typeof this.value === 'string' && ['true', 'false'].inArray(this.value.toLowerCase())){
                this.content = eval(this.value);

                return 'switch';
            }

            return this.type;
        }
    },
    watch: {
        content(value){
            this.$emit('input', value)
        },

        value(value){
            if(value !== this.content)
                this.content = value;
        },
    }
}
</script>

<style scoped>

</style>
