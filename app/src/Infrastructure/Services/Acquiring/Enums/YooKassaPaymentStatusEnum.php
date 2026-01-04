<?php

namespace Infrastructure\Services\Acquiring\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self pending()
 * @method static self waitingForCapture()
 * @method static self succeeded()
 * @method static self canceled()
 */
class YooKassaPaymentStatusEnum extends Enum
{
    private const PENDING = 'pending';

    private const WAITING_FOR_CAPTURE = 'waiting_for_capture';

    private const SUCCEEDED = 'succeeded';

    private const CANCELED = 'canceled';

    protected static function labels(): array
    {
        return [
            self::PENDING               => 'Ожидание оплаты',
            'waitingForCapture'         => 'Оплачен',
            self::SUCCEEDED             => 'Завершен',
            self::CANCELED              => 'Отменен',
        ];
    }

    protected static function values(): array
    {
        return [
            'waitingForCapture'         => self::WAITING_FOR_CAPTURE,
        ];
    }
}
