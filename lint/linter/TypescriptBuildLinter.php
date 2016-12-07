<?php
/*
 Copyright 2016-present The Material Motion Authors. All Rights Reserved.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */

/** Uses tsc to build typescript source and emit any errors. */
final class TypescriptBuildLinter extends ArcanistExternalLinter {

  public function getInfoName() {
    return 'tsc';
  }

  public function getInfoURI() {
    return '';
  }

  public function getInfoDescription() {
    return pht('Run tsc against typescript source.');
  }

  public function getLinterName() {
    return 'tsc';
  }

  public function getLinterConfigurationName() {
    return 'tsc';
  }

  public function getDefaultBinary() {
    return 'tsc';
  }

  public function getInstallInstructions() {
    return pht('Install tsc with `npm install typescript -g`');
  }

  public function shouldExpectCommandErrors() {
    return true;
  }

  protected function getDefaultMessageSeverity($code) {
    return ArcanistLintSeverity::SEVERITY_ERROR;
  }

  public function getVersion() {
    list($stdout) = execx('%C --version', $this->getDefaultBinary());

    $matches = array();
    $regex = '/(?P<version>\d+\.\d+\.\d+)/';
    if (preg_match($regex, $stdout, $matches)) {
      return $matches['version'];
    } else {
      return false;
    }
  }

  protected function getMandatoryFlags() {
    $flags = array(
      '--noEmit'
    );
    return $flags;
  }

  protected function parseLinterOutput($path, $err, $stdout, $stderr) {
    $lines = explode("\n", $stdout);
    $messages = array();
    foreach ($lines as $line) {
      if (preg_match("/(?P<path>.+)\((?P<line>.+),(?P<char>.+)\): error (?P<code>.+): (?P<message>.+)/", $line, $matches)) {
        $messages []= id(new ArcanistLintMessage())
          ->setPath($path)
          ->setLine($matches['line'])
          ->setChar($matches['char'])
          ->setCode($matches['code'])
          ->setSeverity($this->getLintMessageSeverity($matches['code']))
          ->setName('tsc failure')
          ->setDescription($matches['message']);
      }
    }
    return $messages;
  }
}
