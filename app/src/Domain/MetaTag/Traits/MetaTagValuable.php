<?php

namespace Domain\MetaTag\Traits;

use Domain\MetaTag\Models\MetaTagValues;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait MetaTagValuable
{
    public function setMetaTagValues($data): self
    {
        $this->metaTagValues()->updateOrCreate([
            'id' => $this->metaTagValues?->id
        ], $data);

        return $this;
    }

    public function metaTagValues(): MorphOne
    {
        return $this->morphOne(MetaTagValues::class, 'entity', 'entity_type', 'entity_id', 'id');
    }
}
