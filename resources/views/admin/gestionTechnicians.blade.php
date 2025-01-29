@extends('layouts.admin')

@section('content')
     
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
               
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($technicians as $technician)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $technician->name }}</td>
                    <td>{{ $technician->email }}</td>
                   
                    <td>{{ $technician->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm">Modifier</a>
                        <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
