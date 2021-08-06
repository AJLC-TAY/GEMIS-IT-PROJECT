class Table {
    constructor(tableId, url, method, uniqueId, idField, height, search = false, 
        searchSelector = '', exportDataType = 'All', onPostBody = null) {
        
        this.tableId = tableId

        var tableSetup = {
            url, 
            method,
            uniqueId,
            idField,
            height,
            pagination: true,
            paginationParts: ["pageInfoShort", "pageSize", "pageList"],
            pageSize: 10,
            pageList: "[10, 25, 50, All]"
        }

        if (search) {
            tableSetup.search = search
        }

        if (searchSelector) {
            tableSetup.searchSelector = searchSelector
        }

        if (exportDataType) {
            tableSetup.exportDataType = exportDataType
        }

        if (onPostBody) {
            tableSetup.onPostBody = onPostBody
        }

        this.table = $(`${tableId}`).bootstrapTable(tableSetup)
    }
}

