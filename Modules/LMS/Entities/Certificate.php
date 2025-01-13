<?php

namespace Modules\LMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'customer_id',
        'issue_date',
        'status',
        'category_id',
        'is_convert',
        'converted_certificate_id',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\LMS\Database\factories\CertificateFactory::new();
    }
}
