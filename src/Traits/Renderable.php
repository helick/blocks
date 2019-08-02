<?php

namespace Helick\Blocks\Traits;

use Exception;
use Helick\Blocks\Exceptions\BlockException;

trait Renderable
{
    /**
     * Captures the output that is generated when a template is included.
     * This property is static to prevent object scope resolution.
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     *
     * @throws Exception
     */
    protected static function capture(string $template, array $data): string
    {
        extract($data, EXTR_SKIP);

        ob_start();

        try {
            include $template;
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ob_get_clean();
    }

    /**
     * Render the block.
     *
     * [!!] Global variables with the same key name as local variables will be
     * overwritten by the local variable.
     *
     * @param array  $fields
     * @param array  $attributes
     * @param string $blocks
     *
     * @return void
     *
     * @throws Exception
     */
    public function render(array $fields, array $attributes, string $blocks): void
    {
        $globalVariables = compact('fields', 'attributes', 'blocks');
        $localVariables  = $this->with($fields);

        $data = array_merge($globalVariables, $localVariables);

        echo static::capture($this->template(), $data);
    }

    /**
     * Get the block's template.
     *
     * @return string
     */
    protected function template(): string
    {
        if (empty($this->template)) {
            throw BlockException::forEmptyTemplate();
        }

        $template = locate_template($this->template);
        if ($template === '') {
            throw BlockException::forNotFoundTemplate($this->template);
        }

        return $template;
    }
}
