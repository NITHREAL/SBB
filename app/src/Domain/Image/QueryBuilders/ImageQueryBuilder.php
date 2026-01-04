<?php

namespace Domain\Image\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Infrastructure\Eloquent\Builder\BaseQueryBuilder;

/**
 * @method static baseQuery()
 * @method static whereOwners(array $ownerIds, string $ownerType)
 */
class ImageQueryBuilder extends BaseQueryBuilder
{
    public function baseQuery(): Builder
    {
        return $this
            ->select([
                'attachments.path',
                'attachments.blur_hash',
                'attachments.name',
                'attachments.original_name',
                'attachments.extension',
                'attachments.disk',
                'attachments.main',
                'attachments.group',
                'attachmentable.attachmentable_id as owner_id',
            ])
            ->leftJoin('attachmentable', 'attachmentable.attachment_id', '=', 'attachments.id')
            ->where('attachments.mime', 'LIKE', 'image/%')
            ->where('attachments.active', true)
            ->orderBy('sort', 'ASC')
            ->orderBy('main', 'DESC');
    }

    public function descriprionQuery(): Builder
    {
        return $this
            ->baseQuery()
            ->addSelect([
                'attachments.id',
                'attachments.group',
                'attachments.description',
            ]);
    }

    public function whereOwners(array $ownerIds, string $ownerType): Builder
    {
        return $this
            ->leftJoin('attachmentable', 'attachmentable.attachment_id', '=', 'attachments.id')
            ->where('attachmentable.attachmentable_type', $ownerType)
            ->whereIn('attachmentable.attachmentable_id', $ownerIds);
    }
}
