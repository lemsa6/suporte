<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'name',
        'description',
        'is_public',
    ];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return $setting->value;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, $value)
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings as key-value pairs
     *
     * @param string|null $group
     * @return array
     */
    public static function getAllSettings(?string $group = null): array
    {
        $query = self::query();
        
        if ($group) {
            $query->where('group', $group);
        }
        
        return $query->pluck('value', 'key')->toArray();
    }
}
