
 @if(isset($scrappers))
                        @foreach($scrappers as $scrapper)
                            <?php
                                $start_time = new DateTime(@$scrapper->last_started_at);
                                $end_time = new DateTime(@$scrapper->last_completed_at);
                                $interval = @$start_time->diff($end_time);
                                $time = $scrapper->scraper_start_time;
                                if($time < 12){
                                    $timeSt = 'AM';
                                }else{
                                    $timeSt = 'PM';
                                }
                            ?>
                            <tr>
                                <td>{{ @$scrapper->server_id }}</td>
                                <td>{{ @$time }} {{ @$timeSt }}</td>
                                <td>{{ @$scrapper->scraper_name }}</td>
                                <td>{{ @$scrapper->last_started_at }}</td>
                                <td>{{ @$scrapper->last_completed_at }}</td>
                                <td>{{ @$scrapper->updated_at }}</td>
                                <td>{{ @$interval->format('%Y year %m month %d day %H hours %i minutes %s seconds') }}</td>
                                 
                                <td>
                                    @if($scrapper->getScrapHistory->count() != 0)
                                        <a class="btn d-inline btn-image openHistory" data-attr="{{ @$scrapper->id }}" id="{{ @$scrapper->id }}">
                                            <img src="/images/view.png" />
                                        </a>
                                    @endif
                                </td>
                                
                            </tr>

                           

                            
                            <tr class="open_request_{{ @$scrapper->id }}" style="display: none;">
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Duration</th>
                                <th>Sent Request</th>
                                <th>Failed Request</th>
                            </tr>
                                    @if($scrapper->getScrapHistory->count() != 0)
                                        @foreach($scrapper->getScrapHistory as $scrapHistory)
                                            <tr class="open_request_{{ @$scrapper->id }}" style="display: none;">
                                                <?php
                                                    $start_time = new DateTime(@$scrapHistory->start_time);
                                                    $end_time = new DateTime(@$scrapHistory->end_time);
                                                    $interval_history = @$start_time->diff($end_time);
                                                ?>    
                                                <td>
                                                    {{ @$scrapHistory->start_time }}
                                                </td>
                                                <td>
                                                    {{ @$scrapHistory->end_time }}
                                                </td>
                                                <td>
                                                    {{ @$scrapHistory->end_time }}
                                                </td>
                                                <td>
                                                    {{ @$scrapHistory->request_sent }}
                                                </td>
                                                <td>
                                                    {{ @$scrapHistory->request_failed }}
                                                </td>
                                            </tr>
                                        @endforeach    
                                    @endif

                             </td>
                               
                        @endforeach
                    @endif
                   