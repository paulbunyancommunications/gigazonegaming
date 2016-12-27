require(['vue', 'axios', 'jquery', 'underscore', 'Utility'], function (Vue, axios, $, _, Utility) {

    /**
     *
     * @param originals
     * @param blacklist
     * @returns {Array}
     */
    function flattenMessages(originals, blacklist) {
        return Utility.flattenMessages(originals, blacklist);
    }

    /**
     * wait for milliseconds
     * http://stackoverflow.com/a/27870155/405758
     * @param millis
     */
    function pauseBrowser(millis) {
        var date = Date.now();
        var curDate = null;
        do {
            curDate = Date.now();
        } while (curDate-date < millis);
    }

    /**
     * Strip html tags
     * http://stackoverflow.com/a/822486/405758
     * @param html
     * @returns {string|string}
     */
    function strip(html)
    {
        var tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }

    /**
     * Filter out html from string
     */
    Vue.filter('stripHtmlTags', function(value){
        return strip(value)
    });

    /**
     * CreatePlayerModal
     */
    Vue.component('createPlayerModal', {
        template: "#createPlayer"
    });

    /**
     * Generate a section title
     */
    Vue.component('section-title', {
        props: ['id', 'label'],
        template: '<h2 class="txt-color--shadow" :id="id">{{ label }}</h2>'
    });

    /**
     * Populate array that will be used for initial data in vue object
     * @returns {{}}
     */
    function populateVueData() {
        var vueData = {},
            modelTypes = ['player', 'tournament', 'score'],
            messageTypes = ['success', 'error'];
        vueData['modelTypes'] = modelTypes;
        vueData['messageTypes'] = messageTypes;
        // for each of the form inputs
        for (var a = 0; a < modelTypes.length; a++) {
            var old = $('#old' + Utility.capitalizeFirstLetter(modelTypes[a])).val();
            vueData[modelTypes[a] + 's'] = [];
            vueData[modelTypes[a]] = old;
            vueData[modelTypes[a] + 'Old'] = old;
            vueData['loading' + Utility.capitalizeFirstLetter(modelTypes[a]) + 's'] = true;
            vueData['loadingNew' + Utility.capitalizeFirstLetter(modelTypes[a]) + 'Form'] = false;
            vueData['loadingUpdate' + Utility.capitalizeFirstLetter(modelTypes[a]) + 'Form'] = false;
            vueData[modelTypes[a] + 'Select'] = $('#' + modelTypes[a] + 'Old').val();
            vueData['unique' + Utility.capitalizeFirstLetter(modelTypes[a]) + 'ScoreSelect'] = '';
        }
        // for each of the message types
        for (var b = 0; b < messageTypes.length; b++) {
            vueData[messageTypes[b]] = [];
            // player, tournament and score (might not be used) modal success/errors
            for (var c = 0; c < modelTypes.length; c++) {
                vueData[modelTypes[c] + 'Modal' + Utility.capitalizeFirstLetter(messageTypes[b])] = [];
            }
        }

        return vueData;
    }

    new Vue({
        el: '#root',
        data: populateVueData(),
        mounted: function () {
            var vm = this;
            // Get players from api
            axios.get('/app/api/manage/player/all')
                .then(function (response) {
                    vm.players = response.data;
                    vm.loadingPlayers = false;
                    vm.player = $('#oldPlayer').val()
                })
                .catch(function (error) {
                    vm.loadingPlayers = false;
                    vm.error = vm.error.concat(flattenMessages(error.response.data));

                });
            // Get tournaments from api
            axios.get('/app/api/manage/tournament/all')
                .then(function (response) {
                    vm.tournaments = response.data
                    vm.loadingTournaments = false;
                    vm.tournament = $('#oldTournament').val()
                })
                .catch(function (error) {
                    vm.loadingTournaments = false;
                    vm.error = vm.error.concat(flattenMessages(error.response.data));
                });

            // Get scores from api
            axios.get('/app/api/manage/score/all')
                .then(function (response) {
                    vm.scores = response.data
                    vm.loadingScores = false;
                })
                .catch(function (error) {
                    vm.loadingScores = false;
                    vm.error = vm.error.concat(flattenMessages(error.response.data));
                    console.log(error);
                });
        },
        methods: {
            /**
             * Create a new score
             * This will take all the form inputs from the #createNewScoreForm form and
             * send them to the backend controller for creating the record in the database.
             * @param e
             */
            createNewForm: function (e) {

                e.preventDefault();
                var vm = this,
                    // The type of form we're working with, should be in the modelTypes array
                    thisFormIdentifier = e.target.dataset.form,

                    // Form type, create, update, modal
                    thisFormType = e.target.dataset.formtype,

                    // Form id that we'll be working with
                    thisFormId = e.target.dataset.formid,

                    // form fields that will be passed in the ajax request
                    fields = {};

                // get the form we're working with
                var form = $('#' + thisFormId);

                // for each of the message types reset messages
                for (var i = 0; i < vm.messageTypes.length; i++) {
                    vm[vm.messageTypes[i]] = [];
                    // player, tournament and score (might not be used) modal success/errors
                    for (var f = 0; f < vm.modelTypes.length; f++) {
                        vm[vm.modelTypes[f] + 'Modal' + Utility.capitalizeFirstLetter(vm.messageTypes[i])] = [];
                    }
                }

                // If the form is not in the acceptable
                // form list then fail out.
                if ($.inArray(thisFormIdentifier, vm.modelTypes) === -1) {
                    vm.error = flattenMessages({errors: ['Unknown form type ' + thisFormIdentifier + '.']}, []);
                    return;
                }

                // set loading for this form to true to show the spinner
                vm['loadingNew' + Utility.capitalizeFirstLetter(thisFormIdentifier) + 'Form'] = true;


                // collect all the form inputs and add
                // them to the fields array
                // http://stackoverflow.com/a/5603151/405758
                form.find("input, textarea, select").each(function () {
                    fields[this.name] = $(this).val();
                });

                // send form through axios and return success and errors
                axios({
                    method: 'post',
                    url: form.attr('action'),
                    data: fields,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-XSRF-TOKEN': $('meta[name="request_token"]').attr('content')
                    },
                    responseType: 'json'
                })
                    .then(function (response) {

                        vm['loadingNew' + Utility.capitalizeFirstLetter(thisFormIdentifier) + 'Form'] = false;

                        // if form is 'score' (the base form) then add the message to
                        // the main message block, otherwise add the message to
                        // the correct spot (modal probably)
                        if (thisFormIdentifier === 'score') {
                            vm['success'] = flattenMessages(response.data, ['id', 'redirect', 'model'])
                        } else {
                            vm[thisFormIdentifier + 'ModalSuccess'] = flattenMessages(response.data, ['id', 'redirect', 'model'])
                        }

                        // set loading to false
                        vm['loadingNew' + Utility.capitalizeFirstLetter(thisFormIdentifier) + 'Form'] = false;

                        // set the old value to this value
                        vm[thisFormIdentifier + 'Old'] = vm[vm.modelTypes[i] + 'Select'];

                        // if type iof modal, take the return data and
                        // add it to the select list that matches
                        if (thisFormType === 'modal') {

                        }
                        // if the thisFormIdentifier is 'score' then redirect to the edit page
                        if (thisFormIdentifier === 'score' && thisFormType === 'create') {
                            // pause browser for 2 seconds to show the message before redirecting
                            pauseBrowser(2000);
                            window.location.href = response.data.redirect[0];
                        }

                    })
                    .catch(function (error) {
                        if (thisFormIdentifier === 'score') {
                            vm['error'] = flattenMessages(error.response.data, ['id', 'redirect', 'model'])
                        } else {
                            vm[thisFormIdentifier + 'ModalError'] = flattenMessages(error.response.data, ['id', 'redirect', 'model'])
                        }
                        vm['loadingNew' + Utility.capitalizeFirstLetter(thisFormIdentifier) + 'Form'] = false;
                    });
            },
            /**
             * Take and send the update for a score to the database
             * @param e
             */
            updateScore: function (e) {
                var vm = this;
                e.preventDefault();
                vm.loadingNewScoreForm = true;
                vm.success = [];
                vm.error = [];
                var form = $('#updateScoreForm');
                axios({
                    method: 'put',
                    url: form.attr('action'),
                    data: {
                        score: vm.score
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-XSRF-TOKEN': $('meta[name="request_token"]').attr('content')
                    },
                    responseType: 'json'
                })
                    .then(function (response) {
                        vm.loadingNewScoreForm = false;
                        vm.success = flattenMessages(response.data, ['id', 'redirect'])
                        vm.loadingNewScoreForm = false;

                    })
                    .catch(function (error) {
                        console.log(error);
                        vm.error = flattenMessages(error.response.data)
                        vm.loadingNewScoreForm = false;
                    });
            }
        },
        computed: {
            /**
             * Get player name from the players list
             */
            playerName: function () {
                if (this.players[(this.playerSelect) - 1] !== undefined) {
                    return this.players[(this.playerSelect) - 1].name
                }
            },
            /**
             * get the tournament name from the tournaments list
             */
            tournamentName: function () {
                if (this.tournaments[(this.tournamentSelect) - 1] !== undefined) {
                    return this.tournaments[(this.tournamentSelect) - 1].name
                }
            },
            /**
             * Find tournaments that have scores
             */
            uniqueScoresByTournament: function () {

                var found = [],
                    vm = this;
                foundList = [];

                vm.loadingScores = true;

                _.each(this.scores, function (ele) {
                    if (found.indexOf(ele.tournament.id) === -1) {
                        found.push(ele.tournament.id);
                    }
                });
                (function waitForFound() {
                    if (found.length === 0) {
                        setTimeout(waitForFound, 100);
                    } else {
                        axios.get('/app/api/manage/tournament/find/' + found.join())
                            .then(function (tournament) {
                                foundList.concat(tournament);
                                // if only one result then return the value into an option,
                                // otherwise iterate over each and add a option.
                                if (found.length === 1) {
                                    document.getElementById('uniqueTournamentSelect').insertAdjacentHTML('beforeend', '<option id="' + tournament.data.id + '">' + tournament.data.name + '</option>')
                                } else {
                                    $.each(tournament.data, function (key, tournament) {
                                        document.getElementById('uniqueTournamentSelect').insertAdjacentHTML('beforeend', '<option id="' + tournament.id + '">' + tournament.name + '</option>')
                                    })
                                }
                                vm.loadingScores = false;
                            })
                            .catch(function (error) {
                                vm.error = vm.error.concat(flattenMessages(error.response.data));
                                vm.loadingScores = false;
                            });
                    }
                })();
            }
        }
    });
});
