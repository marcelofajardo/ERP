<div class="table-responsive mt-3">
    <table class="table table-bordered" id="master-table">
        <tbody>
          <tr>
            <td>Database</td>
            <td colspan="6">
              <table style="width: 100%;">
                  <tr>
                    <th>Current Size</th>
                    <th>Size Before</th>
                  </tr>
                  <tr>
                      <td>{{ ($currentSize) ? $currentSize->size : "N/A" }}</td>
                      <td>{{ ($sizeBefore) ? $sizeBefore->size : "N/A" }}</td>
                  </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>Database Table</td>
            <td colspan="6">
              <table style="width: 100%;">
                  <tr>
                    <th>Table</th>
                    <th>Size</th>
                  </tr>
                  @if(!empty($topFiveTables))
                    @foreach($topFiveTables as $tft)
                      <tr>
                          <td>{{ $tft->database_name }}</td>
                          <td>{{ number_format($tft->size/1024,2,'.','') }}</td>
                      </tr>
                    @endforeach
                  @endif
              </table>
            </td>
          </tr>
          <tr>
            <td>Development</td>
            <td colspan="6">
              <table style="width: 100%;">
                  <tr>
                    <th>Repository</th>
                    <th>Open Pull Request</th>
                  </tr>
                  <?php if(!empty($repoArr)) { ?>
                    <?php foreach($repoArr as $repo) { ?>
                    <?php $totalRequest = !empty($repo['pulls']) ? count($repo['pulls']) : 0 ?>
                    <?php if($totalRequest > 0) { ?>  
                        <tr>
                            <td>{{ !empty($repo['name']) ? $repo['name'] : "N/A" }}</td>
                            <td>{{ !empty($repo['pulls']) ? count($repo['pulls']) : 0 }}</td>
                        </tr>
                    <?php } ?>    
                    <?php } ?>  
                  <?php } ?>  
              </table>
            </td>
          </tr>
          <tr>
            <td>Whatsapp</td>
            <td colspan="6">
              <table style="width: 100%;">
                  <tr>
                    <th>Last 3 hours</th>
                    <th>Last 24 hours</th>
                  </tr>
                  <tr>
                      <td>{{ isset($last3HrsMsg) ? $last3HrsMsg->cnt : 0 }}</td>
                      <td>{{ isset($last24HrsMsg) ? $last24HrsMsg->cnt : 0 }}</td>
                  </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>Crop Reports</td>
            <td colspan="6">
              <table style="width: 100%;">
                  <tr>
                    <th>Last 1 hours</th>
                    <th>Last 24 hours</th>
                  </tr>
                  <tr>
                      <td>{{ !empty($scraper1hrsReports) ? $scraper1hrsReports->cnt : 0 }}</td>
                      <td>{{ !empty($scraper24hrsReports) ? $scraper24hrsReports->cnt : 0 }}</td>
                  </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>Cron jobs</td>
            <td colspan="6">
              <table style="width: 100%;">
                  <tr>
                    <th>Signature</th>
                    <th>Start time</th>
                    <th>Last error</th>
                  </tr>
                  <?php if(!empty($cronjobReports)){ ?>
                      <?php foreach($cronjobReports as $cronLastError) { ?>
                        <tr>
                          <td>{{ $cronLastError->signature }}</td>
                          <td>{{ $cronLastError->start_time }}</td>
                          <td>{{ $cronLastError->last_error }}</td>
                        </tr>
                      <?php } ?>
                  <?php } ?>
              </table>
            </td>
          </tr>

          <tr>
            <td>Scrap</td>
            <td colspan="6">
              <table style="width: 100%;">
                  <tr>
                    <th>Total</th>
                    <th>Failed</th>
                    <th>Validated</th>
                    <th>Errors</th>
                  </tr>
                  <tr>
                      <td>{{ isset($scrapeData[0]->total) ? $scrapeData[0]->total : 0 }}</td>
                      <td>{{ isset($scrapeData[0]->failed) ? $scrapeData[0]->failed : 0 }}</td>
                      <td>{{ isset($scrapeData[0]->validated) ? $scrapeData[0]->validated : 0 }}</td>
                      <td>{{ isset($scrapeData[0]->errors) ? $scrapeData[0]->errors : 0 }}</td>
                  </tr>
              </table>
            </td>
          </tr>
			     <tr>
            <td>Jobs</td>
            <td colspan="6">
              <table style="width: 100%;">
                  <tr>
                    <th>Last 3 hours</th>
                    <th>Last 24 hours</th>
                  </tr>
                  <tr>
                      <td>{{ isset($last3HrsJobs) ? $last3HrsJobs->cnt : 0 }}</td>
                      <td>{{ isset($last24HrsJobs) ? $last24HrsJobs->cnt : 0 }}</td>
                  </tr>
              </table>
            </td>
          </tr>
			<tr>
				<td>Project Directory Size Management</td>
				<td colspan="6">
					<table style="width: 100%;">
						<tr>
							<th>Directory Name</th>
							<th>Parent</th>
							<th>Size (In MB)</th>
							<th>Expected (In MB)</th>
						</tr>
						<tr>
							@foreach($projectDirectoryData as $val)
								<td>{{ isset($val->name) ? $val->name : "" }}</td>
								<td>{{ isset($val->parent) ? $val->parent : "" }}</td>
								<td>{{ isset($val->size) ? number_format($val->size/1048576,0) : "" }}</td>
								<td>{{ isset($val->notification_at) ? number_format($val->notification_at/1048576,0) : "" }}</td>
							@endforeach
						</tr>
					</table>
				</td>
			</tr>
        <tr>
            <td>Memory Usage</td>
            <td>

                <table style="width: 100%;">
                    <tr>
                        <th>Total</th>
                        <th>Used</th>
                        <th>Free</th>
                        <th>Buff & Cache</th>
                        <th>Available</th>
                    </tr>
                    <tr>

                        <td>{{ isset($memory_use) ?? $memory_use->total}}</td>
                        <td>{{ isset($memory_use) ?? $memory_use->used}}</td>
                        <td>{{ isset($memory_use) ?? $memory_use->free}}</td>
                        <td>{{ isset($memory_use) ?? $memory_use->buff_cache}}</td>
                        <td>{{ isset($memory_use) ?? $memory_use->available}}</td>

                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td>API error</td>
            <td>

                <table style="width: 100%;">
                    <tr>
                        <th>Code</th>
                        <th>Total Error</th>
                    </tr>
                    @if(!empty($logRequest))
                      @foreach($logRequest as $lr)
                        <tr>
                            <td>{{ $lr->status_code}}</td>
                            <td>{{ $lr->total_error}}</td>
                        </tr>
                      @endforeach
                    @endif
                </table>

            </td>
        </tr>
       </tbody>
    </table>
</div>