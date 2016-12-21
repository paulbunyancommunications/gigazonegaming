require(['vue', 'axios', 'jquery', 'underscore'], function(Vue, axios, $, _) {

    /**
     * Form Title
     * Example usage:
     * <form-title id="update-player-score-title" label="Update Player Score"></form-title>
     */
    Vue.component('form-title', {
        props: ['id','label'],
        template: '<h2 class="txt-color--shadow" :id="id">{{ label }}</h2>'
    });

    /**
     * Field value display without the input
     * Example usage:
     * <field-just-value lable="Player" field="player" value="value"></field-just-value>
     */
    Vue.component('field-just-value', {
        props: ['label','field','value'],
        template:'<div><div class="col-xs-3 text-right">' +
                '<p class="offset-to-label font--display">{{ label }}</p>' +
            '</div>' +
            '<div class="col-xs-9 text-left" :id="field">' +
                '<p class="bold">{{ value }}</p>' +
            '</div></div>'
    });
    /**
     * Bootstrap style select list
     * Pass in the field name and label, value
     * whether the mounted data is loaded
     * and the options.
     * Example usage:
     * <select-list field="tournament" label="Tournament" :loading="loadingTournaments" :options="tournaments"></select-list>
     */
    Vue.component('select-list', {
        props: ['label','field','loading','options','selected','model'],
        template: '<div class="form-group">' +
            '<label :for="field" class="control-label col-xs-3">{{ label }}' +
                '<span v-show="loading" class="txt-color--branding">&nbsp;<i class="fa fa-refresh fa-spin"></i></span>' +
            '</label>' +
            '<div class="col-xs-9">' +
                '<select :name="field" :id="field" class="form-control" :v-model="model">' +
                    '<option>Select a {{ label }}</option>' +
                    '<template v-for="option in options">' +
                        '<option v-if="option.id == selected" :value="option.id" selected="selected">{{ option.name }}</option><option v-else :value="option.id">{{ option.name }}</option>' +
                    '</template>'+
                '</select>'+
            '</div>' +
        '</div>',
    });
    /**
     * Same as above, but for the score select
     */
    Vue.component('select-list-scores', {
        props: ['label','field','loading','options','selected','model'],
        template: '<div class="form-group">' +
            '<label :for="field" class="control-label col-xs-3">{{ label }}' +
                '<span v-show="loading" class="txt-color--branding">&nbsp;<i class="fa fa-refresh fa-spin"></i></span>' +
            '</label>' +
            '<div class="col-xs-9">' +
                '<select :name="field" :id="field" class="form-control" :v-model="model">' +
                    '<option>Select a {{ label }}</option>' +
                    '<template v-for="option in options">' +
                        '<option v-if="option.id == selected" :value="option.tournament.id" selected="selected">{{ option.tournament.name }}</option><option v-else :value="option.tournament.id">{{ option.tournament.name }}</option>' +
                    '</template>'+
                '</select>'+
            '</div>' +
        '</div>',
    });

    new Vue({
        el: '#root',
        data: {
            players: [],
            loadingPlayers: true,
            player: '',
            tournaments: [],
            loadingTournaments: true,
            tournament: '',
            scoreCreated: false,
            scores: [],
            loadingScores: true
        },
        mounted: function() {
            var vm = this;
            // Get players from API
            axios.get('/app/api/manage/player/all')
                .then(function(response) {
                    vm.players = response.data;
                    vm.loadingPlayers = false;
                    vm.player = $('#old_player').val()
                })
                .catch(function (error) {
                    vm.loadingPlayers = false;
                    console.log(error);
                });
            // Get tournaments from api
            axios.get('/app/api/manage/tournament/all')
                .then(function(response) {
                    vm.tournaments = response.data
                    vm.loadingTournaments = false;
                    vm.tournament = $('#old_tournament').val()
                })
                .catch(function (error) {
                    vm.loadingTournaments = false;
                    console.log(error);
                });

            // Get tournaments from api
            axios.get('/app/api/manage/score/all')
                .then(function(response) {
                    vm.scores = response.data
                    vm.loadingScores = false;
                })
                .catch(function (error) {
                    vm.loadingScores = false;
                    console.log(error);
                });
        },
        computed: {
            uniqueScoresByTournament: function() {
                var found = [];
                var filteredScoreByTournament = []
                var vm = this;
                _.each(this.scores, function(ele) {
                   if (found.indexOf(ele.tournament.id) === -1) {
                       found.push(ele.tournament.id);
                   }
                });

                _.each(found, function(ele) {
                    vm.loadingScores = true;
                    axios.get('/app/api/manage/score/tournament/' + ele)
                        .then(function(response) {
                            filteredScoreByTournament.push(response.data[0])
                            vm.loadingScores = false;
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                });
                vm.scores = filteredScoreByTournament;
                return filteredScoreByTournament;
            }
        }
    })
});
