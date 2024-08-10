<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentConfiguration extends Model
{
    use HasFactory;

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    protected $table = 'document_configurations';
    protected $guarded = ['id'];

    public function document():BelongsTo
    {
        return $this->belongsTo(Document::class);
    }


}
