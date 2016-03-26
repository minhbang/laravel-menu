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
 * @property string $params
 * @property string $options
 * @property-read mixed $url
 * @property-read \Minhbang\Menu\Menu $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Menu\Menu[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Menu whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class Menu extends NestedSetModel
{
    use PresentableTrait;
    protected $table = 'menus';
    protected $presenter = MenuPresenter::class;
    protected $fillable = ['name', 'label', 'type', 'params', 'options'];
    public $timestamps = false;
    /**
     * @var array
     */
    protected $_options;

    /**
     * @param null|string $key
     * @param mixed $default
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
        return app('menu-manager')->buildUrl($this->type, $this->params);
    }
}
