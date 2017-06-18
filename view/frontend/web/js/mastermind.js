define(['ko', 'jquery'], function (ko, $) {
    "use strict";
    return function (config) {
        var guess1 = ko.observable('');
        var guess2 = ko.observable('');
        var evaluationResult = ko.observable();

        function evaluateGuess(color1, color2) {
            $.ajax(config.base_url + 'mastermind/evaluate', {
                method: 'POST',
                data: { guess: [color1, color2] }
            })
            .success(function (response) {
                console.log(response);
                evaluationResult(response);
            })
            .error(function (reason) {
                evaluationResult('Error evaluating guess: ' + reason.status + ' ' + reason.statusText);
            });
        }

        function resetGuess() {
            guess1('');
            guess2('');
        }

        return {
            colors: config.colors,
            guess1: guess1,
            guess2: guess2,
            instruction: ko.computed(function () {
                if (!guess1() && !guess2()) {
                    return 'Guess 2 colors.';
                } else if (guess1() && !guess2()) {
                    return 'Choose a second color.';
                } else if (guess1() && guess2()) {
                    return evaluationResult();
                }
            }),
            guess: function (color) {
                if (guess1() && guess2()) resetGuess();

                if (!guess1() && !guess2()) {
                    guess1(color);
                } else {
                    guess2(color);
                    evaluationResult('Evaluating...');
                    evaluateGuess(guess1(), guess2());
                }
            }
        };
    };
});
