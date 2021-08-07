<?php

namespace App\Hubstaff;

use Illuminate\Database\Eloquent\Model;

class HubstaffProject extends Model
{
    protected $fillable = [
        'hubstaff_project_id',
        'organisation_id',
        'hubstaff_project_name',
        'hubstaff_project_description',
        'hubstaff_project_status'
    ];

    static function updateOrCreateApiProjects($projects){
        foreach ($projects as $project)
                    HubstaffProject::updateOrCreate(
                        [
                            'hubstaff_project_id' => $project->id
                        ],
                        [
                            'hubstaff_project_id' => $project->id,
                            // 'organisation_id' => getenv('HUBSTAFF_ORG_ID'),
                            'organisation_id' => config('env.HUBSTAFF_ORG_ID'),
                            'hubstaff_project_name' => $project->name,
                            'hubstaff_project_description' => isset($project->description)?$project->description:'',
                            'hubstaff_project_status' => $project->status
                        ]
                    );
    }

    function editProject($projectName, $projectDescription){
        $this->hubstaff_project_name = $projectName;
        $this->hubstaff_project_description = $projectDescription;
        $this->save();
    }
}
