<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserCrudController extends AbstractCrudController
{

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined();
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('pseudo')
                ->setLabel($this->translator->trans('admin.user.pseudo')),
            TextField::new('email')
                ->setLabel($this->translator->trans('admin.user.email')),
            ArrayField::new('roles')
                ->setLabel($this->translator->trans('admin.user.roles'))
                ->formatValue(fn ($v, $entity) =>
                implode(', ', array_map(fn ($role) => $this->translator->trans($this->getRoleLabel($role)), $entity->getRoles()))
                ),
            ChoiceField::new('roles')
                ->setLabel($this->translator->trans('admin.user.roles'))
                ->onlyOnForms()
                ->setLabel('admin.user.roles')
                ->allowMultipleChoices()
                ->setChoices([
                    $this->translator->trans('roles.user') => 'ROLE_USER',
                    $this->translator->trans('roles.moderator') => 'ROLE_MODERATOR',
                    $this->translator->trans('roles.admin') => 'ROLE_ADMIN',
                ])
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    private function getRoleLabel(string $role): string
    {
        return match ($role) {
            'ROLE_USER' => 'roles.user',
            'ROLE_MODERATOR' => 'roles.moderator',
            'ROLE_ADMIN' => 'roles.admin',
            default => $role,
        };
    }

    public function new(AdminContext $context): Response
    {
        return $this->redirectToRoute('admin_user_index');
    }
}
