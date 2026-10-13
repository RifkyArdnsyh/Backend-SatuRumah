<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Pengaturan dinamis (SLA, batas upload). Bila belum ada baris di database,
 * dipakai nilai bawaan dari config/satu_rumah.php (yang bisa diatur lewat .env).
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value', 'description'];

    /** @var array<string, string>|null */
    protected static ?array $cache = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        if (static::$cache === null) {
            static::$cache = static::query()->pluck('value', 'key')->all();
        }

        if (array_key_exists($key, static::$cache)) {
            return static::$cache[$key];
        }

        return config('satu_rumah.defaults.' . $key, $default);
    }

    public static function getInt(string $key, int $default = 0): int
    {
        return (int) static::get($key, $default);
    }

    public static function set(string $key, mixed $value, ?string $description = null): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            array_filter(['value' => (string) $value, 'description' => $description], fn($v) => $v !== null)
        );

        static::$cache = null;
    }
}
