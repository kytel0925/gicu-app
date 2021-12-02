window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

/**
 * Search into the array for the needle, same as php
 *
 * @param needle
 * @returns {boolean}
 */
Array.prototype.inArray = function(needle){
    return this.indexOf(needle) !== -1;
};

/**
 * Return the first element of the array
 *
 * @return {*}
 */
Array.prototype.first = function (){
    return [...this].shift();
};

/**
 * returns the last element of the array
 *
 * @return {*}
 */
Array.prototype.last = function (){
    return [...this].pop();
};

/**
 * Returns the first element that fulfil the condition
 *
 * @param condition
 * @return {null|*}
 */
Array.prototype.firstOf = function (condition){
    if(condition === undefined)
        return this.first();

    let value = null;

    this.forEach((item, index) => {
        if(!value && condition(item, index))
            value = item;
    });

    return value;
};

/**
 * Returns the last element that fulfil the condition
 *
 * @param condition
 * @return {null|*}
 */
Array.prototype.lastOf = function (condition){
    if(condition === undefined)
        return this.last();

    let value = null;
    let reverse = [...this];

    reverse.forEach((item, index) => {
        if(!value && condition(item, index))
            value = item;
    });

    return value;
};

/**
 * Get the values of a given key.
 *
 * @param value
 * @return {*}
 */
Array.prototype.pluck = function (value){
    return this.map(item => _.get(item, value));
};

window.collapseToDotNotation = function (nestedData){
    nestedData = JSON.parse(JSON.stringify(nestedData)); //this will prevent circular references and sort the fields?

    let newDottedObject = {};

    let collapseAttributesWithValues = function(value, attribute, attributes){
        attributes.push(attribute);

        if(typeof value === 'object') {
            _.each(value, (childValue, childAttribute) =>
                collapseAttributesWithValues(childValue, childAttribute, [...attributes])
            );

            return ;
        }


        return newDottedObject[attributes.join('.')] = value;
    };

    _.each(nestedData, (value, attribute) => {
        collapseAttributesWithValues(value, attribute, []);

        return true;
    });

    return newDottedObject;
}

window.expandWithDotNotation = function(dottedData) {
    dottedData = JSON.parse(JSON.stringify(dottedData)); //this will prevent circular references and sort the fields?

    let newNestedObject = {};

    _.each(dottedData, (value, dotKey) => {
        if(typeof value !== "object"){
            _.set(newNestedObject, dotKey, value);
        }
    });

    return newNestedObject;
}
