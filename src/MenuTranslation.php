<?php
namespace Minhbang\Menu;

use Eloquent;

/**
 * Class MenuTranslation
 *
 * @package Minhbang\Menu
 * @property integer $id
 * @property string $label
 * @property integer $menu_id
 * @property string $locale
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\MenuTranslation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\MenuTranslation whereLabel($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\MenuTranslation whereMenuId($value)
 * @method static \Illuminate\Database\Query\Builder|\Minhbang\Menu\MenuTranslation whereLocale($value)
 * @mixin \Eloquent
 */
class MenuTranslation extends Eloquent
{
    public $timestamps = false;
    protected $table = 'menu_translations';
    protected $fillable = ['label'];
}