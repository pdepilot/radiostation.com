<?php

namespace App\Observers;

use App\Events\ContentUpdated;
use App\Models\Event;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        event(new ContentUpdated(
            'event',
            $event->id,
            'created',
            [
                'title' => $event->title,
                'slug' => $event->slug,
                'image' => $event->hero_image,
                'date' => $event->event_date?->format('M d, Y'),
            ]
        ));
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        event(new ContentUpdated(
            'event',
            $event->id,
            'updated',
            [
                'title' => $event->title,
                'slug' => $event->slug,
                'image' => $event->hero_image,
                'date' => $event->event_date?->format('M d, Y'),
            ]
        ));
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        event(new ContentUpdated(
            'event',
            $event->id,
            'deleted',
            []
        ));
    }
}

