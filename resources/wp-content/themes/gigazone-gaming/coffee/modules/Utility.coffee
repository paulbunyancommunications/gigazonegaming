define ['jquery', 'underscore'], ($, _) ->
  Utility = {}

  Utility.possibleId = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"

  Utility.slugify = (string) ->
    slug = '';
    trimmed = $.trim(string);
    slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
    replace(/-+/g, '-').
    replace(/^-|-$/g, '');
    slug.toLowerCase();

  Utility.titleFromSlug = (slug) ->
    words = slug.split('-')
    i = 0
    while i < words.length
      word = words[i]
      words[i] = word.charAt(0).toUpperCase() + word.slice(1)
      i++
    words.join ' '

  # http://stackoverflow.com/a/1026087/405758
  Utility.capitalizeFirstLetter = (string) ->
    string.charAt(0).toUpperCase() + string.slice(1)

  Utility.makeid = ->
    text = "";
    i = 0;
    while i < 12
      text += Utility.possibleId.charAt(Math.floor(Math.random() * Utility.possibleId.length))
      i++
    text

  Utility.getXsrfToken = ->
    cookies = document.cookie.split(';')
    token = ''
    i = 0
    while i < cookies.length
      cookie = cookies[i].split('=')
      if cookie[0] == 'XSRF-TOKEN'
        token = decodeURIComponent(cookie[1])
      i++
    token

  ###*
  # Flatten messages
  # @param originals
  # @param blacklist
  # @returns {Array}
  ###

  Utility.flattenMessages = (originals, blacklist) ->
    list = []
    keys = _.keys(originals)
    i = 0
    while i < keys.length
      if blacklist.indexOf(keys[i]) != -1
        i++
        continue
      list = list.concat(_.flatten(originals[keys[i]]))
      i++
    list


  Utility