<img src="https://raw.githubusercontent.com/apie-lib/apie-lib-monorepo/main/docs/apie-logo.svg" width="100px" align="left" />
<h1>count-words</h1>






 [![Latest Stable Version](http://poser.pugx.org/apie/count-words/v)](https://packagist.org/packages/apie/count-words) [![Total Downloads](http://poser.pugx.org/apie/count-words/downloads)](https://packagist.org/packages/apie/count-words) [![Latest Unstable Version](http://poser.pugx.org/apie/count-words/v/unstable)](https://packagist.org/packages/apie/count-words) [![License](http://poser.pugx.org/apie/count-words/license)](https://packagist.org/packages/apie/count-words) [![PHP Composer](https://apie-lib.github.io/projectCoverage/coverage-count-words.svg)](https://apie-lib.github.io/projectCoverage/app/packages/count-words/index.html)  

[![PHP Composer](https://github.com/apie-lib/count-words/actions/workflows/php.yml/badge.svg?event=push)](https://github.com/apie-lib/count-words/actions/workflows/php.yml)

This package is part of the [Apie](https://github.com/apie-lib) library.
The code is maintained in a monorepo, so PR's need to be sent to the [monorepo](https://github.com/apie-lib/apie-lib-monorepo/pulls)

## Documentation
This small package contains a class to count words in a text. All words are returned lowercase.

Usage
```php
use Apie\CountWords\WordCounter;

var_dump(WordCounter::countFromString('This is the text with many words like the or and'));
```
This will echo:
```
array(10) {
  ["this"]=>
  int(1)
  ["is"]=>
  int(1)
  ["the"]=>
  int(2)
  ["text"]=>
  int(1)
  ["with"]=>
  int(1)
  ["many"]=>
  int(1)
  ["words"]=>
  int(1)
  ["like"]=>
  int(1)
  ["or"]=>
  int(1)
  ["and"]=>
  int(1)
}
```
