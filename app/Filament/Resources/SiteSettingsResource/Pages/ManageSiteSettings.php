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
            'whatsapp_number' => $settings['whatsapp_number'] ?? '+2348064444444',
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
                            ->maxLength(255)
                            ->placeholder('https://facebook.com/darlingfm')
                            ->helperText('Full URL to your Facebook page (leave empty to hide icon)'),
                        Forms\Components\TextInput::make('twitter_url')
                            ->label('Twitter/X URL')
                            ->maxLength(255)
                            ->placeholder('https://twitter.com/darlingfm')
                            ->helperText('Full URL to your Twitter/X profile (leave empty to hide icon)'),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram URL')
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/darlingfm')
                            ->helperText('Full URL to your Instagram profile (leave empty to hide icon)'),
                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/@darlingfm')
                            ->helperText('Full URL to your YouTube channel (leave empty to hide icon)'),
                        Forms\Components\TextInput::make('tiktok_url')
                            ->label('TikTok URL')
                            ->maxLength(255)
                            ->placeholder('https://tiktok.com/@darlingfm')
                            ->helperText('Full URL to your TikTok profile (leave empty to hide icon)'),
                    ])
                    ->columns(1),
                Forms\Components\Section::make('Contact Information')
                    ->description('Contact details used across the site, including the floating WhatsApp button')
                    ->schema([
                        Forms\Components\TextInput::make('whatsapp_number')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('+234 806 444 4444')
                            ->helperText('This number is used for the WhatsApp floating button and contact sections. Include country code (e.g., +234)'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
        $data = $this->form->getState();

            // Save each setting - allow empty values to remove links
        foreach ($data as $key => $value) {
                // Trim whitespace and convert empty strings to null
                $cleanValue = is_string($value) ? trim($value) : ($value ?? null);
                $cleanValue = $cleanValue === '' ? null : $cleanValue;
                
            SiteSetting::updateOrCreate(
                ['key' => $key],
                    [
                        'value' => $cleanValue,
                        'type' => 'text',
                    ]
                );
            }

            // Reload form with fresh data to reset dirty state
            $settings = SiteSetting::pluck('value', 'key')->toArray();
            $this->data = [
                'facebook_url' => $settings['facebook_url'] ?? '',
                'twitter_url' => $settings['twitter_url'] ?? '',
                'instagram_url' => $settings['instagram_url'] ?? '',
                'youtube_url' => $settings['youtube_url'] ?? '',
                'tiktok_url' => $settings['tiktok_url'] ?? '',
                'whatsapp_number' => $settings['whatsapp_number'] ?? '+2348064444444',
            ];
            $this->form->fill($this->data);
            
            // Reset form to clear dirty state
            $this->form->model(null);
            $this->form->fill($this->data);

        \Filament\Notifications\Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Filament\Notifications\Notification::make()
                ->title('Validation error')
                ->body('Please check your input and try again.')
                ->danger()
                ->send();
        } catch (\Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title('Error saving settings')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        // Return empty array - we're using AJAX save button instead
        return [];
    }

    public function getTitle(): string | Htmlable
    {
        return 'Site Settings';
    }
}

