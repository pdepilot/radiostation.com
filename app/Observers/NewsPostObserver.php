<?php

namespace App\Observers;

use App\Events\ContentUpdated;
use App\Models\NewsPost;

class NewsPostObserver
{
    /**
     * Handle the NewsPost "created" event.
     */
    public function created(NewsPost $newsPost): void
    {
        if ($newsPost->status === 'published') {
            event(new ContentUpdated(
                'news',
                $newsPost->id,
                'created',
                [
                    'title' => $newsPost->title,
                    'slug' => $newsPost->slug,
                    'image' => $newsPost->hero_image,
                    'date' => $newsPost->published_at?->format('M d, Y'),
                ]
            ));
        }
    }

    /**
     * Handle the NewsPost "updated" event.
     */
    public function updated(NewsPost $newsPost): void
    {
        if ($newsPost->status === 'published') {
            event(new ContentUpdated(
                'news',
                $newsPost->id,
                'updated',
                [
                    'title' => $newsPost->title,
                    'slug' => $newsPost->slug,
                    'image' => $newsPost->hero_image,
                    'date' => $newsPost->published_at?->format('M d, Y'),
                ]
            ));
        }
    }

    /**
     * Handle the NewsPost "deleted" event.
     */
    public function deleted(NewsPost $newsPost): void
    {
        event(new ContentUpdated(
            'news',
            $newsPost->id,
            'deleted',
            []
        ));
    }
}

