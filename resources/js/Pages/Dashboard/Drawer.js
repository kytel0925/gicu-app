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

        this.setCurrentIfItemsAre();

        return this;
    }

    setCurrentIfItemsAre(){
        this.current = this.items.filter(item => item.current).length > 0;

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

    get() {
        return this.items;
    }

    dashboard(){
        let item = (new DrawerItem('dashboard.index', 'Dashboard', 'mdi-view-dashboard'))
            .setRoute();

        this.items.push(item);

        return item;
    }

    dataGathering(reports){
        let item = (new DrawerItem('report-requests', 'Data Gathering', 'mdi-database-marker'));

        reports = reports || _.get(Inertia, 'page.props.reports', []).map(
            item => (
                new DrawerItem(
                    `report-requests.${item.id}`,
                    _.get(item, 'name', `Report ${item.id}`),
                    'mdi-home-analytics'
                )
            ).setRoute('report-requests.index', {
                report: item.id,
            })
        );

        item.setItems(reports);

        this.items.push(item);

        return item;
    }

    indicators() {
        let item = (new DrawerItem('indicators', 'Indicators', 'mdi-chart-timeline-variant'));

        item.setItems([
            DrawerItemsBuilder.getSgicIndicatorItem(),
            DrawerItemsBuilder.getSatisfactionIndicatorItem(),
        ]);

        this.items.push(item);

        return item;
    }

    reports() {
        let item = (new DrawerItem('reports.index', 'Reports', 'mdi-file-chart'))
            .setRoute();

        this.items.push(item);

        return item;
    }

    parameters() {
        let item = (new DrawerItem('parameters', 'Parameters', 'mdi-developer-board'));

        item.setItems([
            DrawerItemsBuilder.getAcademicPeriodsItem(),
            DrawerItemsBuilder.getDegreesItem(),
            DrawerItemsBuilder.getTagsItem(),
        ]);

        this.items.push(item);

        return item;
    }

    settings() {
        let runtimeConnections = (new DrawerItem('dashboard.runtime-connections.index', 'Runtime Connections', 'mdi-connection')).setRoute();

        let item = (new DrawerItem('settings', 'Settings', 'mdi-cog'))
            .setItems([runtimeConnections]);

        this.items.push(item);

        return item;
    }

    static getSgicIndicatorItem() {
        return (new DrawerItem('indicators.sgic.index', 'SGIC', 'mdi-chart-tree'))
            .setRoute();
    }

    static getSatisfactionIndicatorItem() {
        return (new DrawerItem('indicators.satisfaction.index', 'Satisfaction', 'mdi-chart-timeline-variant-shimmer'))
            .setRoute();
    }

    static getTagsItem(){
        return (new DrawerItem('tags.index', 'Tags', 'mdi-tag-multiple'))
            .setRoute();
    }

    static getAcademicPeriodsItem(){
        return (new DrawerItem('academic-periods.index', 'Academic Periods', 'mdi-calendar-text'))
            .setRoute();
    }

    static getDegreesItem() {
        return (new DrawerItem('degrees.index', 'Degrees', 'mdi-school'))
            .setRoute();
    }

    static default(){
        let defaultDrawer = new DrawerItemsBuilder();

        defaultDrawer.dashboard();
        defaultDrawer.dataGathering();
        defaultDrawer.indicators();
        defaultDrawer.reports();
        defaultDrawer.parameters();
        defaultDrawer.settings();

        return defaultDrawer;
    }
}

export {Drawer, DrawerItem, DrawerItemsBuilder, DefaultUserActions};
