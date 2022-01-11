<?php

namespace App\Nova;

use App\Nova\Actions\AssignAction;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Property extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Property::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'reference';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'reference',
        'region',
        'town',
        'street',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            //ID::make(__('ID'), 'id')->sortable(),
            Text::make(__('Reference'), 'reference')
                ->sortable(),
            Text::make(__('Region'), 'region')
                ->sortable(),
            Text::make(__('Town'), 'town')
                ->sortable(),
            Text::make(__('Street'), 'street')
                ->sortable(),
            Text::make(__('Number'), 'number')
                ->sortable(),
            Text::make(__('Stair'), 'stair')
                ->sortable(),
            Text::make(__('Floor'), 'floor')
                ->sortable(),
            Text::make(__('Door'), 'door')
                ->sortable(),
            Boolean::make(__('Assigned'), 'assigned_at'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            (new AssignAction())->withoutConfirmation(),
        ];
    }
}
