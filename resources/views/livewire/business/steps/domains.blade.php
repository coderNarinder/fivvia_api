 <div>
       <form wire:submit.prevent="saveDomains">
                        <div class="step-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="step-header-content">
                                        <h3 class="step-title">Select your Domain</h3>
                                        <p>Choose a domain to publish your site on</p>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-right">
                                    <div class="step-buttons">
                                       <a href="{{route('business.logistics')}}" class="btn btn-grey">back</a>
                                        <button class="btn btn-blue">add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
         @if (session()->has('error'))
            <div class="alert alert-warning">
                {{ session('error') }}
            </div>
        @endif
        
    </div>
                        <div class="step-selection-content">
                        	<div class="row">
							    <div class="col-md-12">
							        <div class="radio-box">
							            <div class="form-group group-inline">
							                <label class="radio-custom">Use a free Fivvia.com domain
							                    <input type="radio" name="domain_type" 
							                    wire:model="domain_type"
							                    key:value="1" 
							                    value="1"
							                    wire:change="changeType(1)"
							                    {{$domain_type == 1 ? 'checked' : ''}}>
							                    <span class="checkmark"></span>
							                </label>
							            </div>
							            <div class="form-group url-group">
							                <ul class="circle-list">
							                    <li class="grey-circle"></li>
							                    <li class="grey-circle"></li>
							                    <li class="grey-circle"></li>
							                </ul>
							                <input type="url" 
							                class="form-control" 
							                wire:model="subdomain" 
							                name="subdomain" 
							                value="{{$subdomain}}" 
							                placeholder="http://kfc.fivvia.com"
							                {{$domain_type == 0 ? 'disabled' : ''}}>
							                @error('subdomain') <span class="error">{{ $message }}</span> @enderror
							            </div>
							        </div>
							    </div>
							    <div class="col-md-12">
							        <div class="radio-box">
							            <div class="form-group group-inline">
							                <label class="radio-custom">Link to existing domain
							                    <input type="radio" 
							                    name="domain_type" 
							                    wire:model="domain_type"
							                    key:value="0" 
							                    value="0" 
							                    wire:change="changeType(0)"
							                    {{$domain_type == 0 ? 'checked' : 'required'}}>
							                    <span class="checkmark"></span>
							                </label>
							            </div>
							            <div class="form-group url-group">
							                <ul class="circle-list">
							                    <li class="grey-circle"></li>
							                    <li class="grey-circle"></li>
							                    <li class="grey-circle"></li>
							                </ul>
							                <input type="url"  
							                wire:model="domain" 
							                name="domain" 
							                class="form-control" 
							                placeholder="http://your-buniness-name.com"
							                value="{{$domain}}" 
							                {{$domain_type == 1 ? 'disabled' : 'required'}}>
							                 @error('domain') <span class="error">{{ $message }}</span> @enderror
							            </div>
							        </div>
							    </div>
					 </div>
                    
</div>
</form>
</div>