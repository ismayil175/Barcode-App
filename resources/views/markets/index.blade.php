@extends('markets.layout')
     
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2></h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('markets.create') }}"> Create New Market</a>
            </div>
        </div>
    </div>
    
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
     
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Details</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($markets as $market)
        <tr>
            <td>{{ $market->id }}</td>
            <td><img src="/image/{{ $market->image }}" width="100px"></td>
            <td>{{ $market->name }}</td>
            <td>{{ $market->detail }}</td>
            
            <td>
                <form action="{{ route('markets.destroy',$market->id) }}" method="POST">
     
                    <a class="btn btn-info" href="{{ route('markets.show',$market->id) }}">Show</a>
                    <a class="btn btn-success" href="{{ route('markets.showProducts',$market->id) }}">Show Products</a>
      
                    <a class="btn btn-primary" href="{{ route('markets.edit',$market->id) }}">Edit</a>
     
                    @csrf
                    @method('DELETE')
        
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    
    {!! $markets->links() !!}
        
@endsection