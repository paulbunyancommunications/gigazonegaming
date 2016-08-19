define ['jquery'], ($) ->
  clickable = {}

  clickable.init = ->
    clickable.makeClickable()
    return

  # make a container clickable by using it's child link as the target
  clickable.makeClickable = ->
    elements = $('.clickable')
    for i in [0..elements.length]
      $('.clickable:eq(' + i + ')').click((e)->
        link = $(this).find('a')
        if link
          console.log(link)
          window.location.replace(link[0].href)
      )

  clickable