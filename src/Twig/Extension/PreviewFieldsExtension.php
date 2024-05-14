<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\PreviewFieldsExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PreviewFieldsExtension extends AbstractExtension
{
    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('init_preview_fields', [PreviewFieldsExtensionRuntime::class, 'initPreviewFields']),
        ];
    }
}
