define ['jquery', 'variables'], ($, variables) ->
  mainNavigation = {}

  # initially set toggled state to false, the menu should not be toggled initially
  mainNavigation.toggled = false
  # main navigation list container, this is the list that will be toggled
  mainNavigation.container = $('.main-navigation')
  # toggle button
  mainNavigation.toggleButton = $('.main-navigation-toggler > a')

  # current section title selector (in sidebar)
  sectionTitle = $('#section-title')

  # current section sidebar nav holder
  sidebarSectionNav = $('#sidebar-section-nav')

  mainNavigation.addCarrot = ->

    $('ul > li', mainNavigation.container).each ->
      if($(this).children('ul').length)
        $(this).prepend('<div class="sub-navigation-caret"><a href="#"><i class="fa fa-caret-down"></i></a></div>')

        ###
        # append dropdown content to the sidebar sub navigation container,
        # the main menu will have the navigation in the correct order already
        ###
        thisSectionTitle = $(this).children('a').text()
        if thisSectionTitle == sectionTitle.text()
          sidebarSectionNav.html($(this).children('ul').html())
        ###
        # add "Hovering" class to the parent of the current hovering caret and sub-menu
        ###
        $('.sub-navigation-caret, .sub-menu').hover(
          (ev) ->  $(this).closest(".menu-item").addClass("hovering-on-child")
          (ev) ->  $(this).closest(".menu-item").removeClass("hovering-on-child")
        )
        return
    return



  ###* Toggle sub navigation on click of the
  # caret toggle button
  ###
  mainNavigation.toggleSubMenuWithCaret = ->
    $ ->
      $('.sub-navigation-caret').on 'click', ->
        thisCaret = $(this)
        sub = $(this).siblings('ul')
        if sub.is(':visible')
          sub.slideUp 100
          mainNavigation.toggleButtonDown(thisCaret)
        else
          sub.slideDown 100
          mainNavigation.toggleButtonUp(thisCaret)
        return
      return

  mainNavigation.toggleSubNavWithLink = ->
    $ ->
      $('.sub-navigation-caret').siblings('a').on 'click', ->
        thisLink = $(this)
        sub = $(this).siblings('ul')
        if sub.is(':visible')
          sub.slideUp 100
          mainNavigation.toggleButtonDown(thisLink.siblings('.sub-navigation-caret'))
        else
          sub.slideDown 100
          mainNavigation.toggleButtonUp(thisLink.siblings('.sub-navigation-caret'))
        return
      return

  mainNavigation.toggleButtonDown = (el)->
    el.html('<a href="#"><i class="fa fa-caret-down"></i></a>')
    return

  mainNavigation.toggleButtonUp = (el)->
    el.html('<a href="#"><i class="fa fa-caret-up"></i></a>')
    return



  ##
  # Initialize the menu
  ##
  mainNavigation.init = ->
    mainNavigation.toggler()
    mainNavigation.addCarrot()
    mainNavigation.toggleSubMenuWithCaret()
    mainNavigation.toggleSubNavWithLink()
    return

  ###* Toggle navigation on click of the
  # main navigation toggle button
  # reset to default when clicked again
  ###
  mainNavigation.toggler = ->
    $ ->
      mainNavigation.toggleButton.on 'click', ->
        ###* toggle clicked check each time ###
        mainNavigation.toggled ^= true
        if mainNavigation.container.is(':visible')
          mainNavigation.container.slideUp 100
        else
          mainNavigation.container.slideDown 100
        return

  ###* make sure we turn back on the
  # nav box if the window size gets
  # bigger than the tablet size
  ###

  $(window).resize ->
    windowWidth = $(window).width()
    if windowWidth <= variables.tabletMax and mainNavigation.toggled != false
      mainNavigation.container.show()
    else if windowWidth > variables.tabletMax
      mainNavigation.container.show()
      mainNavigation.toggled = false
      $('ul li > ul', mainNavigation.container).attr({'style':''})
    else
      mainNavigation.container.hide()
    return



  return mainNavigation