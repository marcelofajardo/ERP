<div class="table-responsive mt-3">
        <table class="table table-bordered" id="master-table">
            <thead>
            <tr>
                <th>Columns</th>
                <th>S. No</th>
                <th>Page Name</th>
                <th>Particulars</th>
                <th>Time Spent</th>
                <th>Remarks</th>
                <th>Action / Time</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>Broadcasts</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
              <tr>
                <td>Tasks</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
              <tr>
                <td>Statutory Tasks</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
              <tr>
                <td>Orders</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
              <tr>
                <td>Purchases</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
              <tr>
                <td>Scraping</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
              <tr>
                <td>Reviews</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
               <tr>
                <td>Emails</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
               <tr>
                <td>Accounting</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
               <tr>
                <td>Suppliers</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
               <tr>
                <td>Vendors</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
               <tr>
                <td>Customer</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
               <tr>
                <td>Old issues</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
               <tr>
                <td>Excel Scrapping</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table"><div class="table"></div>
                </td>
              </tr>
              <tr>
                <td>Crop Reference Grid</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table">
                  <div class="table">
                   <table>
                   <tr>
                    <th width="32%"></th>
                    <th>Today Cropped</th>
                    <th>Last 7 Days</th>
                    <th>Total Crop Reference</th>
                    <th>Pending Products</th>
                    <th>Products With Out Category</th>
                    
                   </tr>
                   <tr>
                    <td width="32%"></td>
                    <td>{{ $cropReferenceDailyCount }}</td>
                    <td>{{ $cropReferenceWeekCount }}</td>
                    <td>{{ $cropReference }}</td>
                    <td>{{ $pendingCropReferenceProducts }}</td>
                    <td>{{ $pendingCropReferenceCategory }}</td>
                    
                   </tr>
                 </table>
                   </div> 
                </td>
              </tr>




              <tr>
                <td>Product Stats</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>

              <tr>
                <td colspan="7" class="sub-table"><div class="table">
                  <table class="table table-striped table-bordered">
                    <tr>
                        <th>Import</th>
                        <th>Scraping</th>
                        <th>Is being scraped</th>
                        <th>Queued for AI</th>
                        <th>Auto crop</th>
                    </tr>
                    <tr>
                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$import]) ? (int) $productStats[\App\Helpers\StatusHelper::$import] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$scrape]) ? (int) $productStats[\App\Helpers\StatusHelper::$scrape] : 0 }}</td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$isBeingScraped]) ? (int) $productStats[\App\Helpers\StatusHelper::$isBeingScraped] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$AI]) ? (int) $productStats[\App\Helpers\StatusHelper::$AI] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$autoCrop]) ? (int) $productStats[\App\Helpers\StatusHelper::$autoCrop] : 0 }}</td>
                    </tr>
                    <tr>
                        <th>Is being cropped</th>
                        <th>Crop Approval</th>
                        <th>Unknown Category</th>
                        <th>Unknown Color</th>
                        <th>Unknown Measurement</th>
                    </tr>
                    <tr>
                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$isBeingCropped]) ? (int) $productStats[\App\Helpers\StatusHelper::$isBeingCropped] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$cropApproval]) ? (int) $productStats[\App\Helpers\StatusHelper::$cropApproval] : 0 }}</td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$unknownCategory]) ? (int) $productStats[\App\Helpers\StatusHelper::$unknownCategory] : 0 }}</td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$unknownColor]) ? (int) $productStats[\App\Helpers\StatusHelper::$unknownColor] : 0 }}</td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$unknownMeasurement]) ? (int) $productStats[\App\Helpers\StatusHelper::$unknownMeasurement] : 0 }}</td>

                    </tr>
                    <tr>
                        <th>Unknown Composition</th>
                        <th>Unknown Size</th>
                        <th>Manual Attribute</th>
                        <th>Final Approval</th>
                        <th>Queued for Magento</th>
                        
                    </tr>
                    <tr>
                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$unknownComposition]) ? (int) $productStats[\App\Helpers\StatusHelper::$unknownComposition] : 0 }}</td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$unknownSize]) ? (int) $productStats[\App\Helpers\StatusHelper::$unknownSize] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$manualAttribute]) ? (int) $productStats[\App\Helpers\StatusHelper::$manualAttribute] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$finalApproval]) ? (int) $productStats[\App\Helpers\StatusHelper::$finalApproval] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$pushToMagento]) ? (int) $productStats[\App\Helpers\StatusHelper::$pushToMagento] : 0 }} </td>
                        
                    </tr>
                    <tr>
                        <th>In Magento</th>
                        <th>Unable to scrape</th>
                        <th>Unable to scrape images</th>
                        <th>Crop Rejected</th>
                        <th>Crop Skipped</th>
                        
                    </tr>
                    <tr>
                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$inMagento]) ? (int) $productStats[\App\Helpers\StatusHelper::$inMagento] : 0 }}</td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$unableToScrape]) ? (int) $productStats[\App\Helpers\StatusHelper::$unableToScrape] : 0 }}</td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$unableToScrapeImages]) ? (int) $productStats[\App\Helpers\StatusHelper::$unableToScrapeImages] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$cropRejected]) ? (int) $productStats[\App\Helpers\StatusHelper::$cropRejected] : 0 }} </td>

                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$cropSkipped]) ? (int) $productStats[\App\Helpers\StatusHelper::$cropSkipped] : 0 }} </td>
                        
                    </tr>
                    <tr>
                        <th>Send To External Scraper</th>
                        <th colspan="2">&nbsp;</th>
                        <th>Scraped Total</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td>{{ isset($productStats[\App\Helpers\StatusHelper::$requestForExternalScraper]) ? (int) $productStats[\App\Helpers\StatusHelper::$requestForExternalScraper] : 0 }} </td>
                        <td colspan="2">&nbsp;</td>
                        <td style="background-color: #eee;"><strong style="font-size: 1.5em; text-align: center;">{{ isset($resultScrapedProductsInStock[0]->ttl) ? (int) $resultScrapedProductsInStock[0]->ttl : '-' }}</strong></td>
                        <td style="background-color: #eee;"><strong style="font-size: 1.5em; text-align: center;">{{ (int) array_sum($productStats) }}</strong></td>
                    </tr>
                </table>
                </div>
                </td>
              </tr>
              





              <tr>
                <td>Crop Job Errors</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table">
                  <div class="table">
                   <table>
                   <tr>
                    <th>Cron</th>
                    <th>Last Run at</th>
                    <th>Error</th>
                   </tr>
                   <?php if(!empty($cronLastErrors)){ ?>
                     <?php foreach($cronLastErrors as $cronLastError) { ?>
                       <tr>
                          <td>{{ $cronLastError->signature }}</td>
                          <td>{{ $cronLastError->start_time }}</td>
                          <td>{{ $cronLastError->last_error }}</td>
                       </tr>
                      <?php } ?>
                    <?php } ?>
                 </table>
                   </div> 
                </td>
              </tr>
              <tr>
                <td>Latest Scraper Remarks</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table">
                  <div class="table">
                   <table>
                   <tr>
                    <th>No</th>
                    <th>Scraper name</th>
                    <th>Created at</th>
                    <th>User Name</th>
                    <th>Remark</th>
                   </tr>
                   <?php if(!empty($latestRemarks)){  $i = 1;?>
                     <?php foreach($latestRemarks as $latestRemark) { ?>
                       <tr>
                          <td>{{ $i }}</td>
                          <td>{{ $latestRemark->scraper_name }}</td>
                          <td>{{ date("Y-m-d H:i",strtotime($latestRemark->created_at)) }}</td>
                          <td>{{ $latestRemark->user_name }}</td>
                          <td>{{ $latestRemark->remark }}</td>
                       </tr>
                      <?php $i++; } ?>
                    <?php } ?>
                 </table>
                   </div> 
                </td>
              </tr>
              <tr>
                <td>Task History</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table">
                  <div class="table">
                   <table>
                   <tr>
                    <tr>
                        <th>User</th>
                        <th>Task id</th>
                        <th>Description</th>
                        <th>From time</th>
                        <th>Duration</th>
                    </tr>
                   </tr>
                   <?php if(!empty($todaytaskhistory)){  $i = 1;?>
                     <?php foreach($todaytaskhistory as $todaytaskhistory) { ?>
                       <tr>
                          <td>{{ $todaytaskhistory->name }}</td>
                          <td>{{ empty($todaytaskhistory->devtaskId) ? $todaytaskhistory->task_id : $todaytaskhistory->devtaskId }}</td>
                          <td>{{ empty($todaytaskhistory->devtaskId) ? $todaytaskhistory->task_subject : $todaytaskhistory->subject }}</td>
                          <td>{{ $todaytaskhistory->starts_at }}</td>
                          <td>{{ number_format($todaytaskhistory->day_tracked / 60,2,".",",") }}</td>
                       </tr>
                      <?php $i++; } ?>
                    <?php } ?>
                 </table>
                   </div> 
                </td>
              </tr>
              <tr>
                <td>HubStaff Notification</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="7" class="sub-table">
                  <div class="table">
                   <table>
                   <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Daily Availble hr</th>
                    <th>Total Working hr</th>
                    <th>Different</th>
                    <th>Min Percentage</th>
                    <th>Actual Percentage   </th>
                    <th>Reason</th>
                    <th>Status</th>
                   </tr>
                   <?php if(!empty($hubstaffNotifications)){  $i = 1;?>
                     <?php foreach($hubstaffNotifications as $row) { 
                        $dwork = $row['daily_working_hour'] ? number_format($row['daily_working_hour'],2,".","") : 0;
                        $thours = floor($row['total_track'] / 3600);
                        $tminutes = floor(($row['total_track'] / 60) % 60);
                        $twork = $thours.':'.sprintf("%02d", $tminutes);
                        $difference = ( ($row['daily_working_hour'] * 60 * 60 ) - $row['total_track']);
                        $sing = '';
                        if($difference > 0){
                          $sign = '-';
                        }
                        elseif($difference < 0){
                          $sign = '+';
                        }else{
                            $sign = '';
                        }
                        
                        $hours = floor(abs($difference) / 3600);
                        $minutes = sprintf("%02d", floor((abs($difference) / 60) % 60));
                        ?>

                       <tr>
                          <td>{{ $i }}</td>
                          <td>{{ $row['user_name'] }}</td>
                          <td>{{ $dwork }}</td>
                          <td>{{ $twork }}</td>
                          <td>{{ $sign.$hours.':'.$minutes }}</td>
                          <td>{{ $row['min_percentage'] }}</td>
                          <td>{{ $row['actual_percentage'] }}</td>
                          <td>{{ $row['reason'] }}</td>
                          <td>{{ $row['status'] == 1 ? 'Approved' : 'Pending' }}</td>

                       </tr>
                      <?php $i++; } ?>
                    <?php } ?>
                 </table>
                   </div> 
                </td>
              </tr>
           </tbody>
        </table>
    </div>