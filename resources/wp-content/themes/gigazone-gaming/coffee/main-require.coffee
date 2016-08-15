require [
  'jquery'
  'bootstrap'
], ($) ->
  # DOM ready
  $ ->

    require ['posts'], (posts) ->
      posts.init()
      return
      
    require ['links'], (links) ->
      links.init()
      return

      
    if $('.main-navigation-container').length
      require ['mainNavigation'], (mainNavigation) ->
        mainNavigation.init()
        return

    if $('.responsive-image').length
      require ['responsiveImages'], (responsiveImages) ->
        responsiveImages.int()
        return

    if $('.photo-rotator').length
      require ['photoRotator'], (photoRotator) ->
        photoRotator.int()
        return

    if $('#footer-content').length and $('#main-container').length and $('#footer-container')
      require ['stickyFooter'], (stickyFooter) ->
        stickyFooter.init()
        return

    if $('.search-result-container').length
      require ['searchResults'], (searchResults) ->
        searchResults.init()
        return

    if $('.doForm').length
      require ['form'], (form) ->
        form.init()

    return
  return