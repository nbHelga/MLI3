@extends('layouts.layout')

@section('title', 'Home - MLI Store')

@section('content')
    <x-header></x-header>

    <main>
        <div class="bg-white py-8 sm:py-12 lg:py-16">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:text-center">
                    <p class="mt-2 text-xl font-semibold tracking-tight text-gray-900 sm:text-2xl lg:text-3xl">(ORIGINAL INDONESIAN PRODUCT)</p>
                </div>
            </div>
        </div>

        {{-- konten best seller --}}
        <div class="bg-white mt-[-80px]">
            <div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Best Seller</h2>

                <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    <div class="group relative">
                        <img src="{{ asset('ikanbanyar.png') }}" alt="Ikan Banyar" class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80">
                        <div class="mt-4 flex justify-between items-end">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="#">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Ikan Banyar
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">(Rastrelliger Kanagurta)</p>
                                <p class="mt-1 text-sm text-gray-800">Rp.10.000 - 12.000</p>
                            </div>

                            {{-- button keranjang --}}
                            <div>
                                <button type="button" class="relative rounded-full bg-gray-800 p-1 text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                    <span class="absolute -inset-1.5"></span>
                                    <span class="sr-only">View cart</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 25 28" stroke-width="1.5" stroke="white" class="h-6 w-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Tambahkan item produk lainnya di sini --}}
                    <div class="group relative">
                      <img src="{{ asset('ikanbanyar.png') }}" alt="Front of men&#039;s Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80">
                      <div class="mt-4 flex justify-between">
                        <div>
                          <h3 class="text-sm text-gray-700">
                            <a href="#">
                              <span aria-hidden="true" class="absolute inset-0"></span>
                              Ikan Banyar
                            </a>
                          </h3>
                          <p class="mt-1 text-sm text-gray-500">(Rastrelliger Kanagurta)</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">$35</p>
                      </div>
                    </div>
              
                    <div class="group relative">
                      <img src="{{ asset('ikanbanyar.png') }}" alt="Front of men&#039;s Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80">
                      <div class="mt-4 flex justify-between">
                        <div>
                          <h3 class="text-sm text-gray-700">
                            <a href="#">
                              <span aria-hidden="true" class="absolute inset-0"></span>
                              Ikan Banyar
                            </a>
                          </h3>
                          <p class="mt-1 text-sm text-gray-500">(Rastrelliger Kanagurta)</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">$35</p>
                      </div>
                    </div>
        
                    <div class="group relative">
                      <img src="{{ asset('ikanbanyar.png') }}" alt="Front of men&#039;s Basic Tee in black." class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:opacity-75 lg:aspect-auto lg:h-80">
                      <div class="mt-4 flex justify-between">
                        <div>
                          <h3 class="text-sm text-gray-700">
                            <a href="#">
                              <span aria-hidden="true" class="absolute inset-0"></span>
                              Ikan Banyar
                            </a>
                          </h3>
                          <p class="mt-1 text-sm text-gray-500">(Rastrelliger Kanagurta)</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">$35</p>
                      </div>
                    </div>
        
                    <!-- More products... -->
                  </div>
                </div>
            </div>
        </div>
    </main>
@endsection