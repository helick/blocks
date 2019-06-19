<?php

namespace Helick\Blocks\Exceptions;

use InvalidArgumentException;

final class BlockException extends InvalidArgumentException
{
    /**
     * @return BlockException
     */
    public static function forEmptyName(): self
    {
        return new static('You must set the block name before composing');
    }
}
