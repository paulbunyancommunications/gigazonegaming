require [
  'jquery'
  'bootstrap'
], ($) ->
  # DOM ready
  $ ->

    require ['links'], (links) ->
      links.init()
      return

    # if the wordpress nav bar exists, push the content of the page down so it doesn't overlap the masthead
    if $('#wpadminbar').length
      $('.masthead-container').css({'margin-top': $('#wpadminbar').outerHeight()})

    if $('.main-navigation-container').length
      require ['jquery', 'mainNavigation'], ($, mainNavigation) ->
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