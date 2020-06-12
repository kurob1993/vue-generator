@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif


                    <form action="{{ route('columnsTable') }}" method="POST">
                        @csrf
                        <label for="table">Pilih Table</label>
                        <select name="table" id="table" class="form-control my-2 js-example-basic-single" required>
                            @foreach ($tables as $table)
                                @foreach ($table as $key => $value)
                                <option value="{{$value}}">{{$value}}</option>
                                @endforeach
                            @endforeach
                        </select>

                        <label for="title">Title Halaman</label>
                        <input type="text" name="title" id="title" class="form-control my-2" placeholder="Data Master Karyawan" required>

                        <label for="endpoint">Endpoint Api</label>
                        <input type="text" name="endpoint" id="endpoint" class="form-control my-2" placeholder="api/Dokter" required>

                        <button type="submit" class="btn btn-primary my-2">Generate</button>
                    </form>
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