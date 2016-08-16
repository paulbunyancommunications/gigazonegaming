define ['jquery'], ($) ->
  duplicate = {}

  duplicate.init = ->
    duplicate.doDuplicateContent()

  # Duplicate content from one block to another
  # this will look for an input with the class .duplicate and then find the matching ID to its value
  # if found the content from the targeted block with the ID will be copied to the block with the input
  duplicate.doDuplicateContent = ->
    values = $('input.duplicate')
    if values.length
      $.each(values, ->
        thisValue = $(this)
        if $('#' + thisValue.val()).length
          thisValue.after($('#' + thisValue.val()).html())
      )
  duplicate