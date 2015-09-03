<?php
namespace Minhbang\LaravelMenu;

use Minhbang\LaravelKit\Extensions\BackendController;
use Request;

class MenuController extends BackendController
{
    public function __construct()
    {
        parent::__construct(config('menu.middlewares'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $max_depth = config('menu.max_depth');
        $nestable = app('menu')->nestable();
        $this->buildHeading(
            trans('menu::common.manage'),
            'fa-sitemap',
            [
                route('backend.setting.list') => trans('backend.config'),
                '#'                      => trans('menu::common.menu')
            ]
        );
        return view('menu::index', compact('max_depth', 'nestable'));
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
     * @param \Minhbang\LaravelMenu\MenuItem $menu
     * @return \Illuminate\View\View
     */
    public function createChildOf(MenuItem $menu)
    {
        return $this->_create($menu);
    }

    /**
     * @param null|\Minhbang\LaravelMenu\MenuItem $parent
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
        $menu = new MenuItem();
        $method = 'post';
        $types = app('menu')->types;
        return view(
            'menu::form',
            compact('parent_label', 'url', 'method', 'menu', 'types')
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\LaravelMenu\MenuItemRequest $request
     * @return \Illuminate\View\View
     */
    public function store(MenuItemRequest $request)
    {
        return $this->_store($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\LaravelMenu\MenuItemRequest $request
     * @param \Minhbang\LaravelMenu\MenuItem $menu
     * @return \Illuminate\View\View
     */
    public function storeChildOf(MenuItemRequest $request, MenuItem $menu)
    {
        return $this->_store($request, $menu);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\LaravelMenu\MenuItemRequest $request
     * @param null|\Minhbang\LaravelMenu\MenuItem $parent
     * @return \Illuminate\View\View
     */
    public function _store($request, $parent = null)
    {
        $menu = new MenuItem();
        $inputs = $request->all();
        $menu->fill($inputs);
        $menu->save();
        if ($parent) {
            $menu->makeChildOf($parent);
        }
        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('menu::common.menu_item')])
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \Minhbang\LaravelMenu\MenuItem $menu
     * @return \Illuminate\View\View
     */
    public function show(MenuItem $menu)
    {
        return view('menu::show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\LaravelMenu\MenuItem $menu
     * @return \Illuminate\View\View
     */
    public function edit(MenuItem $menu)
    {
        $parent_label = $menu->isRoot() ? '- ROOT -' : $menu->parent->label;
        $url = route('backend.menu.update', ['menu' => $menu->id]);
        $method = 'put';
        $types = app('menu')->types;
        return view('menu::form', compact('parent_label', 'url', 'method', 'menu', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Minhbang\LaravelMenu\MenuItemRequest $request
     * @param \Minhbang\LaravelMenu\MenuItem $menu
     * @return \Illuminate\View\View
     */
    public function update(MenuItemRequest $request, MenuItem $menu)
    {
        $inputs = $request->all();
        $menu->fill($inputs);
        $menu->save();
        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('menu::common.menu_item')])
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\LaravelMenu\MenuItem $menu
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(MenuItem $menu)
    {
        $menu->delete();
        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('menu::common.menu')]),
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function data()
    {
        return response()->json(['html' => app('menu')->nestable()]);
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
            return response()->json(
                [
                    'type'    => 'success',
                    'content' => trans('common.order_object_success', ['name' => trans('menu::common.menu_item')]),
                ]
            );
        } else {
            $this->dieAjax();
        }
    }

    /**
     * @param string $name
     * @return null|\Minhbang\LaravelMenu\MenuItem
     */
    protected function getNode($name)
    {
        $id = Request::input($name);
        if ($id) {
            if ($node = MenuItem::find($id)) {
                return $node;
            } else {
                $this->dieAjax();
            }
        } else {
            return null;
        }
    }

    /**
     * Kết thúc App, trả về message dạng JSON
     */
    protected function dieAjax()
    {
        die(json_encode(
            [
                'type'    => 'error',
                'content' => trans('menu::common.not_found')
            ]
        ));
    }
}
