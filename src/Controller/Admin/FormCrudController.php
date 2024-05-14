<?php

namespace App\Controller\Admin;

use App\Entity\Field;
use App\Entity\Form;
use App\Form\FieldType;
use App\Provider\FormFieldTypeProvider;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Form::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        $crud = parent::configureCrud($crud);

        return $crud->setEntityLabelInPlural('formulaires')
            ->setPageTitle('new', 'Nouveau formulaire');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('category', 'Catégorie')
                ->autocomplete()
                ->setRequired(true)
                ->setQueryBuilder(function (QueryBuilder $query) {
                    $query
                        ->leftJoin('entity.form', 'f')
                        ->andWhere('f is NULL');
                }),
            CollectionField::new('fields', 'Liste des champs')
                ->setEntryType(FieldType::class)
                ->renderExpanded()
                ->setRequired(true),
            DateField::new('createdAt', 'Créé le')->hideOnForm(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters = parent::configureFilters($filters);

        return $filters
            ->add('category');
    }

    public function configureActions(Actions $actions): Actions
    {
        $previewForm = Action::new('preview', 'Visualiser', 'fa-brands fa-wpforms')
            ->linkToRoute('form_preview', function (Form $form): array {
                return [
                    'id' => $form->getId(),
                ];
            })
        ;
        $actions = parent::configureActions($actions);

        return $actions
            ->add(Crud::PAGE_INDEX, $previewForm)
            ;
    }

    #[Route('/{id}/preview', name: 'form_preview', methods: ['GET', 'POST'])]
    public function preview(Request $request, Form $form): Response|JsonResponse
    {
        $formBuilder = $this->createFormBuilder();

        /**
         * @var Field $field
         */
        foreach ($form->getFields() as $field) {
            $formattedField = FormFieldTypeProvider::getBuiltInTypeByField($field)();
            $formBuilder->add(
                $field->getFieldId(),
                $formattedField['class'],
                $formattedField['options']
            );
        }
        $formBuilder = $formBuilder->getForm();

        return $this->render('admin/form/preview.html.twig', [
            'form' => $formBuilder
        ]);
    }
}
