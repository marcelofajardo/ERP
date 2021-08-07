<div class="float-left btn-area">
    <a href="{{route('getSites')}}" class="btn btn-secondary {{\Request::route()->getName() === 'getSites' ? 'active' : ''}}">Sites</a>
    <a href="{{url('se-ranking/keywords')}}" class="btn btn-secondary {{\Request::url() === 'se-ranking/keywords' ? 'active' : ''}}">KeyWords</a>
    <a href="{{route('getCompetitors')}}" class="btn btn-secondary {{\Request::route()->getName() === 'getCompetitors' ? 'active' : ''}}">Competitors</a>
    <a href="{{route('getAnalytics')}}" class="btn btn-secondary {{\Request::route()->getName() === 'getAnalytics' ? 'active' : ''}}">SEO Potential</a>
    <a href="{{route('getBacklinks')}}" class="btn btn-secondary {{\Request::route()->getName() === 'getBacklinks' ? 'active' : ''}}">Backlinks</a>
    <a href="{{route('getResearchData')}}" class="btn btn-secondary {{\Request::route()->getName() === 'getResearchData' ? 'active' : ''}}">Domain Overview (SEO/PPC Research Data)</a>
    <a href="{{route('getSiteAudit')}}" class="btn btn-secondary {{\Request::route()->getName() === 'getSiteAudit' ? 'active' : ''}}">Audit Report</a>
</div>