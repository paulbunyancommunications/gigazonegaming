define [], () ->
  variables = {}

  variables.tabletMax  = 992

  variables.boostrapBreakPoints = [480, 768, 992, 1200];

  variables.rotatorSpeed = 900
  variables.rotatorTimeout = 6500

  variables.siteDomains = [window.location.host, 'redlakeelectric.local','redlakeelectric.com' ];

  return variables