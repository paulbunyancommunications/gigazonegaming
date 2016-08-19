define ['jquery'], ($) ->
  posts = {}

  posts.init = ->
    posts.titleHoverState()
    return posts

  # set hover state on title if hovering over thumbnail and vice versa
  posts.titleHoverState = ->
    titleClass = '.title-page'
    titleThumnailClass = '.next-to-title-page'
    titles = $(titleClass)
    titleThumbnails = $(titleThumnailClass)

    titles.each ->
      thisTitle = $(this)
      thisTitle.hover(
        (ev) ->  $(this).siblings(titleThumnailClass).children('a').addClass("hover")
        (ev) ->  $(this).siblings(titleThumnailClass).children('a').removeClass("hover")
      )
      return
    titleThumbnails.each ->
      thisTitleThumbnail = $(this)
      thisTitleThumbnail.hover(
        (ev) ->  $(this).siblings(titleClass).children('a').addClass("hover")
        (ev) ->  $(this).siblings(titleClass).children('a').removeClass("hover")
      )
      return
    return
  posts
