<div>
 <form wire:submit.prevent="checkLogin">
                                   @csrf
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

                                <div class="form-group mb-3">
                                    <label for="name">Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" placeholder="Enter your name" wire:model="name" />
                                    @error('name')
                                        <span class="invalid-feedback" role="alert" style="display:block;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="emailaddress">Phone number</label>
                                    <input class="form-control @error('phone_number') is-invalid @enderror" type="text" id="emailaddress" placeholder="Enter your phone number" wire:model="phone_number" />
                                    @error('phone_number')
                                        <span class="invalid-feedback" role="alert" style="display:block;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                 <div class="form-group mb-3">
                                    <label for="emailaddress">Email address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" id="emailaddress" placeholder="Enter your email" wire:model="email" />
                                    @error('email')
                                        <span class="invalid-feedback" role="alert" style="display:block;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <div class="input-group input-group-merge @error('password') is-invalid @enderror">
                                        <input class="form-control @error('password') is-invalid @enderror" 
                                        wire:model="password" type="password" placeholder="Enter your password" />
                                        <div class="input-group-append" data-password="false">
                                            <div class="input-group-text">
                                                <span class="password-eye"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-sm-left">
                                      @error('password')
                                        <span class="invalid-feedback" role="alert" style="display:block;">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                        <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-center"> 
                                     <button type="submit" class="btn btn-primary btn-block"
                                        wire:loading.attr="disabled"
                                        wire:target="checkLogin">
                                        <span wire:loading.remove wire:target="checkLogin">
                                        Register
                                        </span>
                                        <span wire:loading wire:target="checkLogin">
                                        Processing...
                                        </span>
                                        </button> 
                                </div>
                            </form>
</div>