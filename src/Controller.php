<?php

namespace Minhbang\Menu;

use MenuManager;
use Minhbang\Kit\Extensions\BackendController;
use Request;
use Response;

/**
 * Class Controller
 *
 * @package Minhbang\Menu
 */
class Controller extends BackendController
{
    /**
     * Quản lý current menu
     *
     * @var \Minhbang\Menu\Roots\EditableRoot
     */
    protected $manager;

    /**
     * Current menu root
     *
     * @var \Minhbang\Menu\Menu
     */
    protected $root;

    /**
     * Current menu name
     *
     * @var string
     */
    protected $name;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->switchMenu();
    }

    /**
     * @param \Minhbang\Menu\Menu $menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function params(Menu $menu)
    {
        return ($menuType = $menu->typeInstance()) ? $menuType->form($menu, $this->route_prefix) :
            view('kit::backend.message', [
                'type' => 'error',
                'content' => trans('menu::type.unregistered'),
            ]);
    }

    /**
     * @param \Minhbang\Menu\MenuParamsRequest $request
     * @param \Minhbang\Menu\Menu $menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateParams(MenuParamsRequest $request, Menu $menu)
    {
        $menu->updateParams($request);
        $menu->configured = 1;
        $menu->save();

        return view('kit::_modal_script', [
            'message' => [
                'type' => 'success',
                'content' => trans('common.update_object_success', ['name' => trans('menu::common.item')]),
            ],
            'reloadPage' => true,
        ]);
    }

    /**
     * @param string|null $name
     *
     * @return \Illuminate\View\View
     */
    public function index($name = null)
    {
        $this->switchMenu($name);
        $max_depth = $this->manager->max_depth;
        $nestable = $this->manager->nestable();
        $menus = $this->manager->titles();
        $current = $this->name;

        $this->buildHeading([trans('menu::common.manage'), array_get($menus, $current)], 'fa-sitemap', [
            '#' => trans('menu::common.menu'),
        ], [
            [
                route('backend.menu.create'),
                trans('menu::common.create_item'),
                ['class' => 'modal-link', 'type' => 'primary', 'size' => 'sm', 'icon' => 'plus-sign'],
                [
                    'title' => trans('common.create_object', ['name' => trans('menu::common.item')]),
                    'label' => trans('common.save'),
                    'icon' => 'align-justify',
                ],
            ],
        ]);

        return view('menu::index', compact('max_depth', 'nestable', 'menus', 'current'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->_create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return \Illuminate\View\View
     */
    public function createChildOf(Menu $menu)
    {
        return $this->_create($menu);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Menu\MenuRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(MenuRequest $request)
    {
        return $this->_store($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Menu\MenuRequest $request
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return \Illuminate\View\View
     */
    public function storeChildOf(MenuRequest $request, Menu $menu)
    {
        return $this->_store($request, $menu);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Menu\MenuRequest $request
     * @param null|\Minhbang\Menu\Menu $parent
     *
     * @return \Illuminate\View\View
     */
    public function _store($request, $parent = null)
    {
        $menu = new Menu();
        $inputs = $request->all();
        $menu->fill($inputs);
        $menu->configured = $menu->typeInstance()->hasParams ? 0 : 1;
        $menu->params = $menu->typeInstance()->paramsDefault;
        $menu->save();
        $menu->makeChildOf($parent ?: $this->root);

        return view('kit::_modal_script', [
            'message' => [
                'type' => 'success',
                'content' => trans('common.create_object_success', ['name' => trans('menu::common.item')]),
            ],
            'reloadPage' => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return \Illuminate\View\View
     */
    public function show(Menu $menu)
    {
        return view('menu::show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return \Illuminate\View\View
     */
    public function edit(Menu $menu)
    {
        $parent = $menu->parent;
        $parent_label = $parent->isRoot() ? '- ROOT -' : $parent->label;
        $url = route('backend.menu.update', ['menu' => $menu->id]);
        $method = 'put';
        $types = $this->manager->types();

        return view('menu::form', compact('parent_label', 'url', 'method', 'menu', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Minhbang\Menu\MenuRequest $request
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return \Illuminate\View\View
     */
    public function update(MenuRequest $request, Menu $menu)
    {
        $type = $menu->type;
        $inputs = $request->all();
        $menu->fill($inputs);
        if ($type != $menu->type) {
            $menu->params = $menu->typeInstance()->paramsDefault;
            $menu->configured = $menu->typeInstance()->hasParams ? 0 : 1;
        }
        $menu->save();

        return view('kit::_modal_script', [
            'message' => [
                'type' => 'success',
                'content' => trans('common.update_object_success', ['name' => trans('menu::common.item')]),
            ],
            'reloadPage' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\Menu\Menu $menu
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return Response::json([
            'type' => 'success',
            'content' => trans('common.delete_object_success', ['name' => trans('menu::common.menu')]),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function data()
    {
        return Response::json(['html' => $this->manager->nestable()]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function move()
    {
        if ($menu = $this->getNode('element')) {
            if ($leftNode = $this->getNode('left')) {
                $menu->moveToRightOf($leftNode);
            } else {
                if ($rightNode = $this->getNode('right')) {
                    $menu->moveToLeftOf($rightNode);
                } else {
                    if ($destNode = $this->getNode('parent')) {
                        $menu->makeChildOf($destNode);
                    } else {
                        $this->dieAjax();
                    }
                }
            }

            return Response::json([
                'type' => 'success',
                'content' => trans('common.order_object_success', ['name' => trans('menu::common.item')]),
            ]);
        } else {
            return $this->dieAjax();
        }
    }

    /**
     * @param null|string $name
     */
    protected function switchMenu($name = null)
    {
        $key = 'backend.menu.name';
        $name = $name ?: session($key, MenuManager::firstEditable());
        abort_unless(MenuManager::has($name), 500, 'Invalid Menu name!');
        session([$key => $name]);
        $this->manager = MenuManager::get($name);
        $this->root = $this->manager->node();
        $this->name = $name;
    }

    /**
     * @param null|\Minhbang\Menu\Menu $parent
     *
     * @return \Illuminate\View\View
     */
    protected function _create($parent = null)
    {
        if ($parent) {
            $parent_label = $parent->label;
            $url = route('backend.menu.storeChildOf', ['menu' => $parent->id]);
        } else {
            $parent_label = '- ROOT -';
            $url = route('backend.menu.store');
        }
        $menu = new Menu();
        $method = 'post';
        $types = $this->manager->types();

        return view('menu::form', compact('parent_label', 'url', 'method', 'menu', 'types'));
    }

    /**
     * @param string $name
     *
     * @return null|\Minhbang\Menu\Menu
     */
    protected function getNode($name)
    {
        $id = Request::input($name);
        if ($id) {
            if ($node = Menu::find($id)) {
                return $node;
            } else {
                return $this->dieAjax();
            }
        } else {
            return null;
        }
    }

    /**
     * Kết thúc App, trả về message dạng JSON
     *
     * @return mixed
     */
    protected function dieAjax()
    {
        return die(json_encode([
            'type' => 'error',
            'content' => trans('menu::common.not_found'),
        ]));
    }
}
