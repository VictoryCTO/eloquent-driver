<?php

namespace Statamic\Eloquent\Structures;

use Illuminate\Support\Facades\Cache;
use Statamic\Contracts\Structures\Tree as TreeContract;
use Statamic\Stache\Repositories\CollectionTreeRepository as StacheRepository;

class CollectionTreeRepository extends StacheRepository
{
    public function find(string $handle, string $site): ?TreeContract
    {
        $model = Cache::remember('CollectionTreeRepo-'.md5($handle.$site), 300, function() use($handle, $site){
            return TreeModel::whereHandle($handle)
                ->where('locale', $site)
                ->whereType('collection')
                ->first();
        });

        return $model
            ? app(CollectionTree::class)->fromModel($model)
            : null;
    }

    public function save($entry)
    {
        $model = $entry->toModel();

        $model->save();

        $entry->model($model->fresh());
    }
}
