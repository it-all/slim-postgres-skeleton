<?php
declare(strict_types=1);

use Domain\HomeView;
use Infrastructure\Security\Authentication\AuthenticationView;
use Infrastructure\Security\Authentication\AuthenticationController;
use Infrastructure\Security\Authentication\GuestMiddleware;
use Infrastructure\Security\Authorization\AuthorizationMiddleware;
use Infrastructure\Security\Authentication\AuthenticationMiddleware;
use Domain\AdminHomeView;
use Entities\SystemEvents\SystemEventsView;
use Entities\SystemEvents\SystemEventsController;
use Entities\LoginAttempts\LoginAttemptsView;
use Entities\LoginAttempts\LoginAttemptsController;
use Entities\Administrators\View\AdministratorsView;
use Entities\Administrators\AdministratorsController;
use Entities\Roles\RolesView;
use Entities\Roles\RolesController;
use Entities\Permissions\View\PermissionsViews;
use Entities\Permissions\PermissionsControllers;

/////////////////////////////////////////
// Routes that anyone can access

$slim->get('/', HomeView::class . ':routeIndex')->setName(ROUTE_HOME);

// remainder of front end pages to go here

/////////////////////////////////////////

/////////////////////////////////////////
// Routes that only non-authenticated users (Guests) can access

$slim->get('/' . $config['adminPath'], AuthenticationView::class . ':routeGetLogin')
    ->add(new GuestMiddleware($slimContainer))
    ->setName(ROUTE_LOGIN);

$slim->post('/' . $config['adminPath'], AuthenticationController::class . ':routePostLogin')
    ->add(new GuestMiddleware($slimContainer))
    ->setName(ROUTE_LOGIN_POST);
/////////////////////////////////////////

// Admin Routes - Routes that only authenticated users access (to end of file)
// Note, if route needs authorization as well, the authorization is added prior to authentication, so that authentication is performed first

// admin home
$slim->get('/' . $config['adminPath'] . '/home',
    AdminHomeView::class . ':routeIndex')
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMIN_HOME_DEFAULT);

// logout
$slim->get('/' . $config['adminPath'] . '/logout', AuthenticationController::class . ':routeGetLogout')
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_LOGOUT);

// system events

