<?php

namespace Modules\Spreadsheet\Entities;

use App\Models\User;
use Google\Service\Sheets\Spreadsheet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spreadsheets extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'folder_name',
        'path',
        'parent_id',
        'user_id',
        'user_assign',
        'user_and_per',
        'related',
        'related_assign',
        'type',
        'workspace',
        'created_by',
    ];

    protected static function newFactory()
    {
        return \Modules\Spreadsheet\Database\factories\SpreadsheetsFactory::new();
    }

    public static $permission = [
        'View' => 'View' ,
        'Edit' => 'Edit',
    ];

    public function parentFolder()
    {
        return $this->belongsTo(Spreadsheets::class, 'parent_id');
    }

    public function childFolders()
    {
        return $this->hasMany(Spreadsheets::class, 'parent_id');
    }

    public function relatedGet()
    {
        return $this->hasOne(Related::class, 'id', 'related');
    }
}
