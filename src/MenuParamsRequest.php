<?php namespace Minhbang\Menu;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class MenuParamsRequest
 *
 * @property-read \Minhbang\Menu\Menu $menu
 * @package Minhbang\Menu
 */
class MenuParamsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->menu && ($menuType = $this->menu->typeInstance()) ? $menuType->paramsRules : [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return $this->menu && ($menuType = $this->menu->typeInstance()) ? $menuType->paramsAttributes : [];
    }
}