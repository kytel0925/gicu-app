<template>
    <drawer-layout
        :drawer="drawer"
        title="Dashboard"
    >
        <v-card>
            <v-row>
                <v-col>
                    <json-crud v-model="data" :readonly-attributes="readonlyAttributes" :hidden-attributes="hiddenAttributes" />
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    {{ data }}
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    Some random string content 23123
                </v-col>
            </v-row>
        </v-card>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";
import TestModel from "../Common/TestModel";
import DynamicInput from "../Common/DynamicInput";
import JsonCrud from "../Common/JsonCrud";

export default {
    components: {JsonCrud, DynamicInput, TestModel},
    mixins: [DrawerLayoutDefaults],
    data() {
        return {
            foo: 1,
            bar: -1,
            // data: ['foo@mail', 'bar@mail'],
            // data: 'true',
            data: {
                key_1: 'foo',
                key_2: 'bar',
                key_3: false,
                foo: {
                    items: ['a', 'b', 'c'],
                    description: 'random and large text'
                }
            },

            readonlyAttributes: ['key_1'],
            hiddenAttributes: ['key_2'],
        };
    }
}
</script>

<style scoped>

</style>
