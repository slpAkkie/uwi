<?php

namespace Uwi\Calibri;

class RenderEngine
{
    /**
     * Render specified view file
     *
     * @param string $filePath
     * @return string
     */
    public function render(View $__view): string
    {
        ob_start();
        (function () use ($__view) {
            extract($__view->getParameters());
            require($__view->getFilePath());
        })();
        $rendered = ob_get_clean();

        return $rendered;
    }
}
