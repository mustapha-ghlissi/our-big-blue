<?php

namespace App\Controller\Admin;

use App\Entity\Field;
use App\Entity\Form;
use App\Enum\FieldTypeEnum;
use App\Form\FieldType;
use App\Provider\FormFieldTypeProvider;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FormCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Form::class;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addWebpackEncoreEntry('app');
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
            CollectionField::new('fields', 'Veuillez préciser les champs')
                ->setEntryType(FieldType::class)
                ->renderExpanded()
                ->setRequired(true),
        ];
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

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $previewForm)
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER)
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
        $formBuilder = $formBuilder->add('submit', SubmitType::class, ['label' => 'Envoyer'])->getForm();

        $formBuilder->handleRequest($request);

        if ($formBuilder->isSubmitted() && $formBuilder->isValid()) {

        }

        return $this->render('admin/form/preview.html.twig', [
            'form' => $formBuilder
        ]);
    }
}
