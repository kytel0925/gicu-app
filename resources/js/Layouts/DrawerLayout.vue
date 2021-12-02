<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer.status"
            app
        >
            <v-list>
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img src="https://cdn.vuetifyjs.com/images/john.png"></v-img>
                    </v-list-item-avatar>
                </v-list-item>

                <v-list-group
                    v-model="drawer.user_actions.status"
                    no-action
                >
                    <template v-slot:activator>
                        <v-list-item-content>
                            <v-list-item-title class="text-h6">
                                {{ drawer.user.login }}
                            </v-list-item-title>
                            <v-list-item-subtitle>{{ drawer.user.email }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </template>

                    <v-list-item
                        v-for="item in drawer.user_actions.items"
                        :key="item.title"
                        link
                        class="sub-items-indentation"
                        dense
                    >
                        <v-list-item-icon>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-group>
            </v-list>

            <v-divider></v-divider>

            <!-- drawer menu items -->
            <v-list nav dense>
                <template v-for="item in drawer.items">
                    <v-list-group
                        v-if="item.items"
                        :key="item.title"
                        v-model="item.current"
                        :prepend-icon="item.icon"
                        no-action
                    >
                        <template v-slot:activator>
                            <v-list-item-content>
                                <v-list-item-title v-text="item.title"></v-list-item-title>
                            </v-list-item-content>
                        </template>

                        <template v-for="child in item.items">
                            <v-list-item
                                v-if="child.href"
                                :key="child.title"
                                :href="child.href"
                                v-model="child.current"
                                link
                                class="sub-items-indentation"
                            >
                                <v-list-item-icon v-show="child.icon">
                                    <v-icon>{{ child.icon }}</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ child.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>

                            <v-list-item
                                v-else
                                :key="child.title"
                                v-model="child.active"
                                @click="onDrawerItemClick(item)"
                                class="sub-items-indentation"
                            >
                                <v-list-item-icon v-show="child.has_icon">
                                    <v-icon>{{ child.icon }}</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>{{ child.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </template>
                    </v-list-group>

                    <v-list-item
                        v-else-if="item.href"
                        :key="item.id"
                        :href="item.href"
                        v-model="item.current"
                        link
                    >
                        <v-list-item-icon v-show="item.icon">
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-list-item
                        v-else
                        :key="item.title"
                        v-model="item.active"
                        @click="onDrawerItemClick(item)"
                    >
                        <v-list-item-icon v-show="item.has_icon">
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                            <v-list-item-title>{{ item.title }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                </template>
            </v-list>

        </v-navigation-drawer>


        <v-app-bar app>
            <v-app-bar-nav-icon @click="toggleDrawer"></v-app-bar-nav-icon>
            <slot name="app-bar-left">
                <v-toolbar-title>{{ title }}</v-toolbar-title>
            </slot>

            <v-spacer></v-spacer>

            <slot name="app-bar-right" />
        </v-app-bar>

        <v-main>
            <slot />
        </v-main>
    </v-app>
</template>

<script>
export default {
    props: {
        drawer: {
            type: Object,
            require: true
        },

        title: {
            type: String,
            require: true
        }
    },

    components: {

    },

    data: () => ({

    }),

    mounted() {

    },

    methods: {
        toggleDrawer() {
            this.drawer.toggle();
        },

        onDrawerItemClick(item){
            this.$emit('on-drawer-item-click', item);
        },
    }
}
</script>

<style scoped>
.sub-items-indentation{
    padding-left: 20px !important;
}
</style>
