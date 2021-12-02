import dayjs from 'dayjs';
let duration = require('dayjs/plugin/duration');
let relativeTime = require('dayjs/plugin/relativeTime');

dayjs.extend(duration)
dayjs.extend(relativeTime)

export default {
    methods: {
        dayjs(date){
            return dayjs(date);
        }
    },

    filters: {
        humanizeDate(date) {
            if(!date)
                return '-';

            return dayjs().to(dayjs(date));
        },

        mysqlDateTime(date) {
            if(!date)
                return '-';

            return dayjs(date).format('YYYY-MM-DD HH:mm:ss');
        }
    }
}
