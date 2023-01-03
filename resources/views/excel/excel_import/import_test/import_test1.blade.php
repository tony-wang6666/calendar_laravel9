@extends('layouts.layout_1')
@section('content')
<div class=" flex items-center justify-center">
    <form id="form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mt-4 w-2/5" method='POST' action="{{url('member/EU')}}" enctype="multipart/form-data">
        <h1 class="block text-gray-700 font-bold mb-2 text-xl text-center ">測試匯入資料</h1>
        <br>
        @csrf
        <!-- <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Nombre
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="name" id="name" type="text" placeholder="Ingresa tu nombre" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Número de Celular
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="tel" id="tel" type="tel" placeholder="Ingresa tu Número de Celular" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                Correo
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="email" id="email" type="email" placeholder="Ingresa tu correo" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="Date">
                Fecha
            </label>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="date" id="date" type="date" placeholder="Ingresa tu Fecha de Nacimiento" required>
        </div>

        <div class="mb-4">

            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                ¿Pregunta 01?
            </label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="message1" id="message1" type="text" placeholder="Escríbe tu respuesta Aquí..."required></textarea>
        </div> -->

        <div class="mb-4">

            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                EXCEL 匯入  (請選擇上傳的EXCEL)
            </label>
            
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                name="import_file" type="file" >

        </div>

        
        <div class="flex items-center justify-between">
            <button id="submit"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                type="submit">
                上傳
            </button>
        </div>

        <div class="mb-4">


    </form>
        
</div>
@endsection