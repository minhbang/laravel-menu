<?php
namespace Minhbang\Menu;

use Minhbang\Kit\Extensions\BackendController;
use Request;

class Controller extends BackendController
{
    /**
     * Quản lý current menu
     *
     * @var \Minhbang\Menu\Manager
     */
    protected $manager;
    /**
     * Current menu root
     *
     * @var \Minhbang\Menu\Item
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
     * @param null|string $name
     */
    protected function switchMenu($name = null)
    {
        $key = 'backend.menu.name';
        $name = $name ?: session($key, 'main');
        session([$key => $name]);
        $this->manager = app('menu')->get($name);
        $this->root = $this->manager->root();
        $this->name = $name;
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
        $this->buildHeading(
            [trans('menu::common.manage'), "[{$menus[$current]}]"],
            'fa-sitemap',
            [
                route('backend.setting.list') => trans('backend.config'),
                '#'                           => trans('menu::common.menu'),
            ]
        );
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
     * @param \Minhbang\Menu\Item $menu
     *
     * @return \Illuminate\View\View
     */
    public function createChildOf(Item $menu)
    {
        return $this->_create($menu);
    }

    /**
     * @param null|\Minhbang\Menu\Item $parent
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
        $menu = new Item();
        $method = 'post';
        $types = $this->manager->types();
        return view(
            'menu::form',
            compact('parent_label', 'url', 'method', 'menu', 'types')
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Menu\ItemRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function store(ItemRequest $request)
    {
        return $this->_store($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Menu\ItemRequest $request
     * @param \Minhbang\Menu\Item $menu
     *
     * @return \Illuminate\View\View
     */
    public function storeChildOf(ItemRequest $request, Item $menu)
    {
        return $this->_store($request, $menu);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\Menu\ItemRequest $request
     * @param null|\Minhbang\Menu\Item $parent
     *
     * @return \Illuminate\View\View
     */
    public function _store($request, $parent = null)
    {
        $menu = new Item();
        $inputs = $request->all();
        $menu->fill($inputs);
        $menu->save();
        $menu->makeChildOf($parent ?: $this->root);
        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('menu::common.item')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \Minhbang\Menu\Item $menu
     *
     * @return \Illuminate\View\View
     */
    public function show(Item $menu)
    {
        return view('menu::show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\Menu\Item $menu
     *
     * @return \Illuminate\View\View
     */
    public function edit(Item $menu)
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
     * @param \Minhbang\Menu\ItemRequest $request
     * @param \Minhbang\Menu\Item $menu
     *
     * @return \Illuminate\View\View
     */
    public function update(ItemRequest $request, Item $menu)
    {
        $inputs = $request->all();
        $menu->fill($inputs);
        $menu->save();
        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('menu::common.item')]),
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\Menu\Item $menu
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(Item $menu)
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
        return response()->json(['html' => $this->manager->nestable()]);
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
                    'content' => trans('common.order_object_success', ['name' => trans('menu::common.item')]),
                ]
            );
        } else {
            return $this->dieAjax();
        }
    }

    /**
     * @param string $name
     *
     * @return null|\Minhbang\Menu\Item
     */
    protected function getNode($name)
    {
        $id = Request::input($name);
        if ($id) {
            if ($node = Item::find($id)) {
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
        return die(json_encode(
            [
                'type'    => 'error',
                'content' => trans('menu::common.not_found'),
            ]
        ));
    }
}
