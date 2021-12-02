<v-card v-if="show_results">
    <v-card-title>
        <v-text-field
            v-model="search_results"
            append-icon="mdi-magnify"
            label="Search"
            single-line
            hide-details
        ></v-text-field>
    </v-card-title>
    <v-data-table
        :headers="headers"
        :items="results"
        :search="search_results"
    ></v-data-table>
</v-card>
