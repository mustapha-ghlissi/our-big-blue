<?php

namespace App\Twig\Runtime;

use App\Entity\CapturedData;
use Twig\Extension\RuntimeExtensionInterface;

class PreviewFieldsExtensionRuntime implements RuntimeExtensionInterface
{
    /**
     * @param CapturedData $capturedData
     * @return array
     */
    public function initPreviewFields(CapturedData $capturedData): array
    {
        $fields = $capturedData->getForm()?->getFields();

        if (!$fields) {
            return [];
        }

        $previewFields = [];

        foreach ($fields as $field) {
            $previewFields[$field->getFieldId()] = $field->getName();
        }

        $previewFields['location'] = 'Localisation';

        return $previewFields;
    }
}
