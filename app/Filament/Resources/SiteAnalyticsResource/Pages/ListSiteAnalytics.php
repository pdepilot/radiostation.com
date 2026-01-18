<?php

namespace App\Filament\Resources\SiteAnalyticsResource\Pages;

use App\Filament\Resources\SiteAnalyticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;

class ListSiteAnalytics extends ListRecords
{
    protected static string $resource = SiteAnalyticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - analytics are tracked automatically
        ];
    }

    /**
     * Use cursor pagination for better performance with large datasets
     */
    protected function paginateTableQuery(Builder $query): CursorPaginator
    {
        return $query->cursorPaginate($this->getTableRecordsPerPage() ?: 25);
    }
}
