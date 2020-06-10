@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Files</div>

            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-striped table-hover table-bordered">
                     @foreach ($data as $key => $items)
                        <tr>
                           <td>{{$key}}</td>
                        </tr>
                        @foreach ($items as $item)
                           <tr>
                              <td><a href="{{ url($item) }}" download="" class="ml-5">{{ $item }}</a></td>
                           </tr>
                        @endforeach
                     @endforeach
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection