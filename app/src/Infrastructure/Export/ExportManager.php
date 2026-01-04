<?php

namespace Infrastructure\Export;

use Domain\Audience\Export\AudienceUsersExport;
use Domain\Audience\Export\AudienceUsersFileExampleExport;
use Domain\Product\Export\ExpectedProductsExport;
use Domain\Promocode\Export\Excel\PromoExport;

class ExportManager
{
    private const TYPES = [
        'expected_products'     => ExpectedProductsExport::class,
        'feedback'              => FeedbackExport::class,
        'farmer_questionnaires' => FarmerQuestionnaireExport::class,
        'order'                 => OrderExport::class,
        'promo'                 => PromoExport::class,
        'abandoned_baskets'     => AbandonedBasketsExport::class,
        'analytic_journal'      => AnalyticJournalExport::class,
        'analytic_upload_user'  => AnalyticUploadUserExport::class,
        'analytic_activity'     => AnalyticActivityExport::class,
        'story_metadata'        => StoryMetadataExport::class,
        'audience_users'        => AudienceUsersFileExampleExport::class,
        'audience_lists'        => AudienceUsersExport::class,
    ];

    public static function exportByType(string $type, array $filter)
    {
        $types = static::TYPES;
        $exportClass = $types[$type];

        if (!$exportClass && !class_exists($type)) {
            throw new \RuntimeException('Export class not found for this type');
        }

        return new $exportClass($filter);
    }
}
