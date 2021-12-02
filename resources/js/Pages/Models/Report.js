class Report {
    constructor(id, code, name, description, options, filters, expires_in) {
        this.id = id;
        this.code = code;
        this.name = name;
        this.description = description || '';
        this.options = options || {};
        this.filters = filters || {};
        this.expires_in = expires_in || null;
    }

    /**
     * Using a raw item we instance a new Report Model
     *
     * @param item
     * @returns {Report}
     */
    static newInstanceFromItem(item){
        return new Report(
            _.get(item, 'id'),
            _.get(item, 'code'),
            _.get(item, 'name'),
            _.get(item, 'description'),
            _.get(item, 'options'),
            _.get(item, 'filters'),
            _.get(item, 'expires_in'),
        );
    }
}

export default Report;
