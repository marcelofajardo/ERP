@if (Auth::check())
    <div class="container-messanger">
        <div class="msg-wgt-header">

            <a href="#">Messenger - {{ Auth::user()->name }}</a>
            <div class="chat-toggle"><i class="fa fa-window-minimize" style="font-size:14px"></i></div>
        </div>
        <div class="chat-container">
        	<div class="col-sm-7" style="padding: 0px;">
           		 <div class="msg-wgt-body">
            	</div>
            	<textarea id="chatMsg" placeholder="Type your message. Press shift + Enter to send"></textarea>
                {{ csrf_field() }}
                <?php
					$users = ( new App\Http\Controllers\ActivityConroller() )->getUserArray();
					?>		
                   
            </div>
            <div class="col-sm-5">	
           		 <div class="msg-wgt-footer">					
					<ul id="sendid">
						<?php foreach ($users as $key => $user) {
                            if(Auth::user()->name != $user) :
                         ?>

						 <li data-id="{{$key}}">{{$user}} <span class="new"></span> </li>
						 <?php 
                        endif;
                        } ?>
					</ul>
				</div>	

            </div>
           </div> 
        </div>
    </div>
    </div>
@endif