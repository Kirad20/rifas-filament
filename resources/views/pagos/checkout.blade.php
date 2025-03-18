<div class="bg-white dark:bg-background-dark/30 p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-accent dark:text-primary mb-6">Proceso de Pago</h2>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Resumen de compra -->
        <div class="bg-background dark:bg-background-dark/50 p-4 rounded-lg">
            <h3 class="text-lg font-semibold text-accent dark:text-primary mb-4">Resumen de tu compra</h3>

            <div class="space-y-3 text-text dark:text-text-light/80">
                <div class="flex justify-between">
                    <span>Rifa:</span>
                    <span class="font-medium">Nombre de la rifa</span>
                </div>
                <div class="flex justify-between">
                    <span>Boletos:</span>
                    <span class="font-medium">3 boletos (#01, #02, #03)</span>
                </div>
                <div class="flex justify-between">
                    <span>Precio unitario:</span>
                    <span class="font-medium">$50.00 MXN</span>
                </div>
                <hr class="border-gray-200 dark:border-gray-700">
                <div class="flex justify-between text-lg">
                    <span class="font-bold">Total:</span>
                    <span class="font-bold text-primary">$150.00 MXN</span>
                </div>
            </div>
        </div>

        <!-- Formulario de pago -->
        <div>
            <h3 class="text-lg font-semibold text-accent dark:text-primary mb-4">Información de pago</h3>

            <!-- Opciones de pago -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3 p-3 border border-primary/20 rounded-lg bg-primary-light/10 dark:bg-primary/10">
                    <input type="radio" name="payment_method" id="card" checked class="text-primary focus:ring-primary">
                    <label for="card" class="flex-1 cursor-pointer text-text dark:text-text-light">
                        Tarjeta de crédito/débito
                    </label>
                </div>

                <div class="flex items-center space-x-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <input type="radio" name="payment_method" id="paypal" class="text-primary focus:ring-primary">
                    <label for="paypal" class="flex-1 cursor-pointer text-text dark:text-text-light">
                        PayPal
                    </label>
                </div>

                <!-- Campos de pago -->
                <div class="mt-4 space-y-3">
                    <!-- Campos de pago -->
                    <div>
                        <label for="card_number" class="block text-sm font-medium text-text dark:text-text-light">Número de tarjeta</label>
                        <input type="text" id="card_number" name="card_number" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                    <div>
                        <label for="card_expiry" class="block text-sm font-medium text-text dark:text-text-light">Fecha de expiración</label>
                        <input type="text" id="card_expiry" name="card_expiry" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                    <div>
                        <label for="card_cvc" class="block text-sm font-medium text-text dark:text-text-light">CVC</label>
                        <input type="text" id="card_cvc" name="card_cvc" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                    </div>

                    <!-- Botón de pago -->
                    <button class="w-full bg-primary text-text-light py-3 rounded-lg hover:bg-primary-dark transition mt-6 btn-primary">
                        Completar pago
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
