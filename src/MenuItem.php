<?php
namespace Minhbang\LaravelMenu;

use Laracasts\Presenter\PresentableTrait;
use Baum\Node;

/**
 * App\MenuItem
 *
 * @property integer $id
 * @property integer $parent_id
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $label
 * @property string $type
 * @property string $params
 * @property-read mixed $url
 * @property-read \Minhbang\LaravelMenu\MenuItem $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Minhbang\LaravelMenu\MenuItem[] $children
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereDepth($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\LaravelMenu\MenuItem whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutSelf()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Query\Builder|\Baum\Node limitDepth($limit)
 */
class MenuItem extends Node
{
    use PresentableTrait;

    protected $table = 'menus';
    protected $presenter = 'Minhbang\LaravelMenu\MenuItemPresenter';
    protected $fillable = ['label', 'type', 'params'];
    public $timestamps = false;

    /**
     * Getter $menu->url
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return app('menu')->getUrl($this->type, $this->params);
    }

    /**
     * @return string
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public static function html()
    {
        return (new static())->present()->html;
    }

    public static function aliases()
    {
        
    }
}
