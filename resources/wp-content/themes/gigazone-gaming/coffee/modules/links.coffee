define ['jquery','variables'], ($, variables) ->

  links = {}

  links.init = ->
    # open all external urls in new window
    $('a').not('[href*="mailto:"]').not('[href*="tel:"]').each ->
      if !(new RegExp( '\\b' + variables.siteDomains.join('\\b|\\b') + '\\b') ).test(this.href)
        $(this).attr('target', '_blank')
    return

  return links