define [], () ->

  Functions = {}

  Functions.capitalizeFirstLetter = (string)->
    return string.charAt(0).toUpperCase() + string.slice(1);


  return Functions