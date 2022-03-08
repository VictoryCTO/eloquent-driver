<?php

namespace Statamic\Eloquent\Globals;

use Illuminate\Support\Facades\Cache;
use Statamic\Contracts\Globals\GlobalSet as GlobalSetContract;
use Statamic\Globals\GlobalCollection;
use Statamic\Stache\Repositories\GlobalRepository as StacheRepository;

class GlobalRepository extends StacheRepository
{
    protected function transform($items, $columns = [])
    {
        return GlobalCollection::make($items)->map(function ($model) {
            return GlobalSet::fromModel($model);
        });
    }

    public function find($handle): ?GlobalSetContract
    {
        return app(GlobalSetContract::class)->fromModel(GlobalSetModel::whereHandle($handle)->firstOrFail());
    }

    public function findByHandle($handle): ?GlobalSetContract
    {
        return Cache::remember('GlobalRepo-'.md5($handle), 400, function() use($handle) {
            return app(GlobalSetContract::class)->fromModel(GlobalSetModel::whereHandle($handle)->firstOrFail());
        });
    }

    public function all(): GlobalCollection
    {
        return $this->transform(GlobalSetModel::all());
    }

    public function save($entry)
    {
        $model = $entry->toModel();

        $model->save();

        $entry->model($model->fresh());
    }

    public function delete($entry)
    {
        $entry->model()->delete();
    }

    public static function bindings(): array
    {
        return [
            GlobalSetContract::class => GlobalSet::class,
        ];
    }
}
