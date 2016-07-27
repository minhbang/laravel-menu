<?php
namespace Minhbang\Menu;

use Laracasts\Presenter\PresentableTrait;
use Minhbang\Kit\Extensions\NestedSetModel;

/**
 * App\Item
 *
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
 * @property-read \Minhbang\Menu\Item $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\Menu\Item[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Item whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Item whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Item whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Item whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Item whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Item whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Item whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\Item whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class Item extends NestedSetModel
{
    use PresentableTrait;
    protected $table = 'menus';
    protected $presenter = 'Minhbang\Menu\ItemPresenter';
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
        return app('menu')->buildUrl($this->type, $this->params);
    }
}
