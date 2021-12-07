export default {
    data() {
        return {
            search: '',

            fetchingItems: false,

            updatingItem: false,

            headers: [
                { text: 'Code', value: 'code',},
                { text: 'Title', value: 'title',},
                { text: 'Description', value: 'description',},
                { text: 'Actions', value: 'actions', filterable: false, sortable: false,},
            ],

            items: [],

            currentItem: {},

            currentItemIndex: -1,

            dialogItemForm: false,

            dialogDeleteItem: false,
        }
    },

    created() {
        //this.setDefaultHeaders();
        this.searchItems();
    },

    mounted(){

    },

    methods: {
        searchItems() {

        },

        addCRUDItem() {
            this.currentItem = {};
            this.currentItemIndex = -1;
            this.dialogItemForm = true;
        },

        editCRUDItem(item) {
            let index = this.findCRUDItemIndex(item);

            this.currentItem = Object.assign({}, item);
            this.currentItemIndex = index;
            this.dialogItemForm = true;
        },

        closeCRUDItemForm() {
            this.currentItem = {};
            this.currentItemIndex = -1;
            this.dialogItemForm = false;
        },

        saveFormCrudItem(){
            this.currentItemIndex === -1?
                this.storeCRUDItem() :
                this.updateCRUDItem();
        },

        storeCRUDItem() {
            this.closeCRUDItemForm();
        },

        updateCRUDItem() {
            let item = Object.assign({}, this.currentItem);

            Object.assign(this.items[this.currentItemIndex], item);

            this.closeCRUDItemForm();
        },

        showConfirmDeleteCRUDItem(item) {
            let index = this.findCRUDItemIndex(item);

            this.currentItem = Object.assign({}, item);
            this.currentItemIndex = index;
            this.dialogDeleteItem = true;
        },

        closeConfirmDeleteCRUDItem() {
            this.currentItem = {};
            this.currentItemIndex = -1;
            this.dialogDeleteItem = false;
        },

        deleteCRUDItem() {
            this.closeConfirmDeleteCRUDItem();
        },

        findCRUDItemIndex(item, ifItemNotFound){
            let index = this.items.indexOf(item);

            ifItemNotFound = ifItemNotFound || (() => {
                if(index === -1){
                    let message = 'item index was not found in the items stack';

                    console.error(message, item);

                    throw message;
                }

                return index;
            });

            return ifItemNotFound();
        }
    },

    computed: {
        /**
         * Current loading status
         *
         * @returns {boolean}
         */
        isLoading() {
            return this.fetchingItems || this.updatingItem;
        },

        title() {
            return 'Data Management';
        },
    },

    watch: {

    },

    filters: {

    },
}
