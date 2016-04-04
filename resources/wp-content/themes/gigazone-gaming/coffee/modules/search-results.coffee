define ['jquery','functions'], ($, Functions) ->

  searchResults = {}

  # value of the search term sent from form
  searchResults.searchValue = $('.search-value').text()

  (->

    $.expr[':'].containsNC = (elem, index, match) ->
      (elem.textContent or elem.innerText or $(elem).text() or '').toLowerCase().indexOf((match[3] or '').toLowerCase()) >= 0

    return
  ) $

  searchResults.init = ->
    searchResults.findQueryInContent()
    return

  searchResults.findQueryInContent = ->
    $(".search-result-container:containsNC(" + searchResults.searchValue + ")").html (_, html)->
      return html.split(searchResults.searchValue).join('<span class="search-result-highlight">' + searchResults.searchValue + '</span>');


  return searchResults