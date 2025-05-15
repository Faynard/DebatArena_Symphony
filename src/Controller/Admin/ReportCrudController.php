<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReportCrudController extends AbstractCrudController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public static function getEntityFqcn(): string
    {
        return Report::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('user')
                ->setDisabled()
                ->setLabel($this->translator->trans('admin.user.pseudo')),
            TextField::new('argument.camp.debate.nameDebate')
                ->setDisabled()
                ->setLabel($this->translator->trans('admin.debate.name')),
            TextField::new('argument.camp.nameCamp')
                ->setDisabled()
                ->setLabel($this->translator->trans('admin.camp.name'))
                ->onlyOnDetail(),
            TextEditorField::new('argument.text')
                ->setDisabled()
                ->setLabel($this->translator->trans('admin.argument.text')),
            BooleanField::new('isValid')
                ->setDisabled()
                ->setLabel($this->translator->trans('admin.argument.valid')),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('isValid');
    }

    public function configureActions(Actions $actions): Actions
    {
        $validate = Action::new('validate', $this->translator->trans('admin.report.validate'))
            ->linkToCrudAction('validate')
            ->displayIf(function (Report $report) {
                return !$report->IsValid();
            });
        $giveSanctionAction = Action::new('giveSanction', $this->translator->trans('admin.report.sanction'))
            ->linkToCrudAction('giveSanction')
            ->displayIf(function (Report $report) {
                return !$report->IsValid();
            });

        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_DETAIL, $validate)
            ->add(Crud::PAGE_DETAIL, $giveSanctionAction)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action->displayIf(function (Report $report) {
                    return !$report->IsValid();
                });
            });
    }

    public function giveSanction(AdminContext $context): Response
    {
        /* TODO voir avec Thomas */
        return $this->redirectToRoute('app_debate_index');
    }

    public function validate(AdminContext $context, ReportRepository $reportRepository, EntityManagerInterface $entityManager): Response
    {
        $reportId = $context->getRequest()->query->get('entityId');
        $report = $reportRepository->find($reportId);

        $report->setIsValid(true);

        $entityManager->persist($report);
        $entityManager->flush();

        return $this->redirectToRoute('admin_report_index');
    }

    public function edit(AdminContext $context): Response
    {
        return $this->redirectToRoute('admin_report_index');
    }

    public function new(AdminContext $context): Response
    {
        return $this->redirectToRoute('admin_report_index');
    }
}
