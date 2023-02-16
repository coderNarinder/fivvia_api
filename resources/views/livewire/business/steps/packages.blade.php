<div wireLinit="loadinit">
 <div class="step-header">
        <div class="row">
		    <div class="col-md-8">
		        <div class="step-header-content offer-header">
		            <h3 class="step-title">Start your Social Comerce Platform Now</h3>
		            <div class="price-switch-text">
		                <p>Select your preferred plan.</p>
                    
		                <div class="switch">
		                    <input type="checkbox" 
                            wire:change="changeType()" 
                            id="plan" 
                            wire:model="duration_type" value="1" 
                            {{$duration_type == 1 ? 'checked' :''}}
                            />
		                    <label for="plan"><i></i></label>
		                    <div class="plan-text">
		                        <span class="monthly">monthly</span>
		                        <span class="yearly">yearly </span>
		                    </div>
		                </div>
		            </div>

                
		            <div class="form-group" style="opacity: 0">
		               
                    <div class="price-switch-text">
                    <p>Start your free 1 month Trial</p>
                   
                     <div class="switch">
                       <input type="checkbox" 
                            wire:change="changeTrialStatus()" 
                            wire:model="trial_status" value="1" 
                            id="trials" checked="true" 
                            />
                        <label for="trials"><i></i></label>
                        <div class="plan-text">
                            <span class="yearly">On </span>
                            <span class="monthly">Off</span>
                        </div>
                    </div>
                </div>
		            </div>
		            <div class="offer-image">
		                <img src="/fivvia_business/images/month_free.png" alt="" srcset="">
		            </div>
		        </div>                                
		     </div>
			<div class="col-md-4 text-md-right">
			    <div class="step-buttons">
			        <a href="{{route('business.business_details')}}" class="btn btn-grey">back</a>
			        <!-- <button class="btn btn-blue">add</button> -->
			    </div>
			</div>
		 </div>
  </div>

               <div class="step-selection-content">
                   <div class="row">
                        @foreach($packages as $p)
                        <?php $package_member = !empty($members[$p->id]) ? ($p->default_member + $members[$p->id]) : $p->default_member; ?>
                        <div class="col-md-4 col-12">
                            <div class="selection-box">
                                <div class="selection-content">
                                    <h3 class="plan-name">{{$p->title}}</h3>
                                    <p class="ideal-for">
                                       {{$p->tagline}}
                                    </p>
                                    <div class="plan-price">
                                        <h2><strong>$
                                          {{$p->price + (!empty($members_rates[$p->id]) ? $members_rates[$p->id] : 0)}}

                                        </strong><span>/{{$p->duration}}</span></h2>
                                        <p>save 20% annually</p>
                                    </div>
                                    <div class="plan-detail">
                                    	<p>{{$p->description}}</p>
                                       <!--  <p>This price is for <span>01 Seat</span>, Get <span>10% OFF</span> on each additional Seat.</p> -->
                                    </div>
                                    <div class="need-for">
                                        <p>How many seats you want for your business?</p>
                                        <!-- <button class="btn total-seats">01</button> -->
                                        <button class="btn btn-orange" {{$package_member <= $p->default_member ? 'disabled' : '' }}
                                        wire:click="increaseMemberData({{$p->id}},'minus')"><span>-</span></button>
                                        <span>{{ $package_member }}</span>
                                        <button class="btn btn-orange" 
                                        wire:click="increaseMemberData({{$p->id}},'plus')"><span>+</span></button>
                                    </div>
                                      <a class="btn btn-grey btn-block" wire:click=increaseMemberData({{$p->id}},'choose')">
                                        <span>Choose</span>
                                      </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                  <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive pricing-table">
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr>
                                          <th>features</th>
                                          <th>basic</th>
                                          <th>standard</th>
                                          <th>Advance</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                @foreach(getPackageFeaturesAll($durations) as $key => $feature)
                                    <tr> 
                                      
                                      <td>
                                        {{$feature['feature']}}
                                        <p>Send post for your business followers.</p>
                                      </td>
                                      <td>
                                        {!!$feature['basic'] == 'yes' ? "<img src='/fivvia_business/images/tick_subscription.png'>" : ''!!}
                                      </td>
                                      <td> 
                                         {!!$feature['standard'] == 'yes' ? "<img src='/fivvia_business/images/tick_subscription.png'>" : ''!!}
                                      </td>
                                      <td> 
                                          {!!$feature['advance'] == 'yes' ? "<img src='/fivvia_business/images/tick_subscription.png'>" : ''!!}
                                      </td>
                                   </tr>
                                @endforeach
                                       
                                        
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
</div>