$slim->get('/' . $config['adminPath'] . '/systemEvents', SystemEventsView::class . ':routeIndex')
    ->add(new AuthorizationMiddleware($slimContainer, SYSTEM_EVENTS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_SYSTEM_EVENTS);

$slim->post('/' . $config['adminPath'] . '/systemEvents', SystemEventsController::class . ':routePostIndexFilter')
    ->add(new AuthorizationMiddleware($slimContainer, SYSTEM_EVENTS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer));

$slim->get('/' . $config['adminPath'] . '/systemEvents/reset',
    SystemEventsView::class . ':routeIndexResetFilter')
    ->add(new AuthorizationMiddleware($slimContainer, SYSTEM_EVENTS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_SYSTEM_EVENTS_RESET);
// end system events

// login attempts

$slim->get('/' . $config['adminPath'] . '/logins', LoginAttemptsView::class . ':routeIndex')
    ->add(new AuthorizationMiddleware($slimContainer, LOGIN_ATTEMPTS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_LOGIN_ATTEMPTS);

$slim->post('/' . $config['adminPath'] . '/logins', LoginAttemptsController::class . ':routePostIndexFilter')
    ->add(new AuthorizationMiddleware($slimContainer, LOGIN_ATTEMPTS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer));

$slim->get('/' . $config['adminPath'] . '/logins/reset',
    LoginAttemptsView::class . ':routeIndexResetFilter')
    ->add(new AuthorizationMiddleware($slimContainer, LOGIN_ATTEMPTS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_LOGIN_ATTEMPTS_RESET);

// administrators

$slim->get('/' . $config['adminPath'] . '/administrators', AdministratorsView::class . ':routeIndex')
    ->add(new AuthorizationMiddleware($slimContainer, ADMINISTRATORS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS);

$slim->post('/' . $config['adminPath'] . '/administrators', AdministratorsController::class . ':routePostIndexFilter')
    ->add(new AuthorizationMiddleware($slimContainer, ADMINISTRATORS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer));

$slim->get('/' . $config['adminPath'] . '/administrators/reset', AdministratorsView::class . ':routeIndexResetFilter')
    ->add(new AuthorizationMiddleware($slimContainer, ADMINISTRATORS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_RESET);

$slim->get('/' . $config['adminPath'] . '/administrators/insert', AdministratorsView::class . ':routeGetInsert')
    ->add(new AuthorizationMiddleware($slimContainer, ADMINISTRATORS_INSERT_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_INSERT);

$slim->post('/' . $config['adminPath'] . '/administrators/insert', AdministratorsController::class . ':routePostInsert')
    ->add(new AuthorizationMiddleware($slimContainer, ADMINISTRATORS_INSERT_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_INSERT_POST);

$slim->get('/' . $config['adminPath'] . '/administrators/{primaryKey}', AdministratorsView::class . ':routeGetUpdate')
    ->add(new AuthorizationMiddleware($slimContainer, ADMINISTRATORS_UPDATE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_UPDATE);

$slim->put('/' . $config['adminPath'] . '/administrators/{primaryKey}', AdministratorsController::class . ':routePutUpdate')
    ->add(new AuthorizationMiddleware($slimContainer, ADMINISTRATORS_UPDATE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_UPDATE_PUT);

$slim->get('/' . $config['adminPath'] . '/administrators/delete/{primaryKey}', AdministratorsController::class . ':routeGetDelete')
    ->add(new AuthorizationMiddleware($slimContainer, ADMINISTRATORS_DELETE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_DELETE);
// end administrators

// roles

$slim->get('/' . $config['adminPath'] . '/roles', RolesView::class . ':routeIndex')
    ->add(new AuthorizationMiddleware($slimContainer, ROLES_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_ROLES);

$slim->post('/' . $config['adminPath'] . '/roles', RolesController::class . ':routePostIndexFilter')
    ->add(new AuthorizationMiddleware($slimContainer, ROLES_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer));

$slim->get('/' . $config['adminPath'] . '/roles/reset', RolesView::class . ':routeIndexResetFilter')
    ->add(new AuthorizationMiddleware($slimContainer, ROLES_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_ROLES_RESET);

$slim->get('/' . $config['adminPath'] . '/roles/insert', RolesView::class . ':routeGetInsert')
    ->add(new AuthorizationMiddleware($slimContainer, ROLES_INSERT_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_ROLES_INSERT);

$slim->post('/' . $config['adminPath'] . '/roles/insert', RolesController::class . ':routePostInsert')
    ->add(new AuthorizationMiddleware($slimContainer, ROLES_INSERT_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_ROLES_INSERT_POST);

$slim->get('/' . $config['adminPath'] . '/roles/{primaryKey}', RolesView::class . ':routeGetUpdate')
    ->add(new AuthorizationMiddleware($slimContainer, ROLES_UPDATE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_ROLES_UPDATE);

$slim->put('/' . $config['adminPath'] . '/roles/{primaryKey}', RolesController::class . ':routePutUpdate')
    ->add(new AuthorizationMiddleware($slimContainer, ROLES_UPDATE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_ROLES_UPDATE_PUT);

$slim->get('/' . $config['adminPath'] . '/roles/delete/{primaryKey}', RolesController::class . ':routeGetDelete')
    ->add(new AuthorizationMiddleware($slimContainer, ROLES_DELETE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_ROLES_DELETE);
// end roles

// permissions

$slim->get('/' . $config['adminPath'] . '/permissions', PermissionsViews::class . ':routeIndex')
    ->add(new AuthorizationMiddleware($slimContainer, PERMISSIONS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_PERMISSIONS);

$slim->post('/' . $config['adminPath'] . '/permissions', PermissionsControllers::class . ':routePostIndexFilter')
    ->add(new AuthorizationMiddleware($slimContainer, PERMISSIONS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer));

$slim->get('/' . $config['adminPath'] . '/permissions/reset', PermissionsViews::class . ':routeIndexResetFilter')
    ->add(new AuthorizationMiddleware($slimContainer, PERMISSIONS_VIEW_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_PERMISSIONS_RESET);

$slim->get('/' . $config['adminPath'] . '/permissions/insert', PermissionsViews::class . ':routeGetInsert')
    ->add(new AuthorizationMiddleware($slimContainer, PERMISSIONS_INSERT_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_PERMISSIONS_INSERT);

$slim->post('/' . $config['adminPath'] . '/permissions/insert', PermissionsControllers::class . ':routePostInsert')
    ->add(new AuthorizationMiddleware($slimContainer, PERMISSIONS_INSERT_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_PERMISSIONS_INSERT_POST);

$slim->get('/' . $config['adminPath'] . '/permissions/{primaryKey}', PermissionsViews::class . ':routeGetUpdate')
    ->add(new AuthorizationMiddleware($slimContainer, PERMISSIONS_UPDATE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_PERMISSIONS_UPDATE);

$slim->put('/' . $config['adminPath'] . '/permissions/{primaryKey}', PermissionsControllers::class . ':routePutUpdate')
    ->add(new AuthorizationMiddleware($slimContainer, PERMISSIONS_UPDATE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_PERMISSIONS_UPDATE_PUT);

$slim->get('/' . $config['adminPath'] . '/permissions/delete/{primaryKey}', PermissionsControllers::class . ':routeGetDelete')
    ->add(new AuthorizationMiddleware($slimContainer, PERMISSIONS_DELETE_RESOURCE))
    ->add(new AuthenticationMiddleware($slimContainer))
    ->setName(ROUTE_ADMINISTRATORS_PERMISSIONS_DELETE);
// end permissions
