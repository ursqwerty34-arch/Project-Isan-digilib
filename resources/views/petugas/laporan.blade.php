@extends('layouts.petugas')
@section('title', 'Cetak Laporan')
@section('content')
@include('laporan._form', ['route' => 'petugas.laporan', 'cetakRoute' => 'petugas.laporan.cetak'])
@endsection
