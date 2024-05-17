<?php

namespace App\Controller\Admin;

use App\Entity\CapturedData;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CapturedDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CapturedData::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);

        return $crud->setEntityLabelInPlural('données collectées')
            ->setPageTitle('new', 'Nouvelle collection');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user', 'Utilisateur')
                ->autocomplete(),
            AssociationField::new('form', 'Formulaire de base')
                ->autocomplete(),
            CollectionField::new('data')->hideOnForm()
                ->setTemplatePath('admin/captured_data/data.html.twig')
            ,
            DateField::new('createdAt', 'Créé le')->hideOnForm(),
            CollectionField::new('images', 'Liste des images')
                ->hideOnIndex()
                ->setEntryType(ImageType::class)
                ->renderExpanded()
                ->setRequired(true)
                ->setFormTypeOptions([
                    'help' => 'Seuls les fichiers ayant l\'extension (<b>jpg, jpeg, png ou pdf</b>) sont acceptés.',
                    'help_html' => true
                ]),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters = parent::configureFilters($filters);
        return $filters
            ->add('user')
            ->add('form')
            ;
    }
}
