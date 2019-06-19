<?php

namespace Helick\Blocks\Exceptions;

use InvalidArgumentException;

final class BlockNameEmptyException extends InvalidArgumentException
{
    /**
     * @return BlockNameEmptyException
     */
    public static function withMessage(): self
    {
        return new static('You must set the block name before composing');
    }
}
