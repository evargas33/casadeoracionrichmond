<div class="min-h-screen bg-cream py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">

        @if ($submitted)
            <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                ✓ Solicitud de membresía enviada exitosamente. Recibirás un correo de confirmación.
            </div>
        @endif

        <div class="mb-8">
            <h1 class="text-3xl font-serif text-navy font-semibold mb-2">Solicitud de Membresía</h1>
            <div class="w-14 h-1 bg-gold rounded"></div>
            <p class="text-gray-600 mt-4">Por favor, completa el siguiente formulario para solicitar ser miembro de nuestra congregación.</p>
        </div>

        <form wire:submit="submit" class="space-y-6">
            <!-- Nombre y Apellido -->
            <div>
                <label for="full_name" class="block text-sm font-medium text-navy mb-1">
                    Nombre y Apellido <span class="text-red-500">*</span>
                </label>
                <input type="text" id="full_name" wire:model="full_name"
                    class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('full_name') border-red-500 @enderror"
                    placeholder="Tu nombre y apellido" required>
                @error('full_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Domicilio -->
            <div>
                <label for="address" class="block text-sm font-medium text-navy mb-1">
                    Domicilio <span class="text-red-500">*</span>
                </label>
                <input type="text" id="address" wire:model="address"
                    class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('address') border-red-500 @enderror"
                    placeholder="Tu dirección" required>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ciudad y Código Postal -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="city" class="block text-sm font-medium text-navy mb-1">
                        Ciudad <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="city" wire:model="city"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('city') border-red-500 @enderror"
                        placeholder="Ciudad" required>
                    @error('city')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="zip_code" class="block text-sm font-medium text-navy mb-1">
                        Código Postal <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="zip_code" wire:model="zip_code"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('zip_code') border-red-500 @enderror"
                        placeholder="Código postal" required>
                    @error('zip_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Fecha de Nacimiento -->
            <div>
                <label for="birth_date" class="block text-sm font-medium text-navy mb-1">
                    Fecha de Nacimiento <span class="text-red-500">*</span>
                </label>
                <input type="date" id="birth_date" wire:model="birth_date"
                    class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('birth_date') border-red-500 @enderror"
                    required>
                @error('birth_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Teléfono -->
            <div>
                <label for="phone" class="block text-sm font-medium text-navy mb-1">
                    Número de Teléfono <span class="text-red-500">*</span>
                </label>
                <input type="tel" id="phone" wire:model="phone"
                    class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('phone') border-red-500 @enderror"
                    placeholder="Tu teléfono" required>
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-navy mb-1">
                    Correo Electrónico <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" wire:model="email"
                    class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('email') border-red-500 @enderror"
                    placeholder="tu@email.com" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado Civil -->
            <div>
                <label for="marital_status" class="block text-sm font-medium text-navy mb-1">
                    Estado Civil <span class="text-red-500">*</span>
                </label>
                <select id="marital_status" wire:model="marital_status"
                    class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('marital_status') border-red-500 @enderror"
                    required>
                    <option value="">Selecciona una opción</option>
                    <option value="soltero">Soltero/a</option>
                    <option value="casado">Casado/a</option>
                </select>
                @error('marital_status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre de Esposo/a -->
            @if ($marital_status === 'casado')
                <div>
                    <label for="spouse_name" class="block text-sm font-medium text-navy mb-1">
                        Nombre de Esposo/a
                    </label>
                    <input type="text" id="spouse_name" wire:model="spouse_name"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('spouse_name') border-red-500 @enderror"
                        placeholder="Nombre de tu esposo/a">
                    @error('spouse_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- ¿Tiene Hijos? -->
            <div>
                <label class="block text-sm font-medium text-navy mb-2">¿Tiene hijos?</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="has_children" value="0" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold">
                        <span class="text-navy">No</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="has_children" value="1" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold">
                        <span class="text-navy">Sí</span>
                    </label>
                </div>
            </div>

            <!-- Nombres de Hijos -->
            @if ($has_children)
                <div>
                    <label for="children_names" class="block text-sm font-medium text-navy mb-1">
                        Nombres de Hijos
                    </label>
                    <textarea id="children_names" wire:model="children_names"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('children_names') border-red-500 @enderror"
                        placeholder="Lista los nombres de tus hijos" rows="2"></textarea>
                    @error('children_names')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- ¿Ya recibió al Señor Jesús? -->
            <div>
                <label class="block text-sm font-medium text-navy mb-2">
                    ¿Ya recibió al Señor Jesús como su Salvador? <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="received_jesus" value="0" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold">
                        <span class="text-navy">No</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="received_jesus" value="1" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold" required>
                        <span class="text-navy">Sí</span>
                    </label>
                </div>
                @error('received_jesus')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ¿Ya fue bautizado en agua? -->
            <div>
                <label class="block text-sm font-medium text-navy mb-2">
                    ¿Ya fue bautizado en agua? <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="baptized_water" value="0" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold">
                        <span class="text-navy">No</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="baptized_water" value="1" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold" required>
                        <span class="text-navy">Sí</span>
                    </label>
                </div>
                @error('baptized_water')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ¿En qué iglesia se bautizó? -->
            @if ($baptized_water)
                <div>
                    <label for="baptism_church" class="block text-sm font-medium text-navy mb-1">
                        ¿En qué iglesia o congregación se bautizó?
                    </label>
                    <input type="text" id="baptism_church" wire:model="baptism_church"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('baptism_church') border-red-500 @enderror"
                        placeholder="Nombre de la iglesia o congregación">
                    @error('baptism_church')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <!-- ¿Ha servido en algún ministerio? -->
            <div>
                <label class="block text-sm font-medium text-navy mb-2">¿Ha servido en algún ministerio?</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="has_served_ministry" value="0" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold">
                        <span class="text-navy">No</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="has_served_ministry" value="1" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold">
                        <span class="text-navy">Sí</span>
                    </label>
                </div>
            </div>

            <!-- ¿Le gustaría servir en algún ministerio? -->
            <div>
                <label class="block text-sm font-medium text-navy mb-2">¿Le gustaría servir en algún ministerio?</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="wants_serve_ministry" value="0" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold">
                        <span class="text-navy">No</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model="wants_serve_ministry" value="1" class="w-4 h-4 text-gold border-navy/15 focus:ring-gold">
                        <span class="text-navy">Sí</span>
                    </label>
                </div>
            </div>

            <!-- Contacto de Emergencia -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-navy mb-4">Contacto de Emergencia</h3>

                <div class="mb-4">
                    <label for="emergency_contact_name" class="block text-sm font-medium text-navy mb-1">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="emergency_contact_name" wire:model="emergency_contact_name"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('emergency_contact_name') border-red-500 @enderror"
                        placeholder="Nombre del contacto" required>
                    @error('emergency_contact_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="emergency_contact_phone" class="block text-sm font-medium text-navy mb-1">
                        Teléfono <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="emergency_contact_phone" wire:model="emergency_contact_phone"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('emergency_contact_phone') border-red-500 @enderror"
                        placeholder="Teléfono del contacto" required>
                    @error('emergency_contact_phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Compromiso -->
            <div class="border-t border-gray-200 pt-6">
                <div class="bg-navy/5 border border-navy/10 rounded-lg p-4 mb-4">
                    <p class="text-sm text-navy leading-relaxed">
                        Al ser parte de esta congregación me comprometo a buscar la unidad, a participar en todas las actividades y ser un colaborador a través de mis diezmos y ofrendas para el sostenimiento de la obra del Señor Jesucristo.
                    </p>
                </div>

                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" wire:model="commitment_accepted"
                        class="mt-1 w-4 h-4 text-gold border-navy/15 rounded focus:ring-gold"
                        required>
                    <span class="text-sm text-navy">
                        Acepto y me comprometo con esta declaración <span class="text-red-500">*</span>
                    </span>
                </label>
                @error('commitment_accepted')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Firma y Fecha -->
            <div class="border-t border-gray-200 pt-6 grid grid-cols-2 gap-4">
                <div>
                    <label for="signature" class="block text-sm font-medium text-navy mb-1">
                        Firma (Tu nombre completo) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="signature" wire:model="signature"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy placeholder-gray-400 focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('signature') border-red-500 @enderror"
                        placeholder="Tu nombre completo" required>
                    @error('signature')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="submission_date" class="block text-sm font-medium text-navy mb-1">
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="submission_date" wire:model="submission_date"
                        class="w-full bg-white border border-navy/15 rounded-lg px-4 py-2 text-navy focus:outline-none focus:border-gold focus:ring-1 focus:ring-gold @error('submission_date') border-red-500 @enderror"
                        required>
                    @error('submission_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botón Enviar -->
            <div class="border-t border-gray-200 pt-6">
                <button type="submit"
                    class="w-full bg-navy hover:bg-navy/90 text-white font-medium py-3 rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Enviar Solicitud</span>
                    <span wire:loading>Enviando...</span>
                </button>
            </div>
        </form>
    </div>
</div>
