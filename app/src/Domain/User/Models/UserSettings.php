<?php

namespace Domain\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 * Class UserSettings
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property bool $allow_notify
 * @property bool $allow_notify_email
 * @property bool $allow_notify_sms
 * @property bool $allow_phone_calls
 * @property bool $news_subscription
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class UserSettings extends Model
{
    protected $table = 'personal_settings';

    protected $casts = [
        'allow_notify'          => 'boolean',
        'allow_notify_push'     => 'boolean',
        'allow_notify_email'    => 'boolean',
        'allow_notify_sms'      => 'boolean',
        'allow_phone_calls'     => 'boolean',
        'news_subscription'     => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'allow_notify',
        'allow_notify_push',
        'allow_notify_email',
        'allow_notify_sms',
        'allow_phone_calls',
        'news_subscription',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
