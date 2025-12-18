<?php

namespace App\Filament\Resources\ContactPageSettingsResource\Pages;

use App\Filament\Resources\ContactPageSettingsResource;
use App\Models\ContactPageSettings;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ManageContactPageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ContactPageSettingsResource::class;

    protected static string $view = 'filament.resources.contact-page-settings-resource.pages.manage-contact-page-settings';

    public ?array $data = [];

    public function mount(): void
    {
        // Load all settings and populate form
        $settings = ContactPageSettings::pluck('value', 'key')->toArray();
        
        // Ensure defaults exist
        $this->data = [
            'page_title' => $settings['page_title'] ?? 'Contact Us',
            'page_description' => $settings['page_description'] ?? '',
            'office_address' => $settings['office_address'] ?? '',
            'phone' => $settings['phone'] ?? '',
            'email' => $settings['email'] ?? '',
            'whatsapp' => $settings['whatsapp'] ?? '',
            'general_email' => $settings['general_email'] ?? 'info@darlingfm.ng',
            'music_email' => $settings['music_email'] ?? 'music@darlingfm.ng',
            'partnerships_email' => $settings['partnerships_email'] ?? 'partners@darlingfm.ng',
            'map_url' => $settings['map_url'] ?? 'https://maps.app.goo.gl/qPWKXDAngcD8thcc9',
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Page Content')
                    ->schema([
                        Forms\Components\TextInput::make('page_title')
                            ->label('Page Title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('page_description')
                            ->label('Page Description')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                            ])
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\Textarea::make('office_address')
                            ->label('Office Address')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('WhatsApp Number')
                            ->tel()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Email Categories')
                    ->description('Email addresses for different contact categories displayed on the contact page')
                    ->schema([
                        Forms\Components\TextInput::make('general_email')
                            ->label('General Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->helperText('Email for general inquiries'),
                        Forms\Components\TextInput::make('music_email')
                            ->label('Music Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->helperText('Email for music-related inquiries'),
                        Forms\Components\TextInput::make('partnerships_email')
                            ->label('Partnerships Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->helperText('Email for partnership inquiries'),
                    ])
                    ->columns(1),
                Forms\Components\Section::make('Map & Location')
                    ->schema([
                        Forms\Components\TextInput::make('map_url')
                            ->label('Google Maps URL')
                            ->url()
                            ->maxLength(500),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Save each setting
        foreach ($data as $key => $value) {
            ContactPageSettings::updateOrCreate(
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
        return 'Contact Page Settings';
    }
}

