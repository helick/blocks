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

    /**
     * @return BlockException
     */
    public static function forEmptyTemplate(): self
    {
        return new static('You must set the block template before rendering');
    }

    /**
     * @param mixed $template
     *
     * @return BlockException
     */
    public static function forNotFoundTemplate($template): self
    {
        return new static(strtr('The block template ":template" could not be found', [
            ':template' => implode(', ', (array)$template)
        ]));
    }
}
