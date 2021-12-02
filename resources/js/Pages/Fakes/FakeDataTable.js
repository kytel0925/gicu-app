import axios from "axios";

export default {
    data: () => ({
        search: '',
        loading: {
            value: false,
            message: 'fetching dummy data...',
        },
        headers: [
            {
                text: 'ID',
                align: 'start',
                sortable: false,
                value: 'id',
            },
            { text: 'Name', value: 'name' },
            { text: 'Last Name', value: 'last_name' },
            { text: 'Birth Date', value: 'birth_date' },
            { text: 'Gender', value: 'gender' },
            { text: 'Eye Color', value: 'eye_color', sortable: false },
        ],
        items: [],
        pagination: {
            disabled: false,
            pages: null,
            page: 1, //prop?
            max: 100, //prop?
            total: null,
            offset: null,
        },
        sort: {
            by: '',
            desc: false,
        },

        dialog: false,
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
        defaultItem: {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
    }),

    created () {
        //auto fetching should be in here
    },

    methods: {
        //real methods
        fetchItems(){
            let url = route('fake-data.index', {
                results: 'fake_person_1',
                page: this.pagination.page,
                max: this.pagination.max,
            });
            let params = {};

            this.pagination.disabled = this.loading.value = true;
            this.items = [];

            //order
            if(this.sort.by){
                if(typeof this.sort.by === 'string' && this.sort.by.trim()){
                    params[`sort.by[${this.sort.by}]`] = this.sort.desc === true? 'desc' : 'asc';
                }
                else if(this.sort.by.length > 0){
                    _.each(this.sort.by, (by, index) =>
                        params[`sort.by[${by}]`] = _.get(this.sort.desc, index, false) === true? 'desc' : 'asc'
                    );
                }
            }

            axios
                .get(route.href, {params})
                .then(response => this.initializeFetchedItems(response.data))
                .catch(error => console.log(error))
                .then(() => this.pagination.disabled = this.loading.value = false);
        },

        initializeFetchedItems(results) {
            this.pagination.pages = results.pages;
            this.pagination.total = results.total;
            this.pagination.offset = results.offset;
            this.pagination.show = results.items.length > 0 && results.pages > 1;

            this.items = results.items;
        },

        //dummy methods
        editItem (item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem (item) {
            this.editedIndex = this.desserts.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        deleteItemConfirm () {
            this.desserts.splice(this.editedIndex, 1)
            this.closeDelete()
        },

        close () {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save () {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
        },
    },

    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },

        searchDisabled() {
            return this.pagination.disabled || this.items.length <= 0;
        }
    },

    watch: {
        dialog (val) {
            val || this.close()
        },
        dialogDelete (val) {
            val || this.closeDelete()
        },

        'pagination.page': function(newPage, oldPage) {
            if(newPage !== oldPage)
                this.fetchItems();
        },

        'pagination.max': function(newMax, oldMax) {
            if(newMax !== oldMax) {
                //to prevent offset bugs
                if(this.pagination.page > 1){
                    this.pagination.page = 1; //the 'pagination.page' watch will fire the fetch
                }
                else{
                    this.fetchItems();
                }
            }
        },

        sort: {
            handler(newSort) {
                if((typeof newSort.by === 'string' && newSort.by !== '') || (typeof newSort.by === 'object' && newSort.by.length > 0 && newSort.desc && newSort.desc.length > 0)) {
                    this.fetchItems();
                }
                else if((typeof newSort.by === 'undefined' || newSort.by.length === 0) && (typeof newSort.desc === 'undefined' || newSort.desc.length === 0)){
                    this.fetchItems();
                }
            },
            deep: true,
        },
    },

}
