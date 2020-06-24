@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                  <form action="{{ route('generate') }}" method="POST">
                     @csrf
                     <table class="table table-bordered table-striped table-hover">
                        <tr>
                           <td>Target Lokasi Folder</td>
                           <td>:</td>
                           <td>
                              <input type="text" name="folder" class="form-control" value="{{ $folder }}" readonly>
                           </td>
                        </tr>
                        <tr>
                           <td>Table</td>
                           <td>:</td>
                           <td>
                              <input type="text" name="table" class="form-control" value="{{ $table }}" readonly>
                           </td>
                        </tr>
                        <tr>
                           <td>Title</td>
                           <td>:</td>
                           <td>
                              <input type="text" name="titleHeader" class="form-control" value="{{ $title }}" readonly>
                           </td>
                        </tr>
                        <tr>
                           <td>Endpont Api</td>
                           <td>:</td>
                           <td>
                              <input type="text" name="endpoint" class="form-control" value="{{ $endpoint }}" readonly>
                           </td>
                        </tr>
                     </table>
                     <div class="col-xs-12">
                        @foreach ($columns as $key => $item)
                        <div class="col-xs-6">
                           <table class="table table-bordered table-hover my-2">
                              <thead>
                                 <tr class="bg-primary text-white">
                                    <th width="10%">Column</th>
                                    <th width="5%">:</th>
                                    <th>
                                       <input type="text" class="form-control" name="column[]" readonly
                                          placeholder="column" value="{{ $item['column'] }}">
                                    </th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td width="10%">PK</td>
                                    <td width="5%">:</td>
                                    <td>
                                       <input type="text" class="form-control" name="pk[]" value="{{ $item['pk'] }}">
                                    </td>
                                 </tr>
                                 <tr>
                                    <td width="10%">title</td>
                                    <td width="5%">:</td>
                                    <td>
                                       <input type="text" class="form-control" name="title[]"
                                          placeholder="Title" value="{{ $item['column'] }}">
                                    </td>
                                 </tr>
                                 <tr>
                                    <td width="10%">Component</td>
                                    <td width="5%">:</td>
                                    <td>
                                       <select class="form-control" name="type[]" onchange="pilih(this.value)">
                                          <option value="text" {{ $item['type'] == 'text' ? 'selected' : ''}}>text</option>
                                          <option value="textarea" {{ $item['type'] == 'textarea' ? 'selected' : ''}}>textarea</option>
                                          <option value="number" {{ $item['type'] == 'number' ? 'selected' : ''}}>number</option>
                                          <option value="textNumber" {{ $item['type'] == 'textNumber' ? 'selected' : ''}}>textNumber</option>
                                          <option value="switch" {{ $item['type'] == 'switch' ? 'selected' : ''}}>switch</option>
                                          <option value="date" {{ $item['type'] == 'date' ? 'selected' : ''}}>date</option>
                                          <option value="select" {{ $item['type'] == 'select' ? 'selected' : ''}}>select</option>
                                       </select>
                                    </td>
                                 </tr>
                                 <tr>
                                    <td width="10%">Required</td>
                                    <td width="5%">:</td>
                                    <td>
                                       <input type="text" class="form-control" name="required[]" 
                                          placeholder="Title" value="{{ $item['required'] }}">
                                    </td>
                                 </tr>
                                 <tr>
                                    <td width="10%">Max Char</td>
                                    <td width="5%">:</td>
                                    <td>
                                       <input type="text" class="form-control" name="max[]" 
                                          placeholder="Max Character" value="{{ $item['max'] }}">
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                        @endforeach
                     </div>
                     <button type="submit" class="btn btn-primary">Generate</button>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
     function pilih(params) {
        console.log(params);
        
     }
    </script>
@endpush