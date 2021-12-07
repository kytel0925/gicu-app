<template>
    <drawer-layout
        :drawer="drawer"
        title="Tags"
    >
        <v-card flat>
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
                    <template v-slot:item.description="{ value }">
                        <truncate-text :text="value" />
                    </template>

                    <template v-slot:item.actions="{ item }">
                        <v-btn icon>
                            <v-icon small @click="editCRUDItem(item)">mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn icon>
                            <v-icon small @click="showConfirmDeleteCRUDItem(item)">mdi-delete</v-icon>
                        </v-btn>
                    </template>

                    <template v-slot:footer.prepend>
                        <v-btn text @click="addCRUDItem">Add</v-btn>
                        <v-btn text>Download</v-btn>
                    </template>
                </v-data-table>
            </v-card-text>

            <!-- delete confirmation dialog -->
            <v-dialog v-model="dialogDeleteItem" max-width="500px">
                <ok-cancel-prompt
                    message="Are you sure you want to delete this item?"
                    :loading="isLoading"
                    @ok="deleteCRUDItem"
                    @cancel="closeConfirmDeleteCRUDItem"
                />
            </v-dialog>

            <!-- item form dialog -->
            <v-dialog
                v-model="dialogItemForm"
                width="500px"
            >
                <form-crud-item
                    v-model="currentItem"
                    :new-item="currentItemIndex === -1"
                    :loading="isLoading"
                    @save="saveFormCrudItem"
                    @cancel="closeCRUDItemForm"
                />
            </v-dialog>
        </v-card>
    </drawer-layout>
</template>

<script>
import DrawerLayoutDefaults from "./Dashboard/DrawerLayoutDefaults";
import BasicCRUDHelper from "../Common/BasicCRUDHelper";
import TruncateText from "../Common/TruncateText";
import FormCrudItem from "../Common/FormCrudItem";
import OkCancelPrompt from "../Common/OkCancelPrompt";

export default {
    components: {OkCancelPrompt, FormCrudItem, TruncateText},
    mixins: [DrawerLayoutDefaults, BasicCRUDHelper],

    data() {
        return {
            readonlyCode: true,
        }
    },
    mounted() {
        this.fakeData();
    },

    methods: {
        fakeData() {
            this.items = [...Array(200).keys()].map(() => ({
                id: _.uniqueId('tag_'),
                code: faker.hacker.abbreviation(),
                title: faker.lorem.words(),
                description: faker.lorem.paragraph(),
            }));
        },

    }
}
</script>

<style scoped>

</style>
