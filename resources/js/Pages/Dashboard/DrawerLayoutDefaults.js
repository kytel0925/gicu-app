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
        let builder = new DrawerItemsBuilder();

        builder.defaults();

        this.drawer.setItems(builder.get());

        this.drawerDefaults();
    },

    methods: {
        drawerDefaults(){

        },

        toggleDrawer() {
            this.drawer.toggle();
        },

        onClickDrawerItem(item) {

        }
    }
}
