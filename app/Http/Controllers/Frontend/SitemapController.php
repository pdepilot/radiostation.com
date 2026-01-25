<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dj;
use App\Models\Event;
use App\Models\NewsPost;
use App\Models\Show;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        $sitemap .= $this->urlTag(url('/'), now(), 'daily', '1.0');
        
        // Shows
        $sitemap .= $this->urlTag(route('shows.index'), now()->subDays(1), 'weekly', '0.9');
        Show::where('status', 'active')->each(function ($show) use (&$sitemap) {
            $sitemap .= $this->urlTag(route('shows.show', $show), $show->updated_at, 'weekly', '0.8');
        });

        // News
        $sitemap .= $this->urlTag(route('news.index'), now()->subDays(1), 'daily', '0.9');
        NewsPost::where('status', 'published')->each(function ($post) use (&$sitemap) {
            $sitemap .= $this->urlTag(route('news.show', $post), $post->updated_at, 'weekly', '0.8');
        });

        // Events
        $sitemap .= $this->urlTag(route('events.index'), now()->subDays(1), 'weekly', '0.9');
        Event::all()->each(function ($event) use (&$sitemap) {
            $sitemap .= $this->urlTag(route('events.show', $event), $event->updated_at, 'monthly', '0.7');
        });

        // OAPs
        Dj::all()->each(function ($dj) use (&$sitemap) {
            $sitemap .= $this->urlTag(route('djs.show', $dj), $dj->updated_at, 'monthly', '0.7');
        });

        // Policy pages
        $sitemap .= $this->urlTag(route('privacy'), now()->subMonths(6), 'yearly', '0.5');
        $sitemap .= $this->urlTag(route('terms'), now()->subMonths(6), 'yearly', '0.5');
        $sitemap .= $this->urlTag(route('faq'), now()->subMonths(3), 'monthly', '0.6');
        $sitemap .= $this->urlTag(route('contact.index'), now()->subDays(7), 'monthly', '0.7');

        $sitemap .= '</urlset>';

        return response($sitemap, 200)
            ->header('Content-Type', 'application/xml');
    }

    private function urlTag(string $url, $lastmod, string $changefreq, string $priority): string
    {
        return sprintf(
            "  <url>\n    <loc>%s</loc>\n    <lastmod>%s</lastmod>\n    <changefreq>%s</changefreq>\n    <priority>%s</priority>\n  </url>\n",
            htmlspecialchars($url),
            $lastmod->format('Y-m-d'),
            $changefreq,
            $priority
        );
    }
}
