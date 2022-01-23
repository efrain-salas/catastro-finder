<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\PostalCode
 *
 * @property int $id
 * @property string $region
 * @property string $town
 * @property string $street
 * @property string $number
 * @property string $postal_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode whereTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostalCode whereUpdatedAt($value)
 */
	class PostalCode extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Property
 *
 * @property int $id
 * @property string $reference
 * @property string $usageType
 * @property string $region
 * @property string $town
 * @property string $street
 * @property string $number
 * @property string|null $stair
 * @property string|null $floor
 * @property string|null $door
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereDoor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereFloor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereStair($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereTown($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereUsageType($value)
 */
	class Property extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Property[] $properties
 * @property-read int|null $properties_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

