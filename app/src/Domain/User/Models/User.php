<?php

declare(strict_types=1);

namespace Domain\User\Models;

use Database\Factories\UserFactory;
use Domain\BonusLevel\Models\BonusLevel;
use Domain\FavoriteCategory\Models\FavoriteCategory;
use Domain\Image\Traits\Attachable;
use Domain\MobileToken\Models\MobileAppToken;
use Domain\Order\Models\Order;
use Domain\Product\Models\Review;
use Domain\Support\Models\SupportMessage;
use Domain\User\Models\Accessors\User\BonusAccountQrCode;
use Domain\User\Models\Accessors\User\FullName;
use Domain\User\Models\Accessors\User\NameWithPhone;
use Domain\User\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Infrastructure\Eloquent\Traits\Active;
use Infrastructure\Helpers\PhoneFormatterHelper;
use Orchid\Platform\Models\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static UserQueryBuilder query()
 */
class User extends Authenticatable implements JWTSubject
{
    use Active;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;
    use Attachable;

    protected $fillable = [
        'phone',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'sex',
        'birthdate',
        'bonuses',
        'referral_code',
        'email_verified_at',
        'password',
        'permissions',
        'store_system_id',
        'set_card_number',
        'electronic_checks',
        'auto_brightness',
        'registration_type',
        'active',
        'loyalty_session_id',
        'loyalty_id',
        'loyalty_level_id',
        'loyalty_level_progression',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    protected $casts = [
        'permissions'               => 'array',
        'email_verified_at'         => 'datetime',
        'active'                    => 'boolean',
        'loyalty_level_progression' => 'float',
    ];

    protected $allowedSorts = [
        'first_name',
        'phone',
        'email',
        'active',
        'created_at',
        'updated_at',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function supportMessages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSettings::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            FavoriteCategory::class,
            'user_favorite_categories',
            'user_id',
            'category_id',
        )->withPivot(['period']);
    }

    public function bonusLevel(): BelongsToMany
    {
        return $this->belongsToMany(BonusLevel::class, 'user_bonus_level')
            ->withPivot('current_bonus_points')
            ->withTimestamps();
    }

    public function mobileTokens(): HasMany
    {
        return $this->hasMany(MobileAppToken::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'user_id' => $this->id
        ];
    }

    public function getCartNumber(): string
    {
        if (!$this->set_card_number && $this->id) {
            $numberById = 100000000 + $this->id;

            $this->set_card_number = sprintf('5%s', $numberById);

            $this->save();
        }

        return $this->set_card_number;
    }

    public function fullName(): Attribute
    {
        return Attribute::get(new FullName($this));
    }

    public function bonusAccountQrCode(): Attribute
    {
        return Attribute::get(new BonusAccountQrCode($this));
    }


    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function nameWithPhone(): Attribute
    {
        return Attribute::get(new NameWithPhone($this));
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = PhoneFormatterHelper::unformat($value);
    }
}


