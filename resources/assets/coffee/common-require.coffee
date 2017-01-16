paths = {}
themePath = "/app/content"

paths.jquery = "../../../bower_components/jquery/dist/jquery.min"
paths.bootstrap = "../libraries/bootstrap/js/bootstrap.min"
paths.underscore = "../../../bower_components/underscore/underscore-min"
paths.handlebars = "../../../bower_components/handlebars/handlebars.min"
paths.select2 = "../../../bower_components/select2/dist/js/select2.full.min"
paths.vue = "../../../app/content/libraries/vue/vue"
paths.axios = "../../../app/content/libraries/axios/axios"
# modules
paths.init = "init"
paths.Utility = "../../../wp-content/themes/gigazone-gaming/js/modules/Utility"
paths.form = "../../../wp-content/themes/gigazone-gaming/js/modules/form"
paths.searchBar = "modules/search-bar"
paths.theIframe = "modules/the-iframe"

require =
  baseUrl: themePath+'/js'
  paths: paths
  waitSeconds: 0
  shim:
    "bootstrap":
      "jquery"
    "underscore":
        exports:
            "_"
    "handlebars":
        exports:
            "Handlebars"
  priority:
      "jquery"