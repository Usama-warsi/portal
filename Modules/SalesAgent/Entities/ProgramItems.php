<?php

namespace Modules\SalesAgent\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramItems extends Model
{
    use HasFactory;
    protected $table = 'sales_agents_program_items';
    
    protected $fillable = [
        'id',
        'program_id',
        'product_type',
        'items',
        'from_amount',
        'to_amount',
        'discount',
    ];
    
    protected static function newFactory()
    {
        return \Modules\SalesAgent\Database\factories\ProgramItemsFactory::new();
    }
}
