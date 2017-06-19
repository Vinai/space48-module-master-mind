# MasterMind

This module implements a simple variant of the game MasterMind.  
The purpose of this module is to practice testing in the context of Magento 2 module development.

## How to use

Visit the module page at `http://[magento base url]/mastermind`  
The repository has 4 tags:

* `0-complete`: The fully functional module. Same as the HEAD of the master branch. Use this as a reference.
* `1-no-guess-checker`: The complete module missing only the `GuessCheckerInterface` implementation.
* `2-no-mastermind`: The complete module, except for the `GuessCheckerInterface` and the `MasterMindGameInterface` implementations.
* `3-no-evaluate-action`: The complete module, except for the `GuessCheckerInterface`, the `MasterMindGameInterface` and the `\Space48\MasterMind\Controller\Evaluate\Index` implementations.

Depending on the available time, three classes can be implemented:  

## `\Space48\MasterMind\Model\GuessCheckerInterface`  

`GuessCheckerInterface::check(string[] $targetColors, string[] $guessColors): string`

Compares the guess colors with the target colors and returns the value of one of the constants:

1. `self::PERFECT` if all colors and positions match in both arrays.
2. `self::ONE_CORRECT_POSITION` if one of the guess colors matches the position in the target colors.
3. `self::ONE_WRONG_POSITION` if one of the guess colors matches but not in the same position.
4. `self::NO_MATCH` if no guess color matches a target color.

The purpose of this class is to practice testing a pure function without collaborators.  
Find examples to test all possible results. No test doubles are required.

## `\Space48\MasterMind\Model\MasterMindGameInterface`

`MasterMindGameInterface::playerGuesses(string[] $colors): mixed[]`  

Receives an array of color strings.  

This class is responsible for coordinating the game:  

1. If no target colors are set, pick the target colors.
2. Check the guess, and return an array with the string in the result message map for the check result and
   also the guess count for the current game.  

   Example return array structure:
   ```php
   [
       'check_result' => 'No match!',
       'guess_count'  => 5
   ]
   ```

   The return array keys are the values of the `self::KEY_CHECK_RESULT` and `self::KEY_GUESS_COUNT` constants.

3. If the check result is "Perfect", reset the game state before returning.
4. Each call to playerGuesses() increments the guess count for the current game.

The following classes should be used as collaborators:  

- `\Space48\MasterMind\Model\GameStateInterface` (target colors, guess count, reset game state)
- `\Space48\MasterMind\Model\GuessCheckerInterface` (compare guess to target colors)
- `\Space48\MasterMind\Config\Colors` (pick new target colors)
     
The purpose of this class is to practice testing while replacing the collaborators with test doubles (a.k.a. "mocks").

## `\Space48\MasterMind\Controller\Evaluate\Index`

`Index::execute(): Magento\Framework\Controller\Result\Json`  

Retrieves the player guess from the request, passes it to `MasterMindGameInterface::playerGuesses()`,
builds a string from the returned message and guess count, and finally returns that string as a JSON response.  

The purpose of this class is to practice testing a class extending a layer supertype (a class extending an abstract M2 core class) using integration tests.
