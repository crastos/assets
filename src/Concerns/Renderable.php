<?php

namespace Roots\Acorn\Assets\Concerns;

trait Renderable
{
    /**
     * Get JS files in bundle.
     *
     * Optionally pass a function to execute on each JS file.
     *
     * @param callable $callable
     * @return Collection|$this
     */
    abstract public function js(?callable $callable = null);

    /**
     * Get CSS files in bundle.
     *
     * Optionally pass a function to execute on each CSS file.
     *
     * @param callable $callable
     * @return Collection|$this
     */
    abstract public function css(?callable $callable = null);

    abstract public function runtimeSource();

    /**
     * Render styles.
     *
     * @param string $media
     * @return string
     */
    public function styles(string $media = 'all'): string
    {
        return $this->css()->values()->map(function ($src) use ($media) {
            $html = "<link href=\"{$this->uri}/{$src}\" rel=\"stylesheet\"";

            if ($media !== 'all') {
                $html .= "media=\"{$media}\"";
            }

            return $html . '>';
        })->join('');
    }

    /**
     * Render scripts.
     *
     * @param bool $defer
     * @param bool $async
     * @return string
     */
    public function scripts($defer = false, $async = false): string
    {
        $html = sprintf('<script>%s</script>', $this->runtimeSource());

        $this->js()->values()->each(function ($src) use ($defer, $async, &$html) {
            $html .= "<script src=\"{$this->uri}/{$src}\"";

            if ($defer) {
                $html .= ' defer';
            }

            if ($async) {
                $html .= ' async';
            }

            $html .= '></script>';
        });

        return $html;
    }
}
