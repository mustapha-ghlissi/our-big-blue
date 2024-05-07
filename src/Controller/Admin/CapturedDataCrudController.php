<?php

namespace App\Controller\Admin;

use App\Entity\CapturedData;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class CapturedDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CapturedData::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Utilisateur')
                ->autocomplete(),
            AssociationField::new('form', 'Formulaire de base')
                ->autocomplete(),
            CollectionField::new('images', 'Liste des images')
                ->setEntryType(ImageType::class)
                ->renderExpanded()
                ->setRequired(true)
                ->setFormTypeOptions([
                    'help' => 'Seuls les fichiers ayant l\'extension (<b>jpg, jpeg, png ou pdf</b>) sont acceptés.',
                    'help_html' => true
                ])
        ];
    }
}
