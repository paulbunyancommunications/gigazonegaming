define ['jquery'], ($) ->
  searchBar = {}

  searchBar.init = ->
    searchBar.doSearch()
    return

  searchBar.doSearch = ->
    $('#searchBar').change ->
      $(this).val()
    return

  searchBar