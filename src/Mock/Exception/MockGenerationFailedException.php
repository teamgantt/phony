<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2016 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Mock\Exception;

use Eloquent\Phony\Mock\Builder\MockDefinition;
use EngineException;
use Error;
use Exception;

/**
 * Mock generation failed.
 */
final class MockGenerationFailedException extends Exception implements
    MockException
{
    /**
     * Construct a mock generation failed exception.
     *
     * @param MockDefinition                       $definition The definition.
     * @param string                               $source     The generated source code.
     * @param array<string,mixed>|null             $error      The error details.
     * @param Exception|Error|EngineException|null $cause      The cause, if available.
     */
    public function __construct(
        MockDefinition $definition,
        $source,
        array $error = null,
        $cause = null
    ) {
        $this->definition = $definition;
        $this->source = $source;
        $this->error = $error;

        $lines = explode(PHP_EOL, $source);

        if (null === $error) {
            $message = sprintf(
                'Mock class %s generation failed.%sRelevant lines:%%s',
                $definition->className(),
                PHP_EOL
            );
            $errorLineNumber = null;
        } else {
            $errorLineNumber = $error['line'];
            $startLine = $errorLineNumber - 4;
            $contextLineCount = 7;

            if ($startLine < 0) {
                $contextLineCount += $startLine;
                $startLine = 0;
            }

            $lines = array_slice($lines, $startLine, $contextLineCount, true);

            $message = sprintf(
                'Mock class %s generation failed: ' .
                    '%s in generated code on line %d.%s' .
                    'Relevant lines:%%s',
                $definition->className(),
                $error['message'],
                $errorLineNumber,
                PHP_EOL
            );
        }

        $lineNumber = 0;

        foreach ($lines as $lineNumber => $line) {
        }

        $padSize = strlen($lineNumber + 1) + 4;
        $renderedLines = '';

        foreach ($lines as $lineNumber => $line) {
            if (null !== $errorLineNumber) {
                $highlight = $lineNumber + 1 === $errorLineNumber;
            } else {
                $highlight = false;
            }

            $renderedLines .= sprintf(
                '%s%s%s %s',
                PHP_EOL,
                str_pad($lineNumber + 1, $padSize, ' ', STR_PAD_LEFT),
                $highlight ? ':' : ' ',
                $line
            );
        }

        parent::__construct(sprintf($message, $renderedLines), 0, $cause);
    }

    /**
     * Get the definition.
     *
     * @return MockDefinition The definition.
     */
    public function definition()
    {
        return $this->definition;
    }

    /**
     * Get the generated source code.
     *
     * @return string The generated source code.
     */
    public function source()
    {
        return $this->source;
    }

    /**
     * Get the error details.
     *
     * @return array<string,mixed> The error details.
     */
    public function error()
    {
        return $this->error;
    }

    private $definition;
    private $source;
    private $error;
}
