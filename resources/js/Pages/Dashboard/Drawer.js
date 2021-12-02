'use strict';

import {Inertia} from '@inertiajs/inertia';

/**
 * Class AppNavigationDrawer
 */
class Drawer {
    /**
     * Class constructor
     *
     * @param user
     * @param status
     */
    constructor(user, status) {
        //user data like: login, avatar, etc
        this.user = user || {
            name: 'Jane',
            last_name: 'Doe',
            login: 'Jane Doe',
            email: 'jane.doe@mail.com',
            avatar: {
                src: ""
            },
        }

        //drawer status: {true = show, false = hide}
        this.status = status || true;

        this.items = [];
    }

    setItems(drawerItems) {
        this.items = drawerItems;
    }

    setUserActions(userActions) {
        this.user_actions = userActions || DefaultUserActions();
    }

    toggle() {
        this.status = !this.status;
    }
}

/**
 *
 * @returns {*}
 */
function DefaultUserActions(){
    return {
        status: false,
        items: [
            {
                icon: 'mdi-account-details', //to another model
                title: 'Account Settings',
            },
            {
                icon: 'mdi-logout',
                title: 'Logout',
            },
        ],
    };
}

class DrawerItem{
    constructor(id, title, icon) {
        this.id = id;
        this.title = title;
        this.icon = icon;
        this.current = false;
        this.href = false;
        this.routeName = null;
        this.routeParameters = null;
        this.visible = true;
    }

    setRoute(name, parameters){
        if(!name || typeof name !== 'string'){
            parameters = name;
            name = this.id;
        }

        this.href = route(name, parameters);
        this.routeName = name;
        this.routeParameters = parameters;

        this.current = route().current(name, parameters);

        return this;
    }

    setItems(items){
        this.items = _.values(items).filter(item => item instanceof DrawerItem);

        return this;
    }
}

class DrawerItemsBuilder{
    constructor() {
        this.reset();
    }

    reset(){
        this.items = [];

        return this;
    }

    defaults(){
        this.items.push(...[
            this.dashboard(),
            this.reports(),
            this.tags(),
            this.settings(),
        ]);

        return this;
    }

    get() {
        return this.items;
    }

    dashboard(){
        return (new DrawerItem('dashboard.index', 'Dashboard', 'mdi-view-dashboard'))
            .setRoute();
    }

    reports(reports){
        let item = (new DrawerItem('dashboard.report-requests.index', 'Reports', 'mdi-home-analytics'));

        reports = reports || _.get(Inertia, 'page.props.reports', []).map(
            item => (
                new DrawerItem(
                    `report-requests.${item.id}`,
                    _.get(item, 'name', `Report ${item.id}`),
                    'mdi-home-analytics'
                )
            ).setRoute('dashboard.report-requests.index', {
                report: item.id,
            })
        );

        item.setItems(reports);

        return item;
    }

    tags() {
        return (new DrawerItem('dashboard.tags.index', 'Tags', 'mdi-tag-multiple'))
            .setRoute();
    }

    settings() {
        let runtimeConnections = (new DrawerItem('dashboard.runtime-connections.index', 'Runtime Connections', 'mdi-connection')).setRoute();

        return (new DrawerItem('settings', 'Settings', 'mdi-cog'))
            .setItems([runtimeConnections]);
    }
}

export {Drawer, DrawerItem, DrawerItemsBuilder, DefaultUserActions};
