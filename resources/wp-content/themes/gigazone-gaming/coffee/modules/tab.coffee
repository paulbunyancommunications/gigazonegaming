define ['jquery'], ($) ->
  tab = {}

  tab.init = ->
    tab.showContent()

  tab.showContent = ->

    # iterate though all the tabs on screen an prep for "tabbing" using boostrap 3's tabbed navigation
    tabs = $('ul[role="tablist"]>li>a[role="tab"]')
    for i in [0..tabs.length]
      $('ul[role="tablist"]>li:eq(' + i + ') a[role="tab"]').click((e)->
        e.preventDefault()
        $(this).tab('show')
      )
  tab

