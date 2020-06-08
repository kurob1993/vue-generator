@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                  <form action="{{ route('generate') }}" method="POST">
                     <button type="submit" class="btn btn-primary mb-2">Generate</button>
                     @csrf
                     <table class="table table-bordered table-striped table-hover">
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
                              <input type="text" name="titleHeader" class="form-control" value="{{ $endpoint }}" readonly>
                           </td>
                        </tr>
                     </table>
                     @foreach ($columns as $key => $item)
                     <table class="table table-bordered table-hover my-5">
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
                                 <select class="form-control" name="type[]">
                                    <option value="text" {{ $item['type'] == 'text' ? 'selected' : ''}}>text</option>
                                    <option value="number" {{ $item['type'] == 'number' ? 'selected' : ''}}>number</option>
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
                        </tbody>
                     </table>
                     @endforeach
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection