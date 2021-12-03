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

        toggleDrawer() {
            this.drawer.toggle();
        },

        onClickDrawerItem(item) {

        }
    }
}
