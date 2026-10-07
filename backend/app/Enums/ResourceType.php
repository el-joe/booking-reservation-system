<?php

declare(strict_types=1);

namespace App\Enums;

enum ResourceType: string
{
    case Room = 'room';
    case Table = 'table';
    case Vehicle = 'vehicle';
    case Slot = 'slot';
    case Court = 'court';
    case Equipment = 'equipment';
    case Staff = 'staff';
    case Package = 'package';

    public function label(): string
    {
        return match ($this) {
            self::Room => 'Room',
            self::Table => 'Table',
            self::Vehicle => 'Vehicle',
            self::Slot => 'Slot',
            self::Court => 'Court',
            self::Equipment => 'Equipment',
            self::Staff => 'Staff',
            self::Package => 'Package',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Room => 'blue',
            self::Table => 'orange',
            self::Vehicle => 'cyan',
            self::Slot => 'green',
            self::Court => 'lime',
            self::Equipment => 'yellow',
            self::Staff => 'indigo',
            self::Package => 'purple',
        };
    }
}
