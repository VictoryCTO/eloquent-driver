<?php

namespace Statamic\Eloquent\Revisions;

use Statamic\Contracts\Revisions\Revision as Contract;
use Statamic\Eloquent\Revisions\RevisionModel as Model;
use Statamic\Events\RevisionDeleted;
use Statamic\Events\RevisionSaved;
use Statamic\Revisions\Revision as FileEntry;
use Statamic\Revisions\WorkingCopy;

class Revision extends FileEntry
{
    protected $id;
    protected $key;
    protected $date;
    protected $user;
    protected $userId;
    protected $message;
    protected $action = 'revision';
    protected $attributes = [];

    protected $model;

    public static function fromModel(Model $model)
    {
        return (new static)
            ->key($model->key)
            ->action($model->action ?? false)
            ->id($model->created_at->timestamp)
            ->date($model->created_at)
            ->user($model->user ?? false)
            ->message($model->message ?? '')
            ->attributes($model->attributes ?? [])
            ->model($model);
    }

    public function toModel()
    {
        $class = app('statamic.eloquent.revisions.model');

        $message = $this->message();

        return $class::findOrNew($this->model?->id)->fill([
            'key' => $this->key(),
            'action' => $this->action(),
            'user' => $this->user()->id(),
            'message' => $message == '0' ? '' : $message,
            'attributes' => collect($this->attributes())->except('id'),
        ]);
    }

    public function fromRevisionOrWorkingCopy($item)
    {
        return (new static)
            ->key($item->key())
            ->action($item instanceof WorkingCopy ? 'working' : 'revision')
            ->date($item->date())
            ->user($item->user()?->id() ?? false)
            ->message($item->message() ?? '')
            ->attributes($item->attributes() ?? []);
    }

    public function model($model = null)
    {
        if (func_num_args() === 0) {
            return $this->model;
        }

        $this->model = $model;

        return $this;
    }

    public function save()
    {
        $this->model->save();

        RevisionSaved::dispatch($this);
    }

    public function delete()
    {
        $this->model->delete();

        RevisionDeleted::dispatch($this);
    }
}
