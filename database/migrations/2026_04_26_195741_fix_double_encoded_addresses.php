<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('addresses')->get()->each(function ($row) {
            $update = [];

            foreach (['billing', 'shipping'] as $col) {
                $raw = $row->$col;
                if ($raw === null) continue;

                $decoded = json_decode($raw, true);
                // A string result means the column holds a JSON-encoded JSON string (double-encoded)
                if (is_string($decoded)) {
                    $update[$col] = $decoded; // strip one layer; store the inner JSON string
                }
            }

            if ($update) {
                DB::table('addresses')->where('id', $row->id)->update($update);
            }
        });
    }

    public function down(): void
    {
        // Reversing data normalization is not safe; no-op
    }
};
