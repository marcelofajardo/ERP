@foreach($data as $key=>$datum)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td><a href="{{ $datum->page_url }}" target="_blank">Visit</a></td>
                        <td>{{ $datum->sender }}</td>
                        <td>{{ $datum->received_at }}</td>
                        <td>
                            @if(isset($datum->images[0]))
                            <a href="{{ $datum->images }}">
                                    <img src="{{ $datum->images }}" alt="" style="width: 100px;height: 100px;">
                                </a>
                            @endif
                            
                        </td>
                        <td>
                            {{-- @php 
                                $count = 0;
                                @endphp
                                 @foreach($datum->tags as $tag)
                                    @if($count == 6)
                                        @break
                                    @endif
                                    <li>{{ $tag }}</li>
                                    @php
                                    $count++
                                    @endphp
                                @endforeach --}}
                            
                        </td>
                    </tr>
                @endforeach