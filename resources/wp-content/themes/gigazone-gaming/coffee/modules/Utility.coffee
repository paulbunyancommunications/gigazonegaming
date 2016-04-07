define ['jquery'], ($) ->
  Utility = {}

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

  Utility.makeid = ->
    text = ''
    possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
    i = 0
    while i < 12
      text += possible.charAt(Math.floor(Math.random() * possible.length))
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

  return Utility