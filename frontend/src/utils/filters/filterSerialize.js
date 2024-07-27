import formData from '../../utils/filters/formData'
import moment from 'moment'
export default {
  serialize(filters, sorting) {
    let parts = []

    for (const [filterName, filterValue] of Object.entries(filters)) {
      switch (formData[filterName].type) {
      case 'range':
        parts = this.buildRangeValuePart(filterName,filterValue,parts)
        break
      case 'multichoice':
        parts = this.buildNumericValuePart(filterName,filterValue,parts)
        break
      case 'level':
        parts = this.buildNumericValuePart(filterName,filterValue,parts)
        break
        // case 'choice':
        //     parts = this.buildNumericValuePart(filterName,filterValue,parts)
        //     break;
      case 'choice':
        parts = this.buildStringValuePart(filterName,filterValue,parts)
        break
      case 'date':
        parts = this.buildDateValuePart(filterName,filterValue,parts)
        break
      case 'ticker':
        parts = this.buildTickerValuePart(filterName,filterValue,parts)
        break
      }
    }
    this.buildSortingPart(sorting, parts)

    return  parts.join('&')
  },
  buildDateValuePart(filterName, filterValue, parts) {
    if (filterValue.dates.length === 1) {
      parts.push(`${filterName}[before]=${formData[filterName].unHumanizeValue(filterValue.dates[0])}`)
      parts.push(`${filterName}[after]=${formData[filterName].unHumanizeValue(filterValue.dates[0])}`)
    }
    if (filterValue.dates.length === 2) {
      const date1 = formData[filterName].unHumanizeValue(filterValue.dates[0])
      const date2 = formData[filterName].unHumanizeValue(filterValue.dates[1])

      if (moment(date2).isAfter(moment(date1))) {
        parts.push(`${filterName}[before]=${date2}`)
        parts.push(`${filterName}[after]=${date1}`)
      } else {
        parts.push(`${filterName}[before]=${date1}`)
        parts.push(`${filterName}[after]=${date2}`)
      }
    }

    return parts
  },
  // buildLevelValuePart(filterName, filterValue, parts){
  //
  //     filterValue.level.forEach((level)=>{
  //
  //     })
  //
  //     return parts
  // },
  buildRangeValuePart(filterName, filterValue, parts) {

    const min = this.buildValuePart(filterValue.gte)
    // eslint-disable-next-line no-case-declarations
    const max = this.buildValuePart(filterValue.lte)

    if ( min ) {
      parts.push(`${filterName}[gte]=${formData[filterName].unHumanizeValue(min)}`)
    }
    if ( max ) {
      parts.push(`${filterName}[lte]=${formData[filterName].unHumanizeValue(max)}`)
    }

    return parts
  },
  buildSortingPart(sorting, parts) {
    if (sorting.sort1.filterId) {
      parts.push(`order[${sorting.sort1.filterId}]=${sorting.sort1.order }`)
    } else if (sorting.sort1.order) {
      parts.push(`order[chp]=${sorting.sort1.order }`)
    }
    if (sorting.sort2.filterId) {
      parts.push(`order[${sorting.sort2.filterId}]=${sorting.sort2.order }`)
    }

    return parts
  },
  buildNumericValuePart(filterName, filterValue, parts) {
    if ( Array.isArray(filterValue.value) ) {
      filterValue.value.forEach((value) => {
        parts.push(`${filterName}[]=${value}`)
      })
    } else if (filterValue.value ) {
      parts.push(`${filterName}=${filterValue.value}`)
    }

    return parts
  },
  buildStringValuePart(filterName, filterValue, parts) {
    if (filterValue.value ) {
      parts.push(`${filterName}=${filterValue.value}`)
    }

    return parts
  },
  buildTickerValuePart(filterName, filterValue, parts) {
    if (filterValue.value ) {
      filterValue.value.replace(',',' ').split(' ').forEach((ticker) => {
        parts.push(`${filterName}[]=${ticker}`)
      })
    }

    return parts
  },
  chosenFilters() {
    return ['price', 'chp','vol','averageDailyVolume3Month','exchange']
  },
  getDefaultFilters() {
    const defaultFilters = {}

    for (const [filterId, filterData] of Object.entries(formData)) {

      switch (filterData.type) {
      case 'range':
        this.appendRangeFilter(defaultFilters,filterId)
        break
      case 'multichoice':
        this.appendNumericFilter(defaultFilters,filterId)
        break
      case 'level':
        this.appendNumericFilter(defaultFilters,filterId)
        break
        // case 'choice':
        //     this.appendText(defaultFilters,filterId)
        //     break;
      case 'date':
        this.appendDateFilter(defaultFilters,filterId)
        break
        // case 'string':
        //     this.appendText(defaultFilters,filterId)
        //     break;
      case 'choice':
        this.appendText(defaultFilters,filterId)
        break
      case 'ticker':
        this.appendText(defaultFilters,filterId)
        break
      }
    }

    return defaultFilters
  },
  getSortableFilters() {
    const sortableFilters = []

    for (const [filterId, filterData] of Object.entries(formData)) {
      if (filterData.sortable === true) {
        sortableFilters.push({ filterId: filterId, order: filterData.order })
      }
    }

    return sortableFilters.sort((a, b) => (a.order > b.order) ? 1 : -1)
  },
  getGroupedFilters() {
    const groupedFilters = {}

    for (const [filterId, filterData] of Object.entries(formData)) {
      if (filterData.type === null) continue
      if (!groupedFilters[filterData.group]) {
        groupedFilters[filterData.group]  = [{ filterId: filterId, order: filterData.order }]
      } else {
        const result = groupedFilters[filterData.group]

        result.push({ filterId: filterId, order: filterData.order })
        groupedFilters[filterData.group] = result.sort((a, b) => (a.order > b.order) ? 1 : -1)
      }
    }

    return groupedFilters
  },
  getGroupedTableColumns() {
    const groupedFilters = {}

    for (const [filterId, filterData] of Object.entries(formData)) {
      if (filterId === 'id') continue
      if (filterData.sortable === false) continue
      if (!groupedFilters[filterData.group]) {
        groupedFilters[filterData.group]  = [{ filterId: filterId, order: filterData.order }]
      } else {
        const result = groupedFilters[filterData.group]

        result.push({ filterId: filterId, order: filterData.order })
        groupedFilters[filterData.group] = result.sort((a, b) => (a.order > b.order) ? 1 : -1)
      }
    }

    return groupedFilters
  },
  // appendLevelFilter(filters,filterId) {
  //     if(filters[filterId]) return;
  //     let value = { levels: [0,50], range: 0, direction: 0  }
  //     filters[filterId] = { ...value,...{ selected: false }}
  // },
  appendRangeFilter(filters,filterId) {
    if (filters[filterId]) return
    const value = { lte: null, gte: null }

    filters[filterId] = { ...value,...{ selected: this.chosenFilters().includes(filterId) } }
  },
  appendText(filters,filterId) {
    if (filters[filterId]) return
    filters[filterId] = { value: null, selected: this.chosenFilters().includes(filterId) }
  },
  appendDateFilter(filters,filterId) {
    if (filters[filterId]) return
    filters[filterId] = { dates: [] , selected: false }
  },
  appendNumericFilter(filters,filterId) {
    if (filters[filterId]) return
    filters[filterId] = { value: [],  selected: this.chosenFilters().includes(filterId) }
  },
  getDefaultSort() {
    return {
      selected: true,
      sort1: { filterId: null, order: null },
      sort2: { filterId: null, order: null }
    }
  },
  buildValuePart(value) {
    if (value === null) return null
    if (value.value) {
      return value.value
    } else if (value) {
      return value
    }
  }

}
