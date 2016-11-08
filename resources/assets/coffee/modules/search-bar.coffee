define ['jquery', 'form'], ($, form) ->
  searchBar = {}
  searchBar.resultsHolder = $('#searchResults')

  searchBar.init = ->
    form.getCsrfToken()
    if !$('#searchbox-response-container').length
      $('body').append('<div id="searchbox-response-container" style="display: none;"></div>')

    searchBar.dataHolder = $('#searchbox-response-container')
    # Listen DOM changes on dataholder
    searchBar.dataHolder.bind("DOMSubtreeModified", searchBar.updateResults);
    return

  searchBar.updateResults = ->
    try
      searchBar.resultsHolder.slideUp 250
      searchBar.resultsHolder.html('')
      data = $.parseJSON(searchBar.dataHolder.text())
      searchBar.resultsHolder.html(data.result)
      searchBar.resultsHolder.slideDown 250
    catch
      return

  searchBar