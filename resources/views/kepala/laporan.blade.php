@extends('layouts.kepala')
@section('title', 'Cetak Laporan')
@section('content')
@include('laporan._form', ['route' => 'kepala.laporan', 'cetakRoute' => 'kepala.laporan.cetak'])
@endsection
