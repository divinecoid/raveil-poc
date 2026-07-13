<?php

namespace App\Filament\Resources\Roles\Schemas;

use App\Models\Role;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $resources = [
            'sales_orders' => 'Sales Orders',
            'invoices' => 'Invoices',
            'products' => 'Products',
            'customers' => 'Customers',
            'vehicles' => 'Vehicles',
            'expenses' => 'Expenses',
            'projects' => 'Projects',
            'tasks' => 'Tasks',
            'settings' => 'Settings',
            'brands' => 'Brands',
            'car_models' => 'Car Models',
            'categories' => 'Categories',
            'users' => 'User Management',
            'roles' => 'Role Management',
        ];

        $permissionsSchema = [];
        
        // Table Header
        $permissionsSchema[] = Grid::make(6)
            ->schema([
                Placeholder::make('header_resource')
                    ->hiddenLabel()
                    ->content(new \Illuminate\Support\HtmlString('<strong>Menu / Fitur</strong>')),
                Checkbox::make('select_all_global')
                    ->label('Pilih Semua')
                    ->dehydrated(false)
                    ->live()
                    ->disabled(fn (?Role $record) => $record && strtolower($record->name) === 'superadmin')
                    ->formatStateUsing(function (?Role $record) {
                        if (!$record) return false;
                        if (!$record->permissions) return false;
                        foreach ($record->permissions as $resKey => $resPerms) {
                            if (empty($resPerms['view']) || empty($resPerms['create']) || empty($resPerms['edit']) || empty($resPerms['delete'])) {
                                return false;
                            }
                        }
                        return true;
                    })
                    ->afterStateUpdated(function ($state, $set) use ($resources) {
                        foreach ($resources as $key => $label) {
                            $set("permissions.{$key}.all", $state);
                            $set("permissions.{$key}.view", $state);
                            $set("permissions.{$key}.create", $state);
                            $set("permissions.{$key}.edit", $state);
                            $set("permissions.{$key}.delete", $state);
                        }
                    }),
                Placeholder::make('header_view')
                    ->hiddenLabel()
                    ->content(new \Illuminate\Support\HtmlString('<strong>Lihat (View)</strong>')),
                Placeholder::make('header_create')
                    ->hiddenLabel()
                    ->content(new \Illuminate\Support\HtmlString('<strong>Tambah (Create)</strong>')),
                Placeholder::make('header_edit')
                    ->hiddenLabel()
                    ->content(new \Illuminate\Support\HtmlString('<strong>Ubah (Edit)</strong>')),
                Placeholder::make('header_delete')
                    ->hiddenLabel()
                    ->content(new \Illuminate\Support\HtmlString('<strong>Hapus (Delete)</strong>')),
            ])
            ->extraAttributes(['class' => 'pb-2 border-b border-gray-200 dark:border-gray-700 align-middle']);

        // Rows
        foreach ($resources as $key => $label) {
            $updateAllCheckbox = function ($get, $set) use ($key, $resources) {
                // Update row's "all" checkbox
                $rowChecked = $get("permissions.{$key}.view") &&
                              $get("permissions.{$key}.create") &&
                              $get("permissions.{$key}.edit") &&
                              $get("permissions.{$key}.delete");
                $set("permissions.{$key}.all", $rowChecked);

                // Update global "select_all_global" checkbox
                $allGlobalChecked = true;
                foreach ($resources as $resKey => $labelName) {
                    if (!$get("permissions.{$resKey}.view") ||
                        !$get("permissions.{$resKey}.create") ||
                        !$get("permissions.{$resKey}.edit") ||
                        !$get("permissions.{$resKey}.delete")
                    ) {
                        $allGlobalChecked = false;
                        break;
                    }
                }
                $set('select_all_global', $allGlobalChecked);
            };

            $permissionsSchema[] = Grid::make(6)
                ->schema([
                    Placeholder::make($key . '_label')
                        ->hiddenLabel()
                        ->content($label),
                    Checkbox::make("permissions.{$key}.all")
                        ->hiddenLabel()
                        ->live()
                        ->disabled(fn (?Role $record) => $record && strtolower($record->name) === 'superadmin')
                        ->afterStateUpdated(function ($state, $set, $get) use ($key, $resources) {
                            $set("permissions.{$key}.view", $state);
                            $set("permissions.{$key}.create", $state);
                            $set("permissions.{$key}.edit", $state);
                            $set("permissions.{$key}.delete", $state);

                            // Update global "select_all_global"
                            $allGlobalChecked = true;
                            foreach ($resources as $resKey => $labelName) {
                                $view = ($resKey === $key) ? $state : $get("permissions.{$resKey}.view");
                                $create = ($resKey === $key) ? $state : $get("permissions.{$resKey}.create");
                                $edit = ($resKey === $key) ? $state : $get("permissions.{$resKey}.edit");
                                $delete = ($resKey === $key) ? $state : $get("permissions.{$resKey}.delete");

                                if (!$view || !$create || !$edit || !$delete) {
                                    $allGlobalChecked = false;
                                    break;
                                }
                            }
                            $set('select_all_global', $allGlobalChecked);
                        }),
                    Checkbox::make("permissions.{$key}.view")
                        ->hiddenLabel()
                        ->live()
                        ->disabled(fn (?Role $record) => $record && strtolower($record->name) === 'superadmin')
                        ->afterStateUpdated($updateAllCheckbox),
                    Checkbox::make("permissions.{$key}.create")
                        ->hiddenLabel()
                        ->live()
                        ->disabled(fn (?Role $record) => $record && strtolower($record->name) === 'superadmin')
                        ->afterStateUpdated($updateAllCheckbox),
                    Checkbox::make("permissions.{$key}.edit")
                        ->hiddenLabel()
                        ->live()
                        ->disabled(fn (?Role $record) => $record && strtolower($record->name) === 'superadmin')
                        ->afterStateUpdated($updateAllCheckbox),
                    Checkbox::make("permissions.{$key}.delete")
                        ->hiddenLabel()
                        ->live()
                        ->disabled(fn (?Role $record) => $record && strtolower($record->name) === 'superadmin')
                        ->afterStateUpdated($updateAllCheckbox),
                ])
                ->extraAttributes(['class' => 'py-2 border-b border-gray-100 dark:border-gray-800 align-middle']);
        }

        return $schema
            ->components([
                Section::make('Role Information')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn (?Role $record) => $record && strtolower($record->name) === 'superadmin'),
                    ]),

                Section::make('Access Control Matrix')
                    ->columnSpanFull()
                    ->description('Tentukan hak akses untuk role ini pada masing-masing menu/fitur.')
                    ->schema($permissionsSchema),
            ]);
    }
}
