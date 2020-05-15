<?php

$finder = PhpCsFixer\Finder::create()->in(['./app', './config', './routes', './tests']);

return PhpCsFixer\Config::create()->setRules(['@PSR1' => true, '@PSR2' => true])->setFinder($finder);
