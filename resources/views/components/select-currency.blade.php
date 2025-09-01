<div>
    {{-- <x-label for="currency">Devise<span class="text-red-500">*</span>:</x-label> --}}
    <x-select-input id="currency" name="currency">
        @foreach($currencies as $currency)
            <option value="{{$currency }}" {{ old('currency', $selectedCurrency) == $currency ? 'selected' : '' }}>{{ $currency }}</option>
        @endforeach
    </x-select-input>
</div>
