<?php

namespace App\Observers;

use App\Events\ContentUpdated;
use App\Models\Show;

class ShowObserver
{
    /**
     * Handle the Show "created" event.
     */
    public function created(Show $show): void
    {
        event(new ContentUpdated(
            'show',
            $show->id,
            'created',
            [
                'title' => $show->title,
                'slug' => $show->slug,
                'image' => $show->hero_image_url,
            ]
        ));
    }

    /**
     * Handle the Show "updated" event.
     */
    public function updated(Show $show): void
    {
        event(new ContentUpdated(
            'show',
            $show->id,
            'updated',
            [
                'title' => $show->title,
                'slug' => $show->slug,
                'image' => $show->hero_image_url,
            ]
        ));
    }

    /**
     * Handle the Show "deleted" event.
     */
    public function deleted(Show $show): void
    {
        event(new ContentUpdated(
            'show',
            $show->id,
            'deleted',
            []
        ));
    }
}

