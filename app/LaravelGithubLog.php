<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class LaravelGithubLog extends Model
{
	  /**
     * @var string
     * @SWG\Property(property="log_time",type="string")
     * @SWG\Property(property="log_file_name",type="string")
     * @SWG\Property(property="file",type="string")
     * @SWG\Property(property="author",type="string")
     * @SWG\Property(property="commit_time",type="string")
     * @SWG\Property(property="commit",type="string")
     * @SWG\Property(property="stacktrace",type="string")
     */
    protected $fillable = [
        'log_time',
        'log_file_name',
        'file',
        'author',
        'commit_time',
        'commit',
        'stacktrace'
    ];

    
}
