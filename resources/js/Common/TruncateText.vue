<template>
    <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
            <div
                class="text-truncate"
                :style="styles"
                v-bind="attrs"
                v-on="on"
            >{{ text }}</div>
        </template>
        <span>{{ text }}</span>
    </v-tooltip>
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            required: true,
        },

        maxWidth: {
            type: String,
            default: '300px',
        },
    },

    computed: {
        styles() {
            return {
                maxWidth: this.maxWidth,
            }
        },
    }
}
</script>

<style scoped>

</style>
