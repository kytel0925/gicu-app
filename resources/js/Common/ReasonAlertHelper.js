export default {
    data: () => ({
        reasonAlert: {
            type: 'warning',
            message: '',
        },
    }),

    methods: {
        reasonAlertShowError(payload, message) {
            message = message || 'Something went wrong, please contact the support center';

            message += ', id: ' + Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 10);
            this.reasonAlert.type = 'error';
            this.reasonAlert.message = message;
            console.error(message, payload);
        },

        reasonAlertShowWarning(payload, message) {
            message = message || 'We find some troubles, please contact the support center';
            message += ', id: ' + Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 10);

            this.reasonAlert.type = 'warning';
            this.reasonAlert.message = message;

            console.warn(message, payload);
        },
    }
}
