<?php

namespace App\Controller\Admin;

use App\Entity\Debate;
use App\Entity\Report;
use App\Repository\DebateRepository;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class DebateCrudController extends AbstractCrudController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public static function getEntityFqcn(): string
    {
        return Debate::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        $isValid = BooleanField::new('isValid')
            ->setLabel($this->translator->trans('admin.debate.valid'));

        if ($pageName === Crud::PAGE_INDEX) {
            $isValid->renderAsSwitch(false);
        }

        return [
            TextField::new('nameDebate')
                ->setLabel($this->translator->trans('admin.debate.name')),
            TextEditorField::new('descriptionDebate')
                ->onlyOnForms()
                ->setLabel($this->translator->trans('admin.debate.description')),
            TextField::new('userCreated')
                ->setDisabled()
                ->setLabel($this->translator->trans('admin.debate.user')),
            ArrayField::new('camps')
                ->setDisabled()
                ->setLabel($this->translator->trans('admin.debate.camps')),
            $isValid
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $validate = Action::new('validate', $this->translator->trans('admin.debate.validate'))
            ->linkToCrudAction('validate')
            ->displayIf(function (Debate $debate) {
                return !$debate->IsValid();
            });

        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            //->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_DETAIL, $validate)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action->displayIf(function (Debate $debate) {
                    return !$debate->IsValid();
                });
            });
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('isValid');
    }

    public function validate(AdminContext $context, DebateRepository $debateRepository, EntityManagerInterface $entityManager): Response
    {
        $debateId = $context->getRequest()->query->get('entityId');
        $debate = $debateRepository->find($debateId);

        $debate->setIsValid(true);

        $entityManager->persist($debate);
        $entityManager->flush();

        return $this->redirectToRoute('admin_debate_index');
    }

    public function new(AdminContext $context): Response
    {
        return $this->redirectToRoute('admin_debate_index');
    }
}
