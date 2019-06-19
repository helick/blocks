<?php

namespace Helick\Blocks\Contracts;

interface Composable
{
    /**
     * Compose the block.
     *
     * @return void
     */
    public function compose(): void;
}
