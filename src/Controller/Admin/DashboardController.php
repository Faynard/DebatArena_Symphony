<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Debate;
use App\Entity\Report;
use App\Entity\User;
use App\Entity\Sanction;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(): Response
    {
        return $this->redirectToRoute('admin_report_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DebateArena')
            ->setFaviconPath('favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl($this->translator->trans('admin.dashboard.home'), '', '/')
            ->setPermission('ROLE_MODERATOR');

        yield MenuItem::section($this->translator->trans('admin.section.moderator'));
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.report'), 'fa fa-flag', Report::class)
            ->setPermission('ROLE_MODERATOR');
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.debate'), 'fa fa-newspaper', Debate::class)
            ->setPermission('ROLE_MODERATOR');
        yield MenuItem::linkToCrud('Creer Categorie', 'fa fa-tags', Category::class)
            ->setPermission('ROLE_MODERATOR');
        
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.sanction'), 'fa fa-gavel', Sanction::class)
            ->setPermission('ROLE_MODERATOR');

        yield MenuItem::section($this->translator->trans('admin.section.administrator'))
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud($this->translator->trans('admin.menu.user'), 'fa fa-user', User::class)
            ->setPermission('ROLE_ADMIN');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
                MenuItem::linkToRoute($this->translator->trans('admin.adminUser.profile'), 'fa fa-id-card', 'app_user_show'),
            ]);
    }
}
