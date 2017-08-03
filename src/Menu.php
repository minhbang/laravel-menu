<?php

namespace Minhbang\Menu;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Kit\Extensions\NestedSetModel;

/**
 * Class Menu
 *
 * @package Minhbang\Menu
 * @property integer $id
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property string $label
 * @property string $type
 * @property array $params
 * @property string $options
 * @property int $configured
 * @property-read mixed $url
 * @property-read \Minhbang\Menu\Menu $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Menu\Menu[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Menu\Menu whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Menu\Menu whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Minhbang\Menu\Menu whereConfigured($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 * @mixin \Eloquent
 */
class Menu extends NestedSetModel
{
    use PresentableTrait;

    protected $table = 'menus';

    protected $presenter = MenuPresenter::class;

    protected $fillable = ['name', 'label', 'type', 'options'];

    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['params' => 'array'];

    /**
     * Getter $menu->params
     * - Thứ tự ưu tiên thuộc tính: fixed > db > default
     *
     * @param string $value
     *
     * @return array
     */
    public function getParamsAttribute($value)
    {
        $typeInstance = $this->typeInstance();

        return $typeInstance->paramsFixed() + (array) json_decode($value, true) + $typeInstance->paramsDefault;
    }

    /**
     * @param \Minhbang\Menu\MenuParamsRequest $request
     */
    public function updateParams(MenuParamsRequest $request)
    {
        $typeInstance = $this->typeInstance();
        $default = $typeInstance->paramsDefault;
        $params = [];
        foreach (array_keys($typeInstance->paramsAttributes) as $attr) {
            $params[$attr] = $request->get($attr, $default[$attr]);
        }
        $this->params = $params;
    }

    /**
     * @var array
     */
    protected $_options;

    /**
     * @param null|string $key
     * @param mixed $default
     *
     * @return mixed;
     */
    public function getOption($key = null, $default = null)
    {
        if (empty($this->options)) {
            return $default;
        } else {
            if (is_null($this->_options)) {
                $this->_options = json_decode($this->options, true);
            }

            return is_null($key) ? $this->_options : array_get($this->_options, $key, $default);
        }
    }

    /**
     * Getter $menu->url
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return ($type = $this->typeInstance()) ? $type->url($this) : '#';
    }

    /**
     * @return \Minhbang\Menu\Types\MenuType
     */
    public function typeInstance()
    {
        return app('menu-manager')->menuType($this->type);
    }
}
