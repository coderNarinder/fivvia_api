@extends(getTemplateLayoutPath('layout'))
 @section('css')
 @livewireStyles 
@endsection
@section('content')
 
 @include('templates.includes.banners')
 

 <livewire:template.home.events/>


 <livewire:template.home.vendor-list/>

 <livewire:template.home.featured-products/>
 <livewire:template.home.on-sale-products/>
 <livewire:template.home.new-products/>
 

@endsection

@section('js')
@livewireScripts
@endsection