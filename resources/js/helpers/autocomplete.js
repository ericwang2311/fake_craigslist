import autocomplete from 'autocomplete.js'
import algolia from 'algoliasearch'

var index = algolia('0ZPPKN9ERW', 'e79836144df979f277394c258ec465a2')

export const listingsautocomplete = (selector, {
    categoryId,
    areaIds
}) => {
    var listings = index.initIndex('listings')

    var areaFilters = 'area_id = ' + areaIds.join(' OR area.id = ')
    var filters = areaFilters

    if (typeof categoryId !== 'undefined') {
        filters = filters + ' AND category.id != ' + categoryId
    }

    var sources = [{
        source: autocomplete.sources.hits(listings, {
            hitsPerPage: 5,
            filters: filters + ' AND live = 1'
        }),
        templates: {
            header: () => {
                if (typeof categoryId !== 'undefined') {
                    return '<div class="aa-suggestions-category">Other categories</div>';
                }
                return '<div class="aa-suggestions-category">All categories</div>';
            },
            suggestion(suggestion) {
                return '<span><a href="/' + suggestion.area.slug + '/' + suggestion.id + '">' + suggestion.title + '</a> in ' + suggestion.category.name + '</span> <span>' + suggestion.created_at_human + ' &bull; ' + suggestion.area.name + '</span>'
            }
        },
        displayKey: 'title',
        empty: '<div class="aa-empty">No listings found</a>'
    }];

    if (typeof categoryId !== 'undefined') {
        sources.unshift({
            source: autocomplete.sources.hits(listings, {
                hitsPerPage: 5,
                filters: '(' + areaFilters + ') AND category.id = ' + categoryId + ' AND live = 1'
            }),
            templates: {
                header: '<div class="aa-suggestions-category">This category</div>',
                suggestion(suggestion) {
                    return '<span><a href="/' + suggestion.area.slug + '/' + suggestion.id + '">' + suggestion.title + '</a> </span> <span>' + suggestion.created_at_human + ' &bull; ' + suggestion.area.name + '</span>'
                }
            },
            displayKey: 'title',
            empty: '<div class="aa-empty">No listings found</a>'
        })
    }

    return autocomplete(selector, {}, sources)
}
