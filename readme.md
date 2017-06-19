# MasterMind

This module implements a simple variant of the popular game MasterMind.  
The purpose of this module is to practice testing in the context of Magento 2 module development.

## How to use

Visit the module page at http://example.com/mastermind  
The repository has 4 tags:

* `0-complete`: The fully functional module. Same as the HEAD of the master branch. Use this as a reference.
* `1-no-guess-evaluator`: The complete module missing only the `GuessEvaluatorInterface` implementation.
* `2-no-mastermind`: The complete module missing the `GuessEvaluatorInterface` and the `MasterMindInterface` implementations.
* `3-no-evaluate-action`: The complete module missing the `GuessEvaluatorInterface`, the `MasterMindInterface` and the `\Space48\MasterMind\Controller\Evaluate\Index` implementations.

Depending on the available time, three classes can be implemented:

## `\Space48\MasterMind\Model\GuessEvaluatorInterface`  

`GuessEvaluatorInterface::evaluate(string[] $targetColors, string[] $guessColors): string`

Compares the guess colors with the target colors and returns the value of one of the constants:

1. `self::PERFECT` if all colors and positions match in both arrays.
2. `self::ONE_CORRECT_POSITION` if one of the guess colors matches the position in the target colors.
3. `self::ONE_WRONG_POSITION` if one of the guess colors matches but not in the same position.
4. `self::NO_MATCH` if no guess color matches a target color.

The purpose of this class is to practice testing a pure function without collaborators.

## `\Space48\MasterMind\Model\MasterMindInterface`

`MasterMindInterface::playerGuesses(string[] $colors): string`

Receives an array of color strings.  
Responsible for picking the target colors if there none are set.  
Evaluates the guess and returns the string in the result message map for the evaluation result.  
If the evaluation result is `Perfect` reset the target colors before returning the result string so state is ready for a new game.

The implementation can use `\Space48\MasterMind\Session\ColorStorage` to keep track of the current target colors, and `\Space48\MasterMind\Config\Colors` to retrieve the list of available colors and to pick a set of target colors.

The purpose of this class is to practice testing methods using mock collaborators.

## `\Space48\MasterMind\Controller\Evaluate\Index`

`Index::execute(): Magento\Framework\Controller\Result\Json`

Retrieves the passed guess from the request, passes it to `MasterMindInterface::playerGuesses()` and returns the result as a JSON encoded string.  

The purpose of this class is to practice testing a layer supertype (a class extending an abstract M2 core class) using integration tests.
    
