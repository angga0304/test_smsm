<?php

namespace App\Http\Middleware\Admin;

use BalajiDharma\LaravelMenu\Models\Menu;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;

class HandleInertiaAdminRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = Auth::user();
        $menu = Menu::getMenuTree('admin', false, true)->filter(function ($data) use ($user) {
            if ($data->name == 'Roles' && !$user->can('role list')) {
                return FALSE;
            }
            if ($data->name == 'Tag' && !$user->can('tag list')) {
                return FALSE;
            }
            if ($data->name == 'Posts' && !$user->can('post list')) {
                return FALSE;
            }
            if ($data->name == 'Files' && !$user->can('file list')) {
                return FALSE;
            }
            if ($data->name == 'Users' && !$user->can('user list')) {
                return FALSE;
            }
            return TRUE;
        });
        return [
            ...parent::share($request),
            'navigation' => [
                'menu' => $menu,
                'breadcrumbs' => $this->getBreadcrumbs($request),
            ],
        ];
    }

    protected function getBreadcrumbs(Request $request)
    {
        if($request->isMethod('get'))
        {
            return Breadcrumbs::generate();
        }
    }
}
