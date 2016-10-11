require ['init'], () ->
  # DOM ready
  $ ->

    if $('.doForm').length
      require ['form'], (form) ->
        form.init()

  return