  @foreach ($documents as $document)
                <tr>
                    <td class="p-2">{{ $document->updated_at->format('d-m-Y') }}</td>
                    <td class="p-2">{{ $document->user->name }}</td>
                    <td class="p-2">{{ $document->user->agent_role  }}</td>
                    {{-- <td class="p-2">{{ $document->name}} --}}
                    
                        @if (strlen($document->name) > 40)
                        <td  style="word-break: break-word;" data-modal-title="Document type" data-log_message="{{ $document->name }}" class="log-message-popup p-2">{{ substr($document->name,0,40) }}...</td>    
                    @else
                        <td class="p-2" style="word-break: break-word;">{{ $document->name }}</td>
                    @endif      
                 

                    @if(isset($document->documentCategory->name))
                   
                            @if (strlen($document->documentCategory->name) > 40)
                            <td  style="word-break: break-word;" data-modal-title="Category" data-log_message="{{ $document->documentCategory->name }}" class="log-message-popup p-2">{{ substr($document->documentCategory->name,0,40) }}...</td>    
                             @else
                            <td class="p-2" style="word-break: break-word;">{{ $document->documentCategory->name }}</td>
                             @endif  

                    @else
                    <td class="p-2">
                    </td>
                    @endif

                        @if (strlen($document->filename) > 40)
                        <td  style="word-break: break-word;" data-modal-title="Filename" data-log_message="{{ $document->filename }}" class="log-message-popup p-2">{{ substr($document->filename,0,40) }}...</td>    
                    @else
                        <td class="p-2" style="word-break: break-word;">{{ $document->filename }}</td>
                    @endif     

                      <td class="p-2">
                        <a href="{{ route('document.download', $document->id) }}"  class="btn btn-image text-dark btn-xs p-0"><i class="fa fa-download"></i>
                        </a>
                        <button type="button" class="btn btn-image sendWhatsapp btn-xs p-0" data-id="{{ $document->id }}"><img src="/images/send.png" /></button>
                        <button type="button" class="btn btn-image sendEmail btn-xs p-0" data-id="{{ $document->id }}"><img src="/images/customer-email.png" /></button>

                        {!! Form::open(['method' => 'DELETE','route' => ['document.destroy', $document->id],'style'=>'display:inline']) !!}

                        <button type="submit" class="btn btn-image btn-xs p-0"><img src="/images/delete.png" /></button>
                        <button type="button" class="btn btn-image make-remark d-inline btn-xs p-0" data-toggle="modal" data-target="#makeRemarkModal" data-id="{{ $document->id }}"><img src="/images/remark.png" /></button>

                        {!! Form::close() !!}
                        <button type="button" class="btn btn-image uploadDocument btn-xs p-0" data-id="{{ $document->id }}"><img src="/images/upload.png" /></button>

                     <p class="text-dark btn font-weight-bold btn-xs p-0">   V: {{ $document->version }}</p>
                    </td>
                </tr>
            @endforeach