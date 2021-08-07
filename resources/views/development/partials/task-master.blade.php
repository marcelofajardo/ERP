<style type="text/css">
    .green-notification { 
        color:green;
     }
    .red-notification { 
        color:grey;
     }   
</style>
<table class="table table-bordered table-striped" style="table-layout:fixed;">
    <tr>
        <th style="width:15%;">ID</th>
        <th style="width:8%;">Module</th>
        <th style="width:12%;">Subject</th>
        <th style="width:20%;">Communication</th>
        <th style="width:10%;">Est Completion Time</th>
        <th style="width:10%;">Est Completion Date</th>
        <th style="width:7%;">Tracked Time</th>
        <th style="width:15%;">Developers</th>
        <th style="width:12%;">Status</th>
        <th style="width:6%;">Cost</th>
        <th style="width:8%;">Milestone</th>
    </tr>
    <?php
        $isReviwerLikeAdmin =  auth()->user()->isReviwerLikeAdmin();
        $userID =  Auth::user()->id;
    ?>
    @foreach ($issues as $key => $issue)
        @if($isReviwerLikeAdmin)
            @include("development.partials.admin-row-view")
        @elseif($issue->created_by == $userID || $issue->master_user_id == $userID || $issue->assigned_to == $userID || $issue->team_lead_id == $userID || $issue->tester_id == $userID)
            @include("development.partials.developer-row-view")
        @endif
    @endforeach
</table>