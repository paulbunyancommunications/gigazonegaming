define [], () ->

  Functions = {}

  Functions.capitalizeFirstLetter = (string)->
    return string.charAt(0).toUpperCase() + string.slice(1);

  Functions.makeid = ->
    text = ''
    possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
    i = 0
    while i < 10
      text += possible.charAt(Math.floor(Math.random() * possible.length))
      i++
    text

  Functions