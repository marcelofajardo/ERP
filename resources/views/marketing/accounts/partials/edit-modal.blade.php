<div id="accountEditModal{{$account->id}}" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="{{ route('accounts.edit') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h4 class="modal-title">Edit @if($type) {{ $type }} @endif Account</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{$account->id}}">
                        <div class="form-group">
                            <strong>Username:</strong>
                            <input type="text" name="username" class="form-control" value="{{ $account->last_name }}" required>

                            @if ($errors->has('username'))
                                <div class="alert alert-danger">{{$errors->first('username')}}</div>
                            @endif
                        </div>
                            <input type="hidden" name="id" value="{{ $account->id }}"/>
                        <div class="form-group">
                            <strong>Password:</strong>
                            <input type="text" name="password" class="form-control" value="{{ $account->password }}" required>

                            @if ($errors->has('password'))
                                <div class="alert alert-danger" >{{$errors->first('password')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Number:</strong>
                            <input type="text" name="number" class="form-control" value="{{ $account->number }}">

                            @if ($errors->has('number'))
                                <div class="alert alert-danger" >{{$errors->first('number')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            <input type="email" name="email" class="form-control" value="{{ $account->email }}">

                            @if ($errors->has('email'))
                                <div class="alert alert-danger" >{{$errors->first('email')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Provider:</strong>
                            <input type="text" name="provider" class="form-control" value="{{ $account->provider }}">

                            @if ($errors->has('provider'))
                                <div class="alert alert-danger" >{{$errors->first('provider')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Customer Support:</strong>
                            <select class="form-control" name="customer_support">
                                <option value="1" @if($account->is_customer_support == 1) selected @endif>Yes</option>
                                <option value="0" @if($account->is_customer_support == 0) selected @endif>No</option>
                            </select>
                            @if ($errors->has('is_customer_support'))
                                <div class="alert alert-danger" >{{$errors->first('is_customer_support')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Website:</strong>
                            <select class="form-control" name="website">
                                <option value="0">Select Website</option>
                                @foreach($websites as $website)
                                <option value="{{ $website->id }}" @if($website->id == $account->store_website_id) selected @endif>{{ $website->title }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('website'))
                            <div class="alert alert-danger">{{$errors->first('website')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>Instance Id:</strong>
                            <input type="text" name="instance_id" class="form-control" value="{{ $account->instance_id }}">
                            @if ($errors->has('instance_id'))
                                <div class="alert alert-danger" >{{$errors->first('instance_id')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Token:</strong>
                            <input type="text" name="token" class="form-control" value="{{ $account->token }}">
                            @if ($errors->has('token'))
                                <div class="alert alert-danger" >{{$errors->first('token')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Proxy:</strong>
                            <input type="text" name="proxy" class="form-control" value="{{ $account->proxy }}">
                            @if ($errors->has('proxy'))
                                <div class="alert alert-danger" >{{$errors->first('proxy')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Is Default ?:</strong>
                            <select class="form-control" name="is_default">
                                <option value="0" @if($account->is_default == 0) selected @endif>No</option>
                                <option value="1" @if($account->is_default == 1) selected @endif>Yes</option>
                            </select>
                            @if ($errors->has('is_default'))
                                <div class="alert alert-danger" >{{$errors->first('is_default')}}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <strong>Frequency:</strong>
                            <input type="text" name="frequency" class="form-control" value="{{ $account->frequency }}">
                            @if ($errors->has('frequency'))
                                <div class="alert alert-danger" >{{$errors->first('frequency')}}</div>
                            @endif
                        </div>

                         <div class="form-group">
                            <strong>Start Time:</strong>
                            <select class="form-control" name="send_start">
                                <option value="0" @if($account->send_start == 0) selected @endif>0</option>
                                <option value="1" @if($account->send_start == 1) selected @endif>1</option>
                                <option value="2" @if($account->send_start == 2) selected @endif>2</option>
                                <option value="3" @if($account->send_start == 3) selected @endif>3</option>
                                <option value="4" @if($account->send_start == 4) selected @endif>4</option>
                                <option value="5" @if($account->send_start == 5) selected @endif>5</option>
                                <option value="6" @if($account->send_start == 6) selected @endif>6</option>
                                <option value="7" @if($account->send_start == 7) selected @endif>7</option>
                                <option value="8" @if($account->send_start == 8) selected @endif>8</option>
                                <option value="9" @if($account->send_start == 9) selected @endif>9</option>
                                <option value="10" @if($account->send_start == 10) selected @endif>10</option>
                                <option value="11" @if($account->send_start == 11) selected @endif>11</option>
                                <option value="12" @if($account->send_start == 12) selected @endif>12</option>
                                <option value="13" @if($account->send_start == 13) selected @endif>13</option>
                                <option value="14" @if($account->send_start == 14) selected @endif>14</option>
                                <option value="15" @if($account->send_start == 15) selected @endif>15</option>
                                <option value="16" @if($account->send_start == 16) selected @endif>16</option>
                                <option value="17" @if($account->send_start == 17) selected @endif>17</option>
                                <option value="18" @if($account->send_start == 18) selected @endif>18</option>
                                <option value="19" @if($account->send_start == 19) selected @endif>19</option>
                                <option value="20" @if($account->send_start == 20) selected @endif>20</option>
                                <option value="21" @if($account->send_start == 21) selected @endif>21</option>
                                <option value="22" @if($account->send_start == 22) selected @endif>22</option>
                                <option value="23" @if($account->send_start == 23) selected @endif>23</option>
                            </select>

                            @if ($errors->has('send_at'))
                            <div class="alert alert-danger">{{$errors->first('send_at')}}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <strong>End Time:</strong>
                             <select class="form-control" name="send_end">
                                <option value="0" @if($account->send_end == 0) selected @endif>0</option>
                                <option value="1" @if($account->send_end == 1) selected @endif>1</option>
                                <option value="2" @if($account->send_end == 2) selected @endif>2</option>
                                <option value="3" @if($account->send_end == 3) selected @endif>3</option>
                                <option value="4" @if($account->send_end == 4) selected @endif>4</option>
                                <option value="5" @if($account->send_end == 5) selected @endif>5</option>
                                <option value="6" @if($account->send_end == 6) selected @endif>6</option>
                                <option value="7" @if($account->send_end == 7) selected @endif>7</option>
                                <option value="8" @if($account->send_end == 8) selected @endif>8</option>
                                <option value="9" @if($account->send_end == 9) selected @endif>9</option>
                                <option value="10" @if($account->send_end == 10) selected @endif>10</option>
                                <option value="11" @if($account->send_end == 11) selected @endif>11</option>
                                <option value="12" @if($account->send_end == 12) selected @endif>12</option>
                                <option value="13" @if($account->send_end == 13) selected @endif>13</option>
                                <option value="14" @if($account->send_end == 14) selected @endif>14</option>
                                <option value="15" @if($account->send_end == 15) selected @endif>15</option>
                                <option value="16" @if($account->send_end == 16) selected @endif>16</option>
                                <option value="17" @if($account->send_end == 17) selected @endif>17</option>
                                <option value="18" @if($account->send_end == 18) selected @endif>18</option>
                                <option value="19" @if($account->send_end == 19) selected @endif>19</option>
                                <option value="20" @if($account->send_end == 20) selected @endif>20</option>
                                <option value="21" @if($account->send_end == 21) selected @endif>21</option>
                                <option value="22" @if($account->send_end == 22) selected @endif>22</option>
                                <option value="23" @if($account->send_end == 23) selected @endif>23</option>
                            </select>

                            @if ($errors->has('send_end'))
                            <div class="alert alert-danger">{{$errors->first('send_end')}}</div>
                            @endif
                        </div>
                        <input type="hidden" name="platform" value="{{ $account->platform }}">
                        <div class="form-group">
                            <strong>Status:</strong>
                             <select class="form-control" name="status">
                                <option>Select Status</option>
                                <option value="1" @if($account->status == 1) selected @endif>Active</option>
                                <option value="2" @if($account->status == 2) selected @endif>Blocked</option>
                                <option value="3" @if($account->status == 0) selected @endif>Inactive</option>
                             </select>
                            @if ($errors->has('status'))
                            <div class="alert alert-danger">{{$errors->first('status')}}</div>
                            @endif
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-secondary">Update</button>
                    </div>
                </form>
            </div>

        </div>
    </div>