# MasterMind

This module implements a simple variant of the game MasterMind.  
The purpose of this module is to practice testing in the context of Magento 2 module development.

## How to install

Run the following composer commands in the Magento 2 root directory:
```
$ composer config repositories.mastermind-exercise git git@github.com:Vinai/space48-module-master-mind.git
$ composer require space48/module-mastermind:dev-master
```

## How to play

To play in the browser visit the module page at `http://[magento base url]/mastermind`, or  
to play on the console, run `bin/magento play:mastermind`.

## How to use 

The purpose of this module is to allow practicing different aspects of testing related to Magento 2 module development.
  
The repository has 4 tags:

* `0-complete`: The fully functional module. Same as the HEAD of the master branch. Use this as a reference.
* `1-no-guess-checker`: The complete module missing only the `Model\GuessCheckerInterface` implementation.
* `2-no-mastermind`: The complete module, except for the `Model\GuessCheckerInterface` and the `Model\MasterMindGameInterface` implementations.
* `3-no-evaluate-action`: The complete module, except for the `Model\GuessCheckerInterface`, the `Model\MasterMindGameInterface` and the `\Space48\MasterMind\Controller\Evaluate\Index` implementations.

Depending on the available time, run `git checkout [tag-name]` and implement the missing classes.
Each of 3 classes that can be implemented allows practicing different aspects related to testing.  

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

This method is responsible for coordinating the game round.

Receives the player guess as an array of color strings.

1. If no target colors are set, pick the target colors.
2. Check the guess, and return an array with the check result and also the guess count for the current game.

   Example return array structure:
   ```php
   [
       self::KEY_CHECK_RESULT => GuessCheckerInterface::NO_MATCH,
       self::KEY_GUESS_COUNT  => 5
   ]
   ```

3. If the check result is `GuessCheckerInterface::PERFECT`, reset the game state before returning.
4. Each call to `playerGuesses()` increments the guess count for the current game.

The following classes should be used as collaborators:

- `\Space48\MasterMind\Model\GameStateInterface` (target colors, guess count, reset game state)
- `\Space48\MasterMind\Model\GuessCheckerInterface` (compare guess to target colors)
- `\Space48\MasterMind\Config\Colors` (pick new target colors)
  
The purpose of this class is to practice testing while replacing the collaborators with test doubles (a.k.a. "mocks").  
Finding all required example test cases is an important part of the task.

## `\Space48\MasterMind\Controller\Evaluate\Index`

`Index::execute(): Magento\Framework\Controller\Result\Json`  

Retrieves the player guess from the request, passes it to `MasterMindGameInterface::playerGuesses()`,
builds a string from the returned message and guess count, and finally returns that string as a JSON response.  

The purpose of this class is to practice testing a class extending a layer supertype (a class extending an abstract M2 core class) using integration tests.


## Copyright:

(c) Vinai Kopp 2017
