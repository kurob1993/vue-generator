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
                        <div id="accordion">
                        @foreach ($columns as $key => $item)                        
                        <div class="col-xs-6 form-{{$key}}">
                           <div class="card mb-2">
                              <div class="card-header" id="heading{{$key}}">
                                 <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne{{$key}}" aria-expanded="true" aria-controls="collapseOne">
                                    {{ $item['title'] }}
                                 </button>
                                 <button type="button" class="btn btn-sm btn-danger" onclick="hapus('form-{{$key}}')">Hapus {{ $item['column'] }} 👇</button>
                              </div>
                          
                              <div id="collapseOne{{$key}}" class="collapse" aria-labelledby="heading{{$key}}" data-parent="#accordion">
                                <div class="card-body">
                                 <table class="table table-bordered table-hover my-2">
                                    <thead>
                                       <tr class="bg-primary text-white">
                                          <th width="10%">Column</th>
                                          <th width="5%">:</th>
                                          <th>
                                             <input type="text" class="form-control" name="column[]" readonly value="{{ $item['column'] }}">
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
                                             <input type="text" class="form-control" name="title[]" value="{{ $item['title'] }}">
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
                                          <td width="10%">Relasi</td>
                                          <td width="5%">:</td>
                                          <td>
                                             <input type="text" class="form-control" name="relasi[]" value="{{ $item['relasi'] }}">
                                             <label for="" class="text-danger">* Unutk Component Select, 
                                                Jika Akan Menggunakan Data Static isi dengan data JSON 
                                                contoh: <br>
                                                [{ "value": "1", "text": "Nama Mobil" }, { "value": "2", "text": "Nama Hewan" }] </label>
                                          </td>
                                       </tr>
                                       <tr>
                                          <td width="10%">Required</td>
                                          <td width="5%">:</td>
                                          <td>
                                             <input type="text" class="form-control" name="required[]" value="{{ $item['required'] }}">
                                          </td>
                                       </tr>
                                       <tr>
                                          <td width="10%">Max Char</td>
                                          <td width="5%">:</td>
                                          <td>
                                             <input type="text" class="form-control" name="max[]" value="{{ $item['max'] }}">
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                                </div>
                              </div>
                           </div>
                        </div>
                        @endforeach
                        </div>
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

     function hapus(params) {
      var r = confirm("Yakin Akan Menghapus Kolom ini?");
      if (r == true) {
         $('.'+params).fadeOut(1000);
         setTimeout(() => {
            $('.'+params).remove();
         }, 1500);
      } 
     }
    </script>
@endpush