@extends('templates.layouts.layout')
@section('mainContent')
 @include(getTemplateLayoutPath('includes').'header')
 @yield('content')
  @include(getTemplateLayoutPath('includes').'footer')

  <div class="modal fade edit_address" id="edit-address" tabindex="-1" aria-labelledby="edit-addressLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div id="address-map-container">
                    <div id="address-map"></div>
                </div>
                <div class="delivery_address p-2 mb-2 position-relative">
                    <button type="button" class="close edit-close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <div class="form-group address-input-group">
                        <label class="delivery-head mb-2">SELECT YOUR LOCATION</label>
                        <div class="address-input-field d-flex align-items-center justify-content-between"> <i
                                class="fa fa-map-marker" aria-hidden="true"></i> <input
                                class="form-control border-0 map-input" type="text" name="address-input"
                                id="address-input" value="Chandigarh, Punjab, India"> <input type="hidden"
                                name="address_latitude" id="address-latitude" value="31" />
                            <input type="hidden" name="address_longitude" id="address-longitude"
                                value="77" /> <input type="hidden" name="address_place_id"
                                id="address-place-id" value="" /> </div>
                    </div>
                    <div class="text-center"> <button type="button"
                            class="btn btn-solid ml-auto confirm_address_btn w-100">Confirm And Proceed</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection