<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon>
                    <v-icon v-bind="attrs" v-on="on">mdi-pencil</v-icon>
                </v-btn>
            </template>
            <span>Edit Indicator</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon>
                    <v-icon v-bind="attrs" v-on="on">mdi-playlist-plus</v-icon>
                </v-btn>
            </template>
            <span>Processes</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn icon>
                    <v-icon v-bind="attrs" v-on="on">mdi-cog</v-icon>
                </v-btn>
            </template>
            <span>Settings</span>
        </v-tooltip>

        <!-- dialogs (all the dialog is a component or only the vcard (content) -->
    </div>
</template>

<script>
export default {
    components: {},

    props: {

    },

    data: () => ({

    }),

    methods: {

    },

    computed: {

    },
}
</script>

<style scoped>

</style>
