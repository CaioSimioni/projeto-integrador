<?php

namespace App\Models;

class Role
{
    /**
     * Get the role translations.
     *
     * @return array
     */
    public static function getTranslations(): array
    {
        return [
            'adm' => 'Administrator',
            'aux' => 'Assistant',
            'far' => 'Pharmacist',
            'med' => 'Doctor',
            'chf' => 'Head Nurse',
            'enf' => 'Nurse',
        ];
    }
}
