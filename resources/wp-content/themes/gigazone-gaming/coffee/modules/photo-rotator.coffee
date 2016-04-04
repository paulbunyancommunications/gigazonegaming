define ['jquery','variables', 'responsiveSlides'], ($, variables) ->
  photoRotator = {}

  photoRotator.int = ->
    return

  $ ->
    $('#front-photo-cycle').responsiveSlides
      auto: true
      random: false
      speed: variables.rotatorSpeed
      timeout: variables.rotatorTimeout
      nav: true
      pause: true
      nextText: '<div class="rotator-nav-item rotator-nav-item-right"><i class="fa fa-chevron-circle-right"></i></div>'
      prevText: '<div class="rotator-nav-item rotator-nav-item-left"><i class="fa fa-chevron-circle-left"></i></div>'


  return photoRotator