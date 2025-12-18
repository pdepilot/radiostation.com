<?php

namespace App\Filament\Resources\SiteSettingsResource\Pages;

use App\Filament\Resources\SiteSettingsResource;
use App\Models\SiteSetting;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SiteSettingsResource::class;

    protected static string $view = 'filament.resources.site-settings-resource.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        // Load all settings and populate form
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        
        // Ensure defaults exist
        $this->data = [
            'facebook_url' => $settings['facebook_url'] ?? '',
            'twitter_url' => $settings['twitter_url'] ?? '',
            'instagram_url' => $settings['instagram_url'] ?? '',
            'youtube_url' => $settings['youtube_url'] ?? '',
            'tiktok_url' => $settings['tiktok_url'] ?? '',
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Social Media Links')
                    ->description('Manage social media links displayed in the footer and other areas of the site')
                    ->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/darlingfm')
                            ->helperText('Full URL to your Facebook page'),
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter/X URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/darlingfm')
                            ->helperText('Full URL to your Twitter/X profile'),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/darlingfm')
                            ->helperText('Full URL to your Instagram profile'),
                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/@darlingfm')
                            ->helperText('Full URL to your YouTube channel'),
                        Forms\Components\TextInput::make('tiktok_url')
                            ->label('TikTok URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://tiktok.com/@darlingfm')
                            ->helperText('Full URL to your TikTok profile'),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save each setting
        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        \Filament\Notifications\Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return 'Site Settings';
    }
}

