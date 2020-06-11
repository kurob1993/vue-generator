@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Files</div>
            <div class="card-body">
               <form action="{{route('generator.files')}}" method="GET">
                  <div class="row mb-2">
                     <div class="col-md-11">
                        <select name="cari" id="cari" class="form-control mb-3 js-example-basic-single">
                           <option value="">ALL</option>
                           @foreach ($group as $key => $items)
                           <option value="{{$key}}">{{$key}}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-1">
                        <button type="submit" class="btn btn-primary ml-2">Cari</button>
                     </div>
                  </div>
               </form>
               <div class="table-responsive">
                  <table class="table table-striped table-hover table-bordered">
                     @foreach ($data as $key => $items)
                        <tr>
                           <td>{{$key}}</td>
                        </tr>
                        @foreach ($items as $item)
                           <tr>
                              <td>
                                 @php($label = explode('/', $item))
                                 <a href="{{ url($item) }}" download="" class="ml-5"> {{ $label[3] }} - {{ $label[4] }} </a>
                              </td>
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

@push('script')
<script>
   $(document).ready(function() {
      $('.js-example-basic-single').select2({
         theme: "bootstrap"
      });
   });
</script>
@endpush