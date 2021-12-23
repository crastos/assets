<?php

namespace Roots\Acorn\Assets\View;

use Illuminate\View\Compilers\BladeCompiler;

class BladeDirectives
{
    /**
     * Blade Compiler
     *
     * @var BladeCompiler
     */
    public $compiler;

    /**
     * BladeDirectives instance.
     *
     * @param BladeCompiler $compiler
     */
    public function __construct(BladeCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    public function register()
    {
        $this->compiler->directive('asset', [$this, 'asset']);
        $this->compiler->directive('scripts', [$this, 'scripts']);
        $this->compiler->directive('styles', [$this, 'styles']);
    }

    /**
     * Invoke the @asset directive.
     *
     * @param  string $expression
     * @return string
     */
    public function asset($expression)
    {
        return sprintf("<?= %s(%s); ?>", '\Roots\asset', $expression);
    }

    /**
     * Process @asset directive.
     *
     * @param  string $expression
     * @return string
     */
    public function bundle($expression)
    {
        return sprintf("<?= %s(%s); ?>", '\Roots\bundle', $expression);
    }

    /**
     * Process @scripts directive.
     *
     * @param  string $expression
     * @return string
     */
    public function scripts($expression)
    {
        return sprintf("<?= %s(%s); ?>", '\Roots\scripts', $expression);
    }

    /**
     * Process @styles directive.
     *
     * @param  string $expression
     * @return string
     */
    public function styles($expression)
    {
        return sprintf("<?= %s(%s); ?>", '\Roots\styles', $expression);
    }
}
