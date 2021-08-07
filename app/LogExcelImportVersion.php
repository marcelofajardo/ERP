<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogExcelImportVersion extends Model
{
    //
    protected $fillable = ['filename','file_version','log_excel_imports_id'];
}
