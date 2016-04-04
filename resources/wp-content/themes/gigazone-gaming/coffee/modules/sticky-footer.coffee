define ['jquery','variables'], ($, variables) ->
  stickyFooter = {}

  # main body content container
  stickyFooter.mainContainer = $("#main-container")
  # main footer container
  stickyFooter.footerContainer = $("#footer-container")
  # footer content container
  stickyFooter.footerContent = $("#footer-content")

  # initialize
  stickyFooter.init = ->
    stickyFooter.reset()

  # reset size of footer offset so footer "sticks" to the bottom
  stickyFooter.reset = ->
    height = stickyFooter.footerContent.height();
    stickyFooter.mainContainer.css({'margin-bottom':'-'+height+'px', 'padding-bottom': height+'px' })
    stickyFooter.footerContainer.height(height)

  # on window reset rerun the reset function
  $(window).on 'resize', ->
    stickyFooter.reset()
    return

  return stickyFooter

