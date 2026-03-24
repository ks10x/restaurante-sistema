<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-orange-500 leading-tight">
            {{ __('Finalizar Pedido') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-stone-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-stone-800 overflow-hidden shadow-xl sm:rounded-lg p-8 border border-stone-700">
                
                <h2 class="text-3xl font-bold text-orange-500 mb-8 border-b border-stone-700 pb-4">
                    Finalizar seu Pedido
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    
                    <div class="text-stone-200">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Itens Selecionados
                        </h3>
                        
                        <div class="space-y-4">
                            {{-- Aqui você fará o @foreach($carrinho as $item) futuramente --}}
                            <div class="flex justify-between items-center bg-stone-700/50 p-4 rounded-lg">
                                <span>Pizza Margherita (1x)</span>
                                <span class="font-bold text-orange-400">R$ 45,00</span>
                            </div>

                            <div class="border-t border-stone-600 pt-4 mt-6">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total:</span>
                                    <span class="text-2xl text-orange-500">R$ 45,00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-stone-700/30 p-6 rounded-xl border border-stone-600">
                        <h3 class="text-xl font-semibold text-stone-100 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Forma de Pagamento
                        </h3>

                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            
                            <div class="space-y-4">
                                <label class="flex items-center p-4 bg-stone-800 rounded-lg border-2 border-orange-500 cursor-pointer">
                                    <input type="radio" name="payment_method" value="pix" checked class="text-orange-500 focus:ring-orange-500">
                                    <span class="ml-4 font-medium text-stone-100">PIX (Aprovação Instantânea)</span>
                                    <span class="ml-auto text-xs bg-green-900 text-green-300 px-2 py-1 rounded">5% OFF</span>
                                </label>

                                <label class="flex items-center p-4 bg-stone-800 rounded-lg border border-stone-600 cursor-pointer opacity-50 hover:opacity-100 transition">
                                    <input type="radio" name="payment_method" value="credit_card" class="text-orange-500 focus:ring-orange-500">
                                    <span class="ml-4 font-medium text-stone-100">Cartão de Crédito</span>
                                </label>
                            </div>

                            <button type="submit" class="w-full mt-8 bg-orange-600 hover:bg-orange-700 text-white font-bold py-4 rounded-lg transition-all transform hover:scale-[1.02] shadow-lg">
                                Pagar Agora
                            </button>
                        </form>

                        <p class="text-center text-stone-400 text-xs mt-4 italic">
                            Ambiente Seguro Trindade Tech & Pagar.me
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>