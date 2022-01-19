<?php

namespace Statamic\Eloquent\Collections;

use Illuminate\Database\Eloquent\Model as Eloquent;

class CollectionModel extends Eloquent
{
    protected $guarded = [];

    protected $table = 'collections';

    protected $casts = [
        'routes' => 'json',
        'inject' => 'json',
        'taxonomies' => 'json',
        'structure' => 'json',
        'sites' => 'json',
        'revisions' => 'bool',
        'dated' => 'bool',
        'default_publish_state' => 'bool',
        'ampable' => 'bool',
    ];
}
