<?php

namespace App\Nova;

use App\Nova\Actions\AssignAction;
use App\Nova\Actions\UnassignAction;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
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
                ->hideFromIndex(),
            Text::make(__('Region'), 'region')
                ->sortable(),
            Text::make(__('Town'), 'town')
                ->sortable(),
            Text::make(__('Postal Code'), function () {
                $result = \App\Models\PostalCode::query()->firstWhere([
                    'region' => $this->region,
                    'town' => $this->town,
                    'street' => $this->street,
                    'number' => $this->number,
                ]);

                return $result?->postal_code;
            }),
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
            Date::make(__('Assigned'), function () {
                $user = $this->users()->where('id', auth()->id())->first();
                if ($user) {
                    return $user->pivot->created_at;
                }
                return null;
            })->format('DD/MM/YYYY'),
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
            (new AssignAction())->withoutConfirmation()
                ->canRun(fn() => true),

            (new UnassignAction())->withoutConfirmation()
                ->canRun(fn() => true),
        ];
    }
}
