require ['init'], () ->
  # DOM ready
  $ ->

    if $('.doForm').length
      require ['form'], (form) ->
        form.init()

    if $('#theIframe', window.parent.document).length
      require ['theIframe'], (theIframe) ->
        theIframe.init()

    if $('#searchBar').length
      require ['searchBar'], (searchBar) ->
        searchBar.init()
  return