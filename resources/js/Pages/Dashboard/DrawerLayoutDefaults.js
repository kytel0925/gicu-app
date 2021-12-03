import DrawerLayout from "../../Layouts/DrawerLayout";
import {Drawer, DefaultUserActions, DrawerItemsBuilder} from "./Drawer";

const drawerData = new Drawer();
drawerData.setUserActions(DefaultUserActions());

export default {
    components: {
        DrawerLayout,
    },

    data: () => ({
        drawer: drawerData,
    }),

    mounted() {
        this.setDashboardDrawer();
    },

    methods: {
        setDashboardDrawer() {
            let builder = DrawerItemsBuilder.default();

            this.drawer.setItems(builder.get());

            this.drawerDefaults();
        },

        drawerDefaults(){

        },

        setDrawerItemsCurrent(ids, items){
            ids = typeof ids === 'object'? ids : [ids];
            items = items || this.drawer.items;

            ids.forEach(id => {
                let item = items.firstOf(item => item.id === id || this.setDrawerItemsCurrent(ids, item.items || []));

                if(item)
                    item.current = true;

                return item? item.current : false;
            });
        },

        toggleDrawer() {
            this.drawer.toggle();
        },

        onClickDrawerItem(item) {

        }
    }
}
